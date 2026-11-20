<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenyaluranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'tanggal_penyaluran' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'langsung_selesai' => ['nullable', 'boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.mustahik_id' => ['required', 'exists:mustahik,id'],
            'items.*.asnaf_id' => ['required', 'exists:asnaf,id'],
            'items.*.jenis_zakat_id' => ['required', 'exists:jenis_zakat,id'],
            'items.*.bentuk_salur' => ['required', Rule::in(['uang', 'beras', 'keduanya'])],
            'items.*.nominal_uang' => ['nullable', 'numeric', 'min:0'],
            'items.*.jumlah_beras_kg' => ['nullable', 'numeric', 'min:0'],
            'items.*.catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_penyaluran.required' => 'Tanggal penyaluran wajib diisi.',
            'items.required' => 'Daftar penerima mustahik minimal 1 baris.',
            'items.*.mustahik_id.required' => 'Mustahik wajib dipilih.',
            'items.*.asnaf_id.required' => 'Kategori asnaf wajib dipilih.',
            'items.*.jenis_zakat_id.required' => 'Jenis zakat wajib dipilih.',
            'items.*.bentuk_salur.required' => 'Bentuk penyaluran wajib dipilih.',
        ];
    }
}
