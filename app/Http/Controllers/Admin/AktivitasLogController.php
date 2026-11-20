<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AktivitasLogController extends Controller
{
    public function index(Request $request): View
    {
        $modul = trim((string) $request->input('modul', ''));
        $userId = $request->input('user_id', '');
        $search = trim((string) $request->input('q', ''));

        $logs = AktivitasLog::with('user')
            ->when($modul !== '', fn ($q) => $q->where('modul', $modul))
            ->when($userId !== '', fn ($q) => $q->where('user_id', $userId))
            ->when($search !== '', fn ($q) => $q->where('deskripsi', 'like', "%{$search}%"))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $moduls = AktivitasLog::select('modul')->distinct()->orderBy('modul')->pluck('modul');
        $users = User::orderBy('name')->get(['id', 'name', 'username']);

        return view('admin.aktivitas-log.index', compact('logs', 'moduls', 'users', 'modul', 'userId', 'search'));
    }
}
