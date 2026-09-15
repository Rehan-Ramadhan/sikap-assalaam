<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\PointThreshold;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PointThresholdController extends Controller
{
    public function index(Request $request)
    {
        $query = PointThreshold::query();

        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->filled('tahap')) {
            $query->where('tahap', $request->tahap);
        }

        $data = $query
            ->orderBy('poin_minimal')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Threshold poin berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'poin_minimal' => [
                'required',
                'integer',
                'min:1',
            ],
            'tahap' => [
                'required',
                Rule::in([
                    'wali_kelas',
                    'bk',
                    'kesiswaan',
                    'kepala_sekolah',
                ]),
            ],
            'nama_tindakan' => [
                'required',
                'string',
                'max:100',
            ],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = PointThreshold::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Threshold berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(PointThreshold $pointThreshold)
    {
        return response()->json([
            'success' => true,
            'message' => 'Threshold berhasil diambil.',
            'data' => $pointThreshold,
        ]);
    }

    public function update(
        Request $request,
        PointThreshold $pointThreshold
    ) {
        $validated = $request->validate([
            'poin_minimal' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
            ],
            'tahap' => [
                'sometimes',
                'required',
                Rule::in([
                    'wali_kelas',
                    'bk',
                    'kesiswaan',
                    'kepala_sekolah',
                ]),
            ],
            'nama_tindakan' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $pointThreshold->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Threshold berhasil diperbarui.',
            'data' => $pointThreshold->fresh(),
        ]);
    }

    public function destroy(PointThreshold $pointThreshold)
    {
        if ($pointThreshold->interventions()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Threshold sudah digunakan dan tidak dapat dihapus. Nonaktifkan saja.',
                'data' => null,
            ], 422);
        }

        $pointThreshold->delete();

        return response()->json([
            'success' => true,
            'message' => 'Threshold berhasil dihapus.',
            'data' => null,
        ]);
    }
}
