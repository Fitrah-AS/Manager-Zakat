<?php

namespace App\Http\Requests;

use App\Enums\StatusKelayakanEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MustahikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nik' => ['nullable', 'digits:16'],
            'alamat' => ['required', 'string', 'max:1000'],
            'no_hp' => ['required', 'string', 'max:25'],
            'asnaf_id' => ['required', 'exists:asnaf,id'],
            'status_kelayakan' => ['required', Rule::in(array_column(StatusKelayakanEnum::cases(), 'value'))],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap mustahik wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'alamat.required' => 'Alamat mustahik wajib diisi.',
            'no_hp.required' => 'Nomor HP mustahik wajib diisi.',
            'asnaf_id.required' => 'Kategori asnaf wajib dipilih.',
            'asnaf_id.exists' => 'Kategori asnaf tidak valid.',
            'status_kelayakan.required' => 'Status kelayakan wajib dipilih.',
            'latitude.between' => 'Latitude harus berada di antara -90 sampai 90.',
            'longitude.between' => 'Longitude harus berada di antara -180 sampai 180.',
        ];
    }
}
