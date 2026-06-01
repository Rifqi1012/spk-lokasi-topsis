<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage observasi');
    }

    public function rules(): array
    {
        return [
            'lokasi_id' => ['required', 'exists:lokasi,lokasi_id'],
            'tanggal_observasi' => ['required', 'date'],
            
            // Detail Bangunan & Wilayah
            'jenis_bangunan' => ['required', 'string', 'max:100'],
            'luas_tanah' => ['required', 'numeric', 'min:0'],
            'luas_bangunan' => ['required', 'numeric', 'min:0'],
            'jumlah_ruangan' => ['required', 'integer', 'min:0'],
            'jumlah_wc' => ['required', 'integer', 'min:0'],
            'sumber_air' => ['required', 'string', 'max:100'],
            'listrik' => ['nullable', 'boolean'],
            'tahun_bps' => ['nullable', 'integer'],
            'kode_wilayah_bps' => ['nullable', 'string', 'max:50'],
            
            // Input Nilai (Topsis)
            'harga_sewa' => ['required', 'numeric', 'min:0'],
            'kepadatan_penduduk' => ['required', 'numeric', 'min:0'],
            'jumlah_kompetitor' => ['required', 'integer', 'min:0'],
            'jarak_rph' => ['required', 'numeric', 'min:0'], // In KM
            
            // Indikator Aksesibilitas
            'akses_roda4' => ['nullable', 'boolean'],
            'jalan_bagus' => ['nullable', 'boolean'],
            'dekat_fasilitas' => ['nullable', 'boolean'],
            
            // Indikator Kelayakan Bangunan
            'bangunan_layak' => ['nullable', 'boolean'],
            'ventilasi_baik' => ['nullable', 'boolean'],
            'air_listrik_memadai' => ['nullable', 'boolean'],
            
            'catatan' => ['nullable', 'string'],
            
            // Photos (Max 10 files, total 20MB limit handled by php.ini, max 2MB per file here)
            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB per file max, will be compressed
        ];
    }
}
