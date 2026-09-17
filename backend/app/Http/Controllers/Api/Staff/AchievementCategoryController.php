<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\AchievementCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AchievementCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = AchievementCategory::query();

        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $data = $query
            ->orderBy('tingkat')
            ->orderBy('nama_prestasi')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Kategori prestasi berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_prestasi' => ['required', 'string', 'max:100'],
            'poin' => ['required', 'integer', 'min:1'],
            'tingkat' => [
                'required',
                Rule::in([
                    'sekolah',
                    'kecamatan',
                    'kabupaten',
                    'provinsi',
                    'nasional',
                    'internasional',
                ]),
            ],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = AchievementCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori prestasi berhasil ditambahkan.',
            'data' => $data,
        ], 201);
    }

    public function show(AchievementCategory $achievementCategory)
    {
        return response()->json([
            'success' => true,
            'message' => 'Kategori prestasi berhasil diambil.',
            'data' => $achievementCategory,
        ]);
    }

    public function update(
        Request $request,
        AchievementCategory $achievementCategory
    ) {
        $validated = $request->validate([
            'nama_prestasi' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],
            'poin' => ['sometimes', 'required', 'integer', 'min:1'],
            'tingkat' => [
                'sometimes',
                'required',
                Rule::in([
                    'sekolah',
                    'kecamatan',
                    'kabupaten',
                    'provinsi',
                    'nasional',
                    'internasional',
                ]),
            ],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $achievementCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori prestasi berhasil diperbarui.',
            'data' => $achievementCategory->fresh(),
        ]);
    }

    public function destroy(AchievementCategory $achievementCategory)
    {
        if ($achievementCategory->achievements()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori sudah digunakan dan tidak dapat dihapus. Nonaktifkan saja.',
                'data' => null,
            ], 422);
        }

        $achievementCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori prestasi berhasil dihapus.',
            'data' => null,
        ]);
    }
}
