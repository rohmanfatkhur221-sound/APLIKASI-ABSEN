<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Barang::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => [
                'required', 'string', 'max:30', 'unique:barang,kode_barang',
            ],
            'nama_barang' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['sometimes', 'required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $barang = Barang::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil ditambahkan.',
            'data' => $barang,
        ], 201);
    }

    public function show(Barang $barang)
    {
        return response()->json([
            'success' => true,
            'data' => $barang,
        ]);
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => [
                'sometimes', 'required', 'string', 'max:30',
                Rule::unique('barang', 'kode_barang')->ignore($barang->id),
            ],
            'nama_barang' => ['sometimes', 'required', 'string', 'max:100'],
            'harga' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stok' => ['sometimes', 'required', 'integer', 'min:0'],
            'deskripsi' => ['sometimes', 'nullable', 'string'],
        ]);

        $barang->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diperbarui.',
            'data' => $barang,
        ]);
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil dihapus.',
        ]);
    }
}