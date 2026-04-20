<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Classes\ApiResponse;
use App\Models\User;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(int $id, UpdateProfileRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::where('id', $id)->with('verification')->firstOrFail();
            $userVerification = $user->verification;

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $userVerification->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
            ]);

            if ($request->hasFile('face_image')) {
                if ($userVerification->face_image) {
                    Storage::disk('public')->delete($userVerification->face_image);
                }
                $userVerification['face_image'] = $request->file('face_image')->store('users/face', 'public');
                $userVerification->save();
            }

            $data = [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'verification' => [
                    'name' => $userVerification->name,
                    'email' => $userVerification->email,
                    'phone_number' => $userVerification->phone_number,
                    'address' => $userVerification->address,
                    'face_image' => $userVerification->face_image,
                ]
            ];

            return ApiResponse::sendResponse('Profile updated successfully', $data);
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage(), ['user_id' => $id]);
            return ApiResponse::sendErrorResponse('Failed to update profile', $e->getMessage());
        }
    }
}
