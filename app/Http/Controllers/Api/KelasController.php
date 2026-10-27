<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Kelas::with('waliGuru', 'murid')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => [
                'required', 'string', 'max:50', 'unique:kelas,nama_kelas',
            ],
            'tingkat' => ['required', 'integer', 'between:1,12'],
            'wali_guru_id' => [
                'nullable', 'integer', 'exists:gurus,id',
            ],
        ]);

        $kelas = Kelas::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil ditambahkan.',
            'data' => $kelas->load('waliGuru'),
        ], 201);
    }

    public function show(Kelas $kelas)
    {
        return response()->json([
            'success' => true,
            'data' => $kelas->load('waliGuru', 'murid'),
        ]);
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => [
                'sometimes', 'required', 'string', 'max:50',
                Rule::unique('kelas', 'nama_kelas')->ignore($kelas->id),
            ],
            'tingkat' => [
                'sometimes', 'required', 'integer', 'between:1,12',
            ],
            'wali_guru_id' => [
                'sometimes', 'nullable', 'integer', 'exists:gurus,id',
            ],
        ]);

        $kelas->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diperbarui.',
            'data' => $kelas->load('waliGuru'),
        ]);
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil dihapus.',
        ]);
    }
}