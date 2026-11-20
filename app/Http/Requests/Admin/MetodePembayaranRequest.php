<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MetodePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $metodeId = $this->route('metode_pembayaran')?->id;

        return [
            'kode' => ['required', 'string', 'max:40', Rule::unique('metode_pembayaran', 'kode')->ignore($metodeId)],
            'nama_metode' => ['required', 'string', 'max:100'],
            'tipe' => ['required', Rule::in(['tunai', 'transfer_bank', 'qris', 'lainnya'])],
            'nama_bank' => ['nullable', 'string', 'max:100'],
            'no_rekening' => ['nullable', 'string', 'max:60'],
            'atas_nama' => ['nullable', 'string', 'max:120'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode metode pembayaran wajib diisi.',
            'kode.unique' => 'Kode metode pembayaran sudah digunakan.',
            'nama_metode.required' => 'Nama metode pembayaran wajib diisi.',
            'tipe.required' => 'Tipe metode pembayaran wajib dipilih.',
        ];
    }
}
