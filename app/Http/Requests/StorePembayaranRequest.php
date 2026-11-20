<?php

namespace App\Http\Requests;

use App\Models\MetodePembayaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $modeMuzakki = $this->input('mode_muzakki', 'pilih');
        $metodeId = $this->input('metode_pembayaran_id');
        $metode = $metodeId ? MetodePembayaran::find($metodeId) : null;
        $wajibBukti = $metode && ! $metode->isTunai();

        return [
            'mode_muzakki' => ['required', Rule::in(['pilih', 'baru'])],
            'muzakki_id' => [$modeMuzakki === 'pilih' ? 'required' : 'nullable', 'exists:muzakki,id'],
            'muzakki_baru_nama' => [$modeMuzakki === 'baru' ? 'required' : 'nullable', 'string', 'max:150'],
            'muzakki_baru_nik' => ['nullable', 'digits:16', 'unique:muzakki,nik'],
            'muzakki_baru_no_hp' => [$modeMuzakki === 'baru' ? 'required' : 'nullable', 'string', 'max:25'],
            'muzakki_baru_email' => ['nullable', 'email', 'max:150'],
            'muzakki_baru_alamat' => [$modeMuzakki === 'baru' ? 'required' : 'nullable', 'string', 'max:1000'],

            'jenis_zakat_id' => ['required', 'exists:jenis_zakat,id'],
            'metode_pembayaran_id' => ['required', 'exists:metode_pembayaran,id'],
            'tanggal_bayar' => ['required', 'date'],
            'bentuk_bayar' => ['nullable', Rule::in(['uang', 'beras'])],
            'nominal_uang' => ['nullable', 'numeric', 'min:0'],
            'jumlah_beras_kg' => ['nullable', 'numeric', 'min:0'],

            'anggota_jiwa' => ['nullable', 'array'],
            'anggota_jiwa.*.nama_anggota' => ['nullable', 'string', 'max:150'],
            'anggota_jiwa.*.hubungan_keluarga' => ['nullable', 'string', 'max:60'],

            'gunakan_kalkulator_mal' => ['nullable', 'boolean'],
            'jenis_harta' => ['nullable', Rule::in(['emas_perak', 'penghasilan', 'perdagangan', 'tabungan_investasi', 'pertanian'])],
            'nilai_harta_kotor' => ['nullable', 'numeric', 'min:0'],
            'hutang_jatuh_tempo' => ['nullable', 'numeric', 'min:0'],
            'harga_emas_per_gram' => ['nullable', 'numeric', 'min:0'],
            'sudah_haul' => ['nullable', 'boolean'],
            'metode_penghasilan' => ['nullable', Rule::in(['bruto', 'neto'])],
            'periode_penghasilan' => ['nullable', Rule::in(['bulanan', 'tahunan'])],
            'jenis_panen' => ['nullable', Rule::in(['gabah', 'beras'])],
            'jenis_irigasi' => ['nullable', Rule::in(['berbiaya', 'alami'])],
            'berat_panen_kg' => ['nullable', 'numeric', 'min:0'],
            'harga_per_kg' => ['nullable', 'numeric', 'min:0'],

            'bukti_transfer' => [
                $wajibBukti ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'muzakki_id.required' => 'Muzakki wajib dipilih.',
            'muzakki_baru_nama.required' => 'Nama lengkap muzakki baru wajib diisi.',
            'muzakki_baru_nik.digits' => 'NIK muzakki harus 16 digit angka.',
            'muzakki_baru_nik.unique' => 'NIK muzakki baru sudah terdaftar.',
            'muzakki_baru_no_hp.required' => 'Nomor HP muzakki baru wajib diisi.',
            'muzakki_baru_alamat.required' => 'Alamat muzakki baru wajib diisi.',
            'jenis_zakat_id.required' => 'Jenis zakat wajib dipilih.',
            'metode_pembayaran_id.required' => 'Metode pembayaran wajib dipilih.',
            'tanggal_bayar.required' => 'Tanggal pembayaran wajib diisi.',
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah untuk metode pembayaran non-tunai.',
            'bukti_transfer.mimes' => 'Bukti transfer harus berformat JPG, JPEG, PNG, atau PDF.',
            'bukti_transfer.max' => 'Ukuran file bukti transfer maksimal 2 MB.',
        ];
    }
}
