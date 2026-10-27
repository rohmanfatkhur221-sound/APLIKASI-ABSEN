<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AbsensiGuruController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => AbsensiGuru::with('guru')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => [
                'required',
                'integer',
                'exists:gurus,id',
            ],
            'tanggal' => [
                'required',
                'date',
            ],
            'status' => [
                'required',
                Rule::in(['hadir', 'izin', 'sakit', 'alpa']),
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $sudahAbsen = AbsensiGuru::where(
            'guru_id',
            $validated['guru_id']
        )->whereDate(
            'tanggal',
            $validated['tanggal']
        )->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Guru sudah memiliki absensi pada tanggal tersebut.',
            ], 422);
        }

        $absensi = AbsensiGuru::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Absensi guru berhasil ditambahkan.',
            'data' => $absensi->load('guru'),
        ], 201);
    }

    public function show(AbsensiGuru $absensiGuru)
    {
        return response()->json([
            'success' => true,
            'data' => $absensiGuru->load('guru'),
        ]);
    }

    public function update(
        Request $request,
        AbsensiGuru $absensiGuru
    ) {
        $validated = $request->validate([
            'guru_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:gurus,id',
            ],
            'tanggal' => [
                'sometimes',
                'required',
                'date',
            ],
            'status' => [
                'sometimes',
                'required',
                Rule::in(['hadir', 'izin', 'sakit', 'alpa']),
            ],
            'keterangan' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ]);

        $guruId = $validated['guru_id']
            ?? $absensiGuru->guru_id;

        $tanggal = $validated['tanggal']
            ?? $absensiGuru->tanggal->format('Y-m-d');

        $duplikat = AbsensiGuru::where('guru_id', $guruId)
            ->whereDate('tanggal', $tanggal)
            ->where('id', '!=', $absensiGuru->id)
            ->exists();

        if ($duplikat) {
            return response()->json([
                'success' => false,
                'message' => 'Guru sudah memiliki absensi pada tanggal tersebut.',
            ], 422);
        }

        $absensiGuru->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Absensi guru berhasil diperbarui.',
            'data' => $absensiGuru->fresh()->load('guru'),
        ]);
    }

    public function destroy(AbsensiGuru $absensiGuru)
    {
        $absensiGuru->delete();

        return response()->json([
            'success' => true,
            'message' => 'Absensi guru berhasil dihapus.',
        ]);
    }
}
