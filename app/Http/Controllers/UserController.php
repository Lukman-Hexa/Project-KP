<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        } else {
            // return redirect()->back()->withErrors(['name' => 'Invalid credentials. ']);
            return back()->withErrors([
            'password' => 'Username atau password salah. Silakan coba lagi.', ])->onlyInput('username');
        }
    }

    public function showHome()
    {
        return view('dashboard');
    }

    public function showKetegori()
    {
        return view('kategori_laporan');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }

}
