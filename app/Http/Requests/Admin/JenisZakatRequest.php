<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisZakatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $jenisZakatId = $this->route('jenis_zakat')?->id;

        return [
            'kode' => ['required', 'string', 'max:40', Rule::unique('jenis_zakat', 'kode')->ignore($jenisZakatId)],
            'nama_jenis' => ['required', 'string', 'max:120'],
            'kategori' => ['required', Rule::in(['fitrah', 'mal'])],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis zakat wajib diisi.',
            'kode.unique' => 'Kode jenis zakat sudah digunakan.',
            'nama_jenis.required' => 'Nama jenis zakat wajib diisi.',
            'kategori.required' => 'Kategori zakat wajib dipilih.',
        ];
    }
}
