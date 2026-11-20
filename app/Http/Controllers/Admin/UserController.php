<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $role = $request->input('role', '');

        $users = User::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role !== '', fn ($q) => $q->where('role', $role))
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active', true),
            'password' => Hash::make($data['password']),
        ]);

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'users',
            deskripsi: "Menambahkan akun pengguna {$user->name} ({$user->username}) dengan role {$user->role->value}.",
            model: $user,
            dataBaru: $user->toArray()
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil ditambahkan.");
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $dataLama = $user->toArray();

        $isActive = $request->boolean('is_active');
        if ($user->id === auth()->id() && ! $isActive) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
        }

        $payload = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'role' => $data['role'],
            'is_active' => $isActive,
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'users',
            deskripsi: "Memperbarui akun pengguna {$user->name} ({$user->username}).",
            model: $user,
            dataLama: $dataLama,
            dataBaru: $user->fresh()->toArray()
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->pembayaranDiinput()->exists() || $user->pembayaranDiverifikasi()->exists() || $user->penyaluran()->exists() || $user->surveiMustahik()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun tidak dapat dihapus karena memiliki riwayat transaksi atau survei. Nonaktifkan akun sebagai gantinya.');
        }

        $dataLama = $user->toArray();
        $nama = $user->name;
        $user->delete();

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'users',
            deskripsi: "Menghapus akun pengguna {$nama}.",
            dataLama: $dataLama
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$nama} berhasil dihapus.");
    }
}
