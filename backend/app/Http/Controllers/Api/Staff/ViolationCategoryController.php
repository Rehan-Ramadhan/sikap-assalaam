<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\ViolationCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ViolationCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ViolationCategory::query();

        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $data = $query
            ->orderBy('poin')
            ->orderBy('nama_pelanggaran')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Kategori pelanggaran berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggaran' => ['required', 'string', 'max:100'],
            'poin' => ['required', 'integer', 'min:1'],
            'kategori' => [
                'required',
                Rule::in(['ringan', 'sedang', 'berat']),
            ],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = ViolationCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori pelanggaran berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(ViolationCategory $violationCategory)
    {
        return response()->json([
            'success' => true,
            'message' => 'Kategori pelanggaran berhasil diambil.',
            'data' => $violationCategory,
        ]);
    }

    public function update(
        Request $request,
        ViolationCategory $violationCategory
    ) {
        $validated = $request->validate([
            'nama_pelanggaran' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],
            'poin' => ['sometimes', 'required', 'integer', 'min:1'],
            'kategori' => [
                'sometimes',
                'required',
                Rule::in(['ringan', 'sedang', 'berat']),
            ],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $violationCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori pelanggaran berhasil diperbarui.',
            'data' => $violationCategory->fresh(),
        ]);
    }

    public function destroy(ViolationCategory $violationCategory)
    {
        if ($violationCategory->violations()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori sudah digunakan dan tidak dapat dihapus. Nonaktifkan saja.',
                'data' => null,
            ], 422);
        }

        $violationCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori pelanggaran berhasil dihapus.',
            'data' => null,
        ]);
    }
}
