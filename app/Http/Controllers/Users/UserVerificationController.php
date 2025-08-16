<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\ApiResponse;
use App\Models\UserVerification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\UserVerificationRequest;

class UserVerificationController extends Controller
{
    public function store(UserVerificationRequest $request)
    {
        $data = $request->validated();

        $ktpPath = $request->file('ktp_image')->store('users/ktp', 'public');
        $facePath = $request->file('face_image')->store('users/face', 'public');

        $userVerification = UserVerification::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'nik' => $data['nik'],
            'ktp_image' => $ktpPath,
            'face_image' => $facePath,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'registration sent, waiting for admin approval',
            'data' => $userVerification
        ], 201);
    }
}
