<?php

namespace App\Http\Requests;

use App\Services\KriteriaService;
use Illuminate\Foundation\Http\FormRequest;

class StoreKriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage kriteria');
    }

    public function rules(): array
    {
        return [
            'kode_kriteria' => ['required', 'string', 'max:10', 'unique:kriteria,kode_kriteria'],
            'nama_kriteria' => ['required', 'string', 'max:255'],
            'bobot' => ['required', 'numeric', 'min:0', 'max:100'],
            'atribut' => ['required', 'in:benefit,cost'],
            'jenis_input' => ['required', 'in:numeric,scoring'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $bobot = (float) $this->input('bobot', 0);
            $service = app(KriteriaService::class);
            
            if ($service->willExceedMaxBobot($bobot)) {
                $remaining = $service->getRemainingBobot();
                $validator->errors()->add('bobot', "Total bobot tidak boleh melebihi 100%. Sisa bobot yang tersedia adalah {$remaining}%.");
            }
        });
    }
}
