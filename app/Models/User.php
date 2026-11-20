<?php

namespace App\Models;

use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRoleEnum::class,
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::ADMIN;
    }

    public function isPetugas(): bool
    {
        return $this->role === UserRoleEnum::PETUGAS;
    }

    public function pembayaranDiinput(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'petugas_input_id');
    }

    public function pembayaranDiverifikasi(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'verified_by');
    }

    public function surveiMustahik(): HasMany
    {
        return $this->hasMany(SurveiMustahik::class, 'petugas_id');
    }

    public function penyaluran(): HasMany
    {
        return $this->hasMany(Penyaluran::class, 'petugas_id');
    }

    public function aktivitasLogs(): HasMany
    {
        return $this->hasMany(AktivitasLog::class, 'user_id');
    }
}
