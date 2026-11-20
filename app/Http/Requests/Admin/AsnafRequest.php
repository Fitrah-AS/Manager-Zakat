<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AsnafRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $asnafId = $this->route('asnaf')?->id;

        return [
            'kode' => ['required', 'string', 'max:30', Rule::unique('asnaf', 'kode')->ignore($asnafId)],
            'nama_asnaf' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'urutan' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode asnaf wajib diisi.',
            'kode.unique' => 'Kode asnaf sudah terdaftar.',
            'nama_asnaf.required' => 'Nama asnaf wajib diisi.',
            'urutan.required' => 'Urutan wajib diisi.',
        ];
    }
}
