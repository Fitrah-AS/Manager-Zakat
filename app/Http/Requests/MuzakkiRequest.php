<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MuzakkiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $muzakkiId = $this->route('muzakki')?->id;

        return [
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nik' => [
                'nullable',
                'digits:16',
                Rule::unique('muzakki', 'nik')->ignore($muzakkiId),
            ],
            'alamat' => ['required', 'string', 'max:1000'],
            'no_hp' => ['required', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap muzakki wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'nik.unique' => 'NIK tersebut sudah terdaftar pada data muzakki lain.',
            'alamat.required' => 'Alamat muzakki wajib diisi.',
            'no_hp.required' => 'Nomor HP muzakki wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ];
    }
}
