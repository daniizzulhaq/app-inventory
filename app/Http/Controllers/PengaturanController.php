<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    // ─── Pengaturan Perusahaan (admin only) ──────────────────────────
    public function index()
    {
        $setting = Setting::first() ?? new Setting();
        return view('pengaturan.index', compact('setting'));
    }

    public function updateSetting(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'logo'            => 'nullable|image|max:2048',
        ]);
        $setting = Setting::firstOrCreate([]);
        $data    = $request->only(['nama_perusahaan', 'alamat', 'telepon', 'email']);
        if ($request->hasFile('logo')) {
            if ($setting->logo) Storage::disk('public')->delete($setting->logo);
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }
        $setting->update($data);
        return back()->with('success', 'Pengaturan perusahaan berhasil disimpan.');
    }

    // ─── Profil Sendiri (semua role) ─────────────────────────────────
    public function profil()
    {
        return view('pengaturan.profil');
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto'  => 'nullable|image|max:2048',
        ]);
        $data = $request->only(['name', 'email']);
        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $data['foto'] = $request->file('foto')->store('foto', 'public');
        }
        $user->update($data);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|min:8|confirmed',
        ]);
        if (!Hash::check($request->password_lama, Auth::user()->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }
        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diubah.');
    }

    // ─── Manajemen User (admin only) ─────────────────────────────────
    public function userIndex()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('pengaturan.users.index', compact('users'));
    }

    public function userCreate()
    {
        return view('pengaturan.users.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'role'     => 'required|in:admin,karyawan',
            'password' => 'required|min:8|confirmed',
        ]);
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('pengaturan.users.index')
            ->with('success', "Akun '{$request->name}' berhasil dibuat.");
    }

    public function userEdit(User $user)
    {
        return view('pengaturan.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,karyawan',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Jangan bisa hapus satu-satunya admin
        if ($user->isAdmin() && $request->role === 'karyawan') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Tidak bisa mengubah role — minimal harus ada 1 admin.');
            }
        }

        $data = $request->only(['name', 'email', 'role']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('pengaturan.users.index')
            ->with('success', "Akun '{$user->name}' berhasil diperbarui.");
    }

    public function userDestroy(User $user)
    {
        // Tidak boleh hapus akun sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun Anda sendiri.');
        }
        // Minimal harus ada 1 admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus — minimal harus ada 1 admin.');
        }
        $user->delete();
        return back()->with('success', "Akun '{$user->name}' berhasil dihapus.");
    }
}