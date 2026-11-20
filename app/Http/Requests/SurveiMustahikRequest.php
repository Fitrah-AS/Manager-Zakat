<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SurveiMustahikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'tanggal_survei' => ['required', 'date'],
            'kondisi_ekonomi' => ['required', 'string', 'max:255'],
            'jumlah_tanggungan' => ['required', 'integer', 'min:0', 'max:50'],
            'hasil_survei' => ['required', Rule::in(['layak', 'tidak_layak', 'perlu_tinjauan'])],
            'catatan_petugas' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_survei.required' => 'Tanggal survei wajib diisi.',
            'kondisi_ekonomi.required' => 'Kondisi ekonomi wajib diisi.',
            'jumlah_tanggungan.required' => 'Jumlah tanggungan wajib diisi.',
            'hasil_survei.required' => 'Hasil survei wajib dipilih.',
        ];
    }
}
