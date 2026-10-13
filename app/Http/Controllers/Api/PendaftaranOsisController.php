<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PendaftaranOsisResource;
use App\Models\PendaftaranOsis;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PendaftaranOsisController extends Controller
{
    /**
     * Menampilkan daftar seluruh pendaftaran OSIS beserta relasi siswa.
     */
    public function index(): AnonymousResourceCollection
    {
        $pendaftaran = PendaftaranOsis::with([
            'siswa.kelas',
            'siswa.jenisKelamin',
        ])->get();

        return PendaftaranOsisResource::collection($pendaftaran);
    }

    /**
     * Menampilkan detail satu pendaftaran OSIS berdasarkan ID.
     */
    public function show(string|int $id): JsonResponse|PendaftaranOsisResource
    {
        $pendaftaran = PendaftaranOsis::with([
            'siswa.kelas',
            'siswa.jenisKelamin',
        ])->find($id);

        if (! $pendaftaran) {
            return response()->json([
                'message' => 'Pendaftaran tidak ditemukan.',
            ], 404);
        }

        return new PendaftaranOsisResource($pendaftaran);
    }

    /**
     * Menyimpan pendaftaran OSIS baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'siswa_id' => [
                'required',
                'integer',
                'exists:siswa,id',
                'unique:pendaftaran_osis,siswa_id',
            ],
            'pilihan_bidang' => [
                'required',
                'string',
                'in:kepemimpinan,keagamaan,olahraga,seni',
            ],
            'motivasi' => [
                'required',
                'string',
                'min:20',
                'max:300',
            ],
        ]);

        $pendaftaran = PendaftaranOsis::create([
            'siswa_id' => $validated['siswa_id'],
            'pilihan_bidang' => $validated['pilihan_bidang'],
            'motivasi' => $validated['motivasi'],
        ]);

        $pendaftaran->load(['siswa.kelas', 'siswa.jenisKelamin']);

        return response()->json([
            'message' => 'Pendaftaran berhasil dibuat.',
            'data' => new PendaftaranOsisResource($pendaftaran),
        ], 201);
    }

    /**
     * Memperbarui pendaftaran OSIS yang sudah ada.
     */
    public function update(Request $request, string|int $id): JsonResponse
    {
        $pendaftaran = PendaftaranOsis::find($id);

        if (! $pendaftaran) {
            return response()->json([
                'message' => 'Pendaftaran tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'siswa_id' => [
                'required',
                'integer',
                'exists:siswa,id',
                'unique:pendaftaran_osis,siswa_id,' . $pendaftaran->id,
            ],
            'pilihan_bidang' => [
                'required',
                'string',
                'in:kepemimpinan,keagamaan,olahraga,seni',
            ],
            'motivasi' => [
                'required',
                'string',
                'min:20',
                'max:300',
            ],
        ]);

        $pendaftaran->update([
            'siswa_id' => $validated['siswa_id'],
            'pilihan_bidang' => $validated['pilihan_bidang'],
            'motivasi' => $validated['motivasi'],
        ]);

        $pendaftaran->load(['siswa.kelas', 'siswa.jenisKelamin']);

        return response()->json([
            'message' => 'Pendaftaran berhasil diperbarui.',
            'data' => new PendaftaranOsisResource($pendaftaran),
        ], 200);
    }

    /**
     * Menghapus pendaftaran OSIS tanpa menghapus data siswa.
     */
    public function destroy(string|int $id): JsonResponse|Response
    {
        $pendaftaran = PendaftaranOsis::find($id);

        if (! $pendaftaran) {
            return response()->json([
                'message' => 'Pendaftaran tidak ditemukan.',
            ], 404);
        }

        $pendaftaran->delete();

        return response()->noContent();
    }
}
