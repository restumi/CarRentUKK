<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Car;
use App\Models\Driver;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Admin\AuthService;

class AuthController extends Controller
{
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function check()
    {
        if(Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login');
    }

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        return $this->authService->login($data);
    }

    public function dashboard()
    {
        $data = $this->authService->dashboard();

        return view('admin.dashboard', $data);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }
}
