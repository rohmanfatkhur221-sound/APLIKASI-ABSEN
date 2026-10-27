<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Guru::with('kelasWali')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:30', 'unique:gurus,nip'],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'unique:gurus,email'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $guru = Guru::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan.',
            'data' => $guru,
        ], 201);
    }

    public function show(Guru $guru)
    {
        return response()->json([
            'success' => true,
            'data' => $guru->load('kelasWali'),
        ]);
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nip' => [
                'sometimes', 'required', 'string', 'max:30',
                Rule::unique('gurus', 'nip')->ignore($guru->id),
            ],
            'nama' => ['sometimes', 'required', 'string', 'max:100'],
            'email' => [
                'sometimes', 'nullable', 'email',
                Rule::unique('gurus', 'email')->ignore($guru->id),
            ],
            'no_telepon' => ['sometimes', 'nullable', 'string', 'max:20'],
            'alamat' => ['sometimes', 'nullable', 'string'],
        ]);

        $guru->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diperbarui.',
            'data' => $guru,
        ]);
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus.',
        ]);
    }
}