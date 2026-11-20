<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NisabRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'periode_id' => ['nullable', 'exists:periode,id'],
            'jenis_harta' => ['required', Rule::in(['emas_perak', 'penghasilan', 'perdagangan', 'tabungan_investasi', 'pertanian'])],
            'nama_standar' => ['required', 'string', 'max:150'],
            'basis_nisab' => ['required', 'string', 'max:150'],
            'harga_emas_per_gram' => ['nullable', 'numeric', 'min:0'],
            'gram_emas_nisab' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'nisab_pertanian_kg' => ['nullable', 'numeric', 'min:0'],
            'nilai_nisab_uang' => ['nullable', 'numeric', 'min:0'],
            'kadar_zakat_persen' => ['required', 'numeric', 'min:0.1', 'max:100'],
            'ketentuan_haul' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_harta.required' => 'Jenis harta wajib dipilih.',
            'nama_standar.required' => 'Nama standar nisab wajib diisi.',
            'basis_nisab.required' => 'Basis nisab wajib diisi.',
            'kadar_zakat_persen.required' => 'Persentase kadar zakat wajib diisi.',
            'ketentuan_haul.required' => 'Ketentuan haul wajib diisi.',
        ];
    }
}
