<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::sendResponseWithToken('user created', $token, $user);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if(!$user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['email atau password salah'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::sendResponseWithToken('login success', $token, $user);
    }

    public function logout(Request $request)
    {
        try{
            $request->user()->tokens()->delete();

            return ApiResponse::sendResponse('', 'logOut success');

        } catch (\Throwable $e) {
            $response = Log::error($e->getMessage());

            return ApiResponse::sendErrorResponse('failed to logOut', $response);
        }
    }
}
