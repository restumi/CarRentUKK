<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        try{
            $ktpPath = $request->file('ktp_image')->store('users/ktp', 'public');
            $facePath = $request->file('face_image')->store('users/face', 'public');

            $verifications = UserVerification::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'user',
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'nik' => $data['nik'],
                'ktp_image' => $ktpPath,
                'face_image' => $facePath,
                'status' => 'pending'
            ]);

            return ApiResponse::sendResponse('waiting for admin approval', $verifications);

        } catch(\Throwable $e){
            $error = $e->getMessage();

            if(!empty($ktpPath)) Storage::disk('public')->delete($ktpPath);
            if(!empty($facePath)) Storage::disk('public')->delete($facePath);

            return ApiResponse::sendErrorResponse('failed to send registration, please try again leter.', $error);
        }
    }
}
