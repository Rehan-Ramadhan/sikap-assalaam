<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\AchievementCategory;
use App\Models\StudentAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAchievementController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentAchievement::with([
            'student.user',
            'category',
            'staff.user',
            'cancelledBy',
        ]);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query
            ->latest('tanggal_prestasi')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Data prestasi berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
            ],
            'category_id' => [
                'required',
                'exists:achievement_categories,id',
            ],
            'tanggal_prestasi' => [
                'required',
                'date',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $category = AchievementCategory::findOrFail(
            $validated['category_id']
        );

        if (!$category->status) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori prestasi sedang nonaktif.',
                'data' => null,
            ], 422);
        }

        $achievement = DB::transaction(function () use (
            $validated,
            $category,
            $request
        ) {
            return StudentAchievement::create([
                'student_id' => $validated['student_id'],
                'category_id' => $validated['category_id'],
                'staff_id' => $request->user()->staff->id,
                'tanggal_prestasi' => $validated['tanggal_prestasi'],
                'keterangan' => $validated['keterangan'] ?? null,
                'poin_tercatat' => $category->poin,
                'status' => 'aktif',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil dicatat.',
            'data' => $achievement->fresh()->load([
                'student.user',
                'category',
                'staff.user',
            ]),
        ], 201);
    }

    public function show(StudentAchievement $studentAchievement)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data prestasi berhasil diambil.',
            'data' => $studentAchievement->load([
                'student.user',
                'category',
                'staff.user',
            ]),
        ]);
    }

    public function update(
        Request $request,
        StudentAchievement $studentAchievement
    ) {
        if ($studentAchievement->status === 'dibatalkan') {
            return response()->json([
                'success' => false,
                'message' => 'Prestasi yang dibatalkan tidak dapat diubah.',
                'data' => null,
            ], 422);
        }

        $validated = $request->validate([
            'category_id' => [
                'sometimes',
                'required',
                'exists:achievement_categories,id',
            ],
            'tanggal_prestasi' => [
                'sometimes',
                'required',
                'date',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $data = collect($validated)
            ->only([
                'category_id',
                'tanggal_prestasi',
                'keterangan',
            ])
            ->toArray();

        if (isset($validated['category_id'])) {
            $category = AchievementCategory::findOrFail(
                $validated['category_id']
            );

            if (!$category->status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori prestasi sedang nonaktif.',
                    'data' => null,
                ], 422);
            }

            $data['poin_tercatat'] = $category->poin;
        }

        $studentAchievement->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil diperbarui.',
            'data' => $studentAchievement->fresh()->load([
                'student.user',
                'category',
                'staff.user',
            ]),
        ]);
    }

    public function cancel(
        Request $request,
        StudentAchievement $studentAchievement
    ) {
        $validated = $request->validate([
            'cancel_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        if ($studentAchievement->status === 'dibatalkan') {
            return response()->json([
                'success' => false,
                'message' => 'Prestasi sudah dibatalkan.',
                'data' => null,
            ], 422);
        }

        $studentAchievement->update([
            'status' => 'dibatalkan',
            'cancelled_by' => $request->user()->id,
            'cancelled_at' => now(),
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil dibatalkan.',
            'data' => $studentAchievement->fresh()->load([
                'student.user',
                'category',
                'cancelledBy',
            ]),
        ]);
    }
}
