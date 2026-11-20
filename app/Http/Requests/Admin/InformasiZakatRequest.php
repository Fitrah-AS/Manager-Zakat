<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InformasiZakatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'kategori' => ['required', Rule::in(['fitrah', 'mal', 'lembaga', 'pengumuman'])],
            'konten' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul informasi wajib diisi.',
            'kategori.required' => 'Kategori informasi wajib dipilih.',
            'konten.required' => 'Isi konten informasi wajib diisi.',
        ];
    }
}
