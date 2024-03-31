<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Services\Verification\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    
    function verification(Request $request)
    {
        if (!$request->token) abort(Response::HTTP_NOT_FOUND);

        $service = VerificationService::email($request);

        $data['status'] = $service->status;

        return view('pages.account.info-verifikasi', $data);
    }
    
    function reset(Request $request)
    {
        if (!$request->token) abort(Response::HTTP_NOT_FOUND);

        $data['token'] = $request->token;

        return view('pages.account.reset', $data);
    }
    
    function resetPassword(Request $request)
    {
        $service = AuthService::doForgotPassword($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
    
    function resetPasswordCancel(Request $request)
    {
        $service = AuthService::cancelForgotPassword($request);

        $data['status'] = $service->status;

        return view('pages.account.info-reset', $data);
    }
}
