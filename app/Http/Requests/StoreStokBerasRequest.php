<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStokBerasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'tanggal_masuk' => ['required', 'date'],
            'jumlah_kg' => ['required', 'numeric', 'min:0.1', 'max:100000'],
            'sumber' => ['required', Rule::in(['infak_beras', 'penyesuaian'])],
            'keterangan' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_masuk.required' => 'Tanggal pencatatan wajib diisi.',
            'jumlah_kg.required' => 'Jumlah beras (kg) wajib diisi.',
            'jumlah_kg.min' => 'Jumlah beras minimal 0,1 kg.',
            'sumber.required' => 'Sumber pemasukan beras wajib dipilih.',
            'keterangan.required' => 'Keterangan pencatatan wajib diisi.',
        ];
    }
}
