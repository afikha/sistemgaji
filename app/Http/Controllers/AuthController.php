<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('indexKaryawan')->with('success', 'Login berhasil');
        } else {
            return redirect()->route('indexLogin')->with('error', 'Username Password Salah');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('indexLogin')->with('success', 'Logout berhasil');
    }
}
