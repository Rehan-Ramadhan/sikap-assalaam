<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\InterventionLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InterventionController extends Controller
{
    public function index(Request $request)
    {
        $query = Intervention::with([
            'student.user',
            'staff.user',
            'threshold',
        ]);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahap')) {
            $query->where('tahap', $request->tahap);
        }

        $data = $query
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Data penanganan berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function show(Intervention $intervention)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data penanganan berhasil diambil.',
            'data' => $intervention->load([
                'student.user',
                'staff.user',
                'threshold',
                'logs.staff.user',
            ]),
        ]);
    }

    public function update(
        Request $request,
        Intervention $intervention
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'menunggu',
                    'diproses',
                    'selesai',
                ]),
            ],
            'catatan' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $validated,
            $intervention
        ) {
            $intervention->update([
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? $intervention->catatan,
                'tanggal_selesai' =>
                $validated['status'] === 'selesai'
                    ? now()->toDateString()
                    : null,
            ]);

            InterventionLog::create([
                'intervention_id' => $intervention->id,
                'staff_id' => $request->user()->staff->id,
                'status' => $validated['status'] === 'menunggu'
                    ? 'menunggu'
                    : $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            Notification::create([
                'user_id' => $intervention->student->user_id,
                'student_id' => $intervention->student_id,
                'type' => 'intervention_update',
                'title' => 'Pembaruan Penanganan',
                'message' => "Status penanganan Anda diperbarui menjadi {$validated['status']}.",
                'is_read' => false,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Penanganan berhasil diperbarui.',
            'data' => $intervention->fresh()->load([
                'student.user',
                'staff.user',
                'threshold',
                'logs.staff.user',
            ]),
        ]);
    }

    public function addLog(
        Request $request,
        Intervention $intervention
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'menunggu',
                    'diproses',
                    'selesai',
                ]),
            ],
            'catatan' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $validated,
            $intervention
        ) {
            InterventionLog::create([
                'intervention_id' => $intervention->id,
                'staff_id' => $request->user()->staff->id,
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $mainStatus = $validated['status'] === 'menunggu'
                ? 'menunggu'
                : $validated['status'];

            $intervention->update([
                'status' => $mainStatus,
                'tanggal_selesai' =>
                $mainStatus === 'selesai'
                    ? now()->toDateString()
                    : $intervention->tanggal_selesai,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Log penanganan berhasil ditambahkan.',
            'data' => $intervention->fresh()->load([
                'student.user',
                'staff.user',
                'threshold',
                'logs.staff.user',
            ]),
        ]);
    }
}
