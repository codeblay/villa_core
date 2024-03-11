<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function login(Request $request)
    {
        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password,
        ];

        $is_valid = auth()->attempt($credentials);
        if ($is_valid) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        
        return back()->with([
            'type'      => 'danger',
            'title'     => 'Login Gagal',
            'message'   => 'Email atau password salah'
        ]);
    }
    
    function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('admin.login');
    }
}
