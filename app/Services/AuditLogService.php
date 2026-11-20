<?php

namespace App\Services;

use App\Models\AktivitasLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Kolom sensitif yang tidak boleh dicatat ke audit log.
     *
     * @var array<int, string>
     */
    private const SENSITIVE_KEYS = [
        'password',
        'password_confirmation',
        'remember_token',
    ];

    public function record(
        string $aksi,
        string $modul,
        string $deskripsi,
        ?Model $model = null,
        ?array $dataLama = null,
        ?array $dataBaru = null,
        ?int $userId = null
    ): AktivitasLog {
        $request = request();

        return AktivitasLog::create([
            'user_id' => $userId ?? Auth::id(),
            'aksi' => $aksi,
            'modul' => $modul,
            'model_type' => $model ? $model::class : null,
            'model_id' => $model?->getKey(),
            'deskripsi' => $deskripsi,
            'data_lama' => $dataLama ? Arr::except($dataLama, self::SENSITIVE_KEYS) : null,
            'data_baru' => $dataBaru ? Arr::except($dataBaru, self::SENSITIVE_KEYS) : null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
