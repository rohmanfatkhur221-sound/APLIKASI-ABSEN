<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MuridController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Murid::with('kelas')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:30', 'unique:murid,nis'],
            'nama' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => [
                'nullable', 'date', 'before_or_equal:today',
            ],
            'jenis_kelamin' => [
                'nullable', Rule::in(['Laki-laki', 'Perempuan']),
            ],
            'alamat' => ['nullable', 'string'],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
        ]);

        $murid = Murid::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data murid berhasil ditambahkan.',
            'data' => $murid->load('kelas'),
        ], 201);
    }

    public function show(Murid $murid)
    {
        return response()->json([
            'success' => true,
            'data' => $murid->load('kelas'),
        ]);
    }

    public function update(Request $request, Murid $murid)
    {
        $validated = $request->validate([
            'nis' => [
                'sometimes', 'required', 'string', 'max:30',
                Rule::unique('murid', 'nis')->ignore($murid->id),
            ],
            'nama' => ['sometimes', 'required', 'string', 'max:100'],
            'tanggal_lahir' => [
                'sometimes', 'nullable', 'date', 'before_or_equal:today',
            ],
            'jenis_kelamin' => [
                'sometimes', 'nullable',
                Rule::in(['Laki-laki', 'Perempuan']),
            ],
            'alamat' => ['sometimes', 'nullable', 'string'],
            'kelas_id' => [
                'sometimes', 'nullable', 'integer', 'exists:kelas,id',
            ],
        ]);

        $murid->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data murid berhasil diperbarui.',
            'data' => $murid->load('kelas'),
        ]);
    }

    public function destroy(Murid $murid)
    {
        $murid->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data murid berhasil dihapus.',
        ]);
    }
}