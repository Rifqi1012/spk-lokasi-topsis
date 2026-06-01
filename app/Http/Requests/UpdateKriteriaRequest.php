<?php

namespace App\Http\Requests;

use App\Services\KriteriaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage kriteria');
    }

    public function rules(): array
    {
        $kriteriaId = $this->route('kriteria')->kriteria_id ?? $this->route('kriteria');

        return [
            'kode_kriteria' => ['required', 'string', 'max:10', Rule::unique('kriteria', 'kode_kriteria')->ignore($kriteriaId, 'kriteria_id')],
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
            
            $kriteria = $this->route('kriteria');
            $kriteriaId = is_object($kriteria) ? $kriteria->kriteria_id : $kriteria;
            
            $service = app(KriteriaService::class);
            
            if ($service->willExceedMaxBobot($bobot, $kriteriaId)) {
                $remaining = $service->getRemainingBobot($kriteriaId);
                $validator->errors()->add('bobot', "Total bobot tidak boleh melebihi 100%. Sisa bobot yang tersedia adalah {$remaining}%.");
            }
        });
    }
}
