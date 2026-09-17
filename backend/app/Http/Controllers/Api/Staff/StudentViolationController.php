<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\Notification;
use App\Models\PointThreshold;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentViolation::with([
            'student.user',
            'category',
            'staff.user',
            'cancelledBy',
        ]);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tanggal_kejadian',
                '>=',
                $request->tanggal_mulai
            );
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate(
                'tanggal_kejadian',
                '<=',
                $request->tanggal_selesai
            );
        }

        $data = $query
            ->latest('tanggal_kejadian')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggaran berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:violation_categories,id',
            ],
            'tanggal_kejadian' => [
                'required',
                'date',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $staff = $request->user()->staff;

        $violation = DB::transaction(function () use (
            $validated,
            $staff
        ) {
            $category = \App\Models\ViolationCategory::findOrFail(
                $validated['category_id']
            );

            if (!$category->status) {
                abort(422, 'Kategori pelanggaran sedang nonaktif.');
            }

            $violation = StudentViolation::create([
                'student_id' => $validated['student_id'],
                'category_id' => $validated['category_id'],
                'staff_id' => $staff->id,
                'tanggal_kejadian' => $validated['tanggal_kejadian'],
                'keterangan' => $validated['keterangan'] ?? null,
                'poin_tercatat' => $category->poin,
                'status' => 'aktif',
                'status_penanganan' => 'belum_ditangani',
            ]);

            $this->processThreshold(
                $violation->student_id
            );

            return $violation;
        });

        return response()->json([
            'success' => true,
            'message' => 'Pelanggaran berhasil dicatat.',
            'data' => $violation->fresh()->load([
                'student.user',
                'category',
                'staff.user',
            ]),
        ], 201);
    }

    public function show(StudentViolation $studentViolation)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data pelanggaran berhasil diambil.',
            'data' => $studentViolation->load([
                'student.user',
                'category',
                'staff.user',
                'cancelledBy',
            ]),
        ]);
    }

    public function update(
        Request $request,
        StudentViolation $studentViolation
    ) {
        if ($studentViolation->status === 'dibatalkan') {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggaran yang sudah dibatalkan tidak dapat diubah.',
                'data' => null,
            ], 422);
        }

        $validated = $request->validate([
            'category_id' => [
                'sometimes',
                'required',
                'exists:violation_categories,id',
            ],
            'tanggal_kejadian' => [
                'sometimes',
                'required',
                'date',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $studentViolation
        ) {
            $data = collect($validated)
                ->only([
                    'category_id',
                    'tanggal_kejadian',
                    'keterangan',
                ])
                ->toArray();

            if (isset($validated['category_id'])) {
                $category = \App\Models\ViolationCategory::findOrFail(
                    $validated['category_id']
                );

                if (!$category->status) {
                    abort(422, 'Kategori pelanggaran sedang nonaktif.');
                }

                $data['poin_tercatat'] = $category->poin;
            }

            $studentViolation->update($data);

            $this->processThreshold(
                $studentViolation->student_id
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Pelanggaran berhasil diperbarui.',
            'data' => $studentViolation->fresh()->load([
                'student.user',
                'category',
                'staff.user',
            ]),
        ]);
    }

    public function cancel(
        Request $request,
        StudentViolation $studentViolation
    ) {
        $validated = $request->validate([
            'cancel_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        if ($studentViolation->status === 'dibatalkan') {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggaran sudah dibatalkan.',
                'data' => null,
            ], 422);
        }

        DB::transaction(function () use (
            $validated,
            $studentViolation,
            $request
        ) {
            $studentViolation->update([
                'status' => 'dibatalkan',
                'cancelled_by' => $request->user()->id,
                'cancelled_at' => now(),
                'cancel_reason' => $validated['cancel_reason'],
            ]);

            $this->processThreshold(
                $studentViolation->student_id
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Pelanggaran berhasil dibatalkan.',
            'data' => $studentViolation->fresh()->load([
                'student.user',
                'category',
                'cancelledBy',
            ]),
        ]);
    }

    private function processThreshold(int $studentId): void
    {
        $totalPoints = StudentViolation::where(
            'student_id',
            $studentId
        )
            ->where('status', 'aktif')
            ->sum('poin_tercatat');

        $threshold = PointThreshold::where('status', true)
            ->where('poin_minimal', '<=', $totalPoints)
            ->orderByDesc('poin_minimal')
            ->first();

        if (!$threshold) {
            return;
        }

        $exists = Intervention::where('student_id', $studentId)
            ->where('threshold_id', $threshold->id)
            ->exists();

        if ($exists) {
            return;
        }

        $student = Student::with('user')->findOrFail($studentId);

        $staff = Staff::where('jabatan', $threshold->tahap)
            ->where('status', 'aktif')
            ->first();

        if (!$staff) {
            $staff = Staff::where('status', 'aktif')->first();
        }

        if (!$staff) {
            return;
        }

        $intervention = Intervention::create([
            'student_id' => $studentId,
            'staff_id' => $staff->id,
            'threshold_id' => $threshold->id,
            'tahap' => $threshold->tahap,
            'poin_saat_penanganan' => $totalPoints,
            'status' => 'menunggu',
            'tanggal_mulai' => now()->toDateString(),
            'catatan' => null,
        ]);

        Notification::create([
            'user_id' => $student->user_id,
            'student_id' => $studentId,
            'type' => 'intervention',
            'title' => 'Penanganan Pelanggaran',
            'message' => "Poin pelanggaran Anda telah mencapai {$totalPoints} poin. Anda masuk tahap {$threshold->tahap}.",
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $staff->user_id,
            'student_id' => $studentId,
            'type' => 'intervention',
            'title' => 'Penanganan Baru',
            'message' => "Siswa {$student->user->name} mencapai threshold {$threshold->poin_minimal} poin dan membutuhkan penanganan {$threshold->tahap}.",
            'is_read' => false,
        ]);

        StudentViolation::where('student_id', $studentId)
            ->where('status', 'aktif')
            ->where(
                'status_penanganan',
                'belum_ditangani'
            )
            ->update([
                'status_penanganan' => $threshold->tahap,
            ]);
    }
}
