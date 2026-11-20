<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nama_periode' => ['required', 'string', 'max:120'],
            'tahun_masehi' => ['required', 'string', 'max:20'],
            'tahun_hijriah' => ['required', 'string', 'max:30'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'batas_akhir_fitrah' => ['nullable', 'date'],
            'beras_per_jiwa_kg' => ['required', 'numeric', 'min:0.1', 'max:50'],
            'nominal_uang' => ['required', 'numeric', 'min:1000'],
            'keterangan_tarif' => ['nullable', 'string', 'max:500'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_periode.required' => 'Nama periode wajib diisi.',
            'tahun_masehi.required' => 'Tahun Masehi wajib diisi.',
            'tahun_hijriah.required' => 'Tahun Hijriah wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'beras_per_jiwa_kg.required' => 'Takaran beras per jiwa wajib diisi.',
            'nominal_uang.required' => 'Nominal uang zakat fitrah per jiwa wajib diisi.',
        ];
    }
}
