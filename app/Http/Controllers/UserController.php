<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna yang berhak login ke sistem.
     */
    public function index(): View
    {
        $users = User::orderBy('id', 'asc')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Menambahkan pengguna baru yang diizinkan untuk login.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,staff'],
            'is_active' => ['required', 'boolean'],
        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil didaftarkan ke sistem!');
    }

    /**
     * Mengubah status izin akses login pengguna (Aktif / Nonaktif).
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $statusLabel = $user->is_active ? 'diaktifkan (diizinkan login)' : 'dinonaktifkan (akses login dicabut)';

        return redirect()->back()->with('success', "Akses untuk pengguna {$user->name} berhasil {$statusLabel}.");
    }

    /**
     * Menghapus pengguna dari sistem.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "Pengguna {$userName} berhasil dihapus dari sistem.");
    }
}
