<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        $this->auditLogService->record(
            aksi: 'login',
            modul: 'autentikasi',
            deskripsi: "Pengguna {$user->name} ({$user->username}) berhasil masuk ke aplikasi.",
            model: $user,
            userId: $user->id
        );

        return redirect()->intended(route('dashboard'))
            ->with('success', "Selamat datang, {$user->name}.");
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $this->auditLogService->record(
                aksi: 'logout',
                modul: 'autentikasi',
                deskripsi: "Pengguna {$user->name} ({$user->username}) keluar dari aplikasi.",
                model: $user,
                userId: $user->id
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda telah berhasil keluar dari aplikasi.');
    }
}
