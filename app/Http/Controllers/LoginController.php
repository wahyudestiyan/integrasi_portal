<?php
// app/Http/Controllers/LoginController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Menampilkan form login
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek kredensial pengguna
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Login berhasil, redirect ke halaman index
            return redirect()->route('api.index');
        }

        // Login gagal, kembali ke form login dengan error
        return back()->withErrors(['username' => 'The provided credentials are incorrect.']);
    }

    public function logout()
    {
        Auth::logout(); // Logout pengguna
        return redirect()->route('login'); // Redirect ke halaman login
    }
}
