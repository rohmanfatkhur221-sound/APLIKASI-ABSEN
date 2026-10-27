<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AbsensiMurid;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AbsensiMuridController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => AbsensiMurid::with('murid')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'murid_id' => ['required', 'integer', 'exists:murid,id'],
            'tanggal' => ['required', 'date'],
            'status' => [
                'required',
                Rule::in(['hadir', 'izin', 'sakit', 'alpa']),
            ],
            'keterangan' => ['nullable', 'string'],
        ]);

        $duplicate = AbsensiMurid::where(
            'murid_id',
            $validated['murid_id']
        )->whereDate(
            'tanggal',
            $validated['tanggal']
        )->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Murid sudah memiliki absensi pada tanggal tersebut.',
            ], 422);
        }

        $absensi = AbsensiMurid::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Absensi murid berhasil ditambahkan.',
            'data' => $absensi->load('murid'),
        ], 201);
    }

    public function show(AbsensiMurid $absensiMurid)
    {
        return response()->json([
            'success' => true,
            'data' => $absensiMurid->load('murid'),
        ]);
    }

    public function update(
        Request $request,
        AbsensiMurid $absensiMurid
    ) {
        $validated = $request->validate([
            'murid_id' => [
                'sometimes', 'required', 'integer', 'exists:murid,id',
            ],
            'tanggal' => ['sometimes', 'required', 'date'],
            'status' => [
                'sometimes',
                'required',
                Rule::in(['hadir', 'izin', 'sakit', 'alpa']),
            ],
            'keterangan' => ['sometimes', 'nullable', 'string'],
        ]);

        $muridId = $validated['murid_id']
            ?? $absensiMurid->murid_id;

        $tanggal = $validated['tanggal']
            ?? $absensiMurid->tanggal->format('Y-m-d');

        $duplicate = AbsensiMurid::where('murid_id', $muridId)
            ->whereDate('tanggal', $tanggal)
            ->where('id', '!=', $absensiMurid->id)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Murid sudah memiliki absensi pada tanggal tersebut.',
            ], 422);
        }

        $absensiMurid->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Absensi murid berhasil diperbarui.',
            'data' => $absensiMurid->fresh()->load('murid'),
        ]);
    }

    public function destroy(AbsensiMurid $absensiMurid)
    {
        $absensiMurid->delete();

        return response()->json([
            'success' => true,
            'message' => 'Absensi murid berhasil dihapus.',
        ]);
    }
}
