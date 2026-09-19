<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Tampilkan form ubah password admin.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.password.edit', compact('user'));
    }

    /**
     * Proses update password admin.
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:6'],
        ], [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini yang Anda masukkan salah.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.confirmed'                => 'Konfirmasi password baru tidak cocok.',
            'password.min'                      => 'Password baru minimal berisi :min karakter.',
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.password.edit')
                         ->with('success', 'Password admin berhasil diperbarui!');
    }
}
