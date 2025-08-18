<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Classes\ApiResponse;
use App\Models\UserVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class RegisterController extends Controller
{
    public function index()
    {
        $list = UserVerification::where('status', 'pending')->get();

        return ApiResponse::sendResponse('list pending verifications', $list);
    }

    public function show($id)
    {
        $verify = UserVerification::findOrFail($id);

        return ApiResponse::sendResponse('verification detail', $verify);
    }

    public function approve($id)
    {
        return ApiResponse::withTransaction( function () use ($id) {
            $verify = UserVerification::lockForUpdate()->findOrFail($id);

            if($verify->status !== 'pending'){
                return ApiResponse::sendErrorResponse('already processed', 'user already verify', 400);
            }

            if(User::where('email', $verify->email)->exists()){
                return ApiResponse::sendErrorResponse('email exists', 'email already used', 409);
            }

            $user = User::create([
                'name' => $verify->name,
                'email' => $verify->email,
                'password' => $verify->password
            ]);

            $verify->update([
                'status' => 'approved'
            ]);

            return ApiResponse::sendResponse('user created', [
                'user' => $user,
                'verification' => $verify
            ]);
        });
    }

    public function reject($id, Request $request)
    {
        return ApiResponse::withTransaction(function() use($id, $request) {
            $verify = UserVerification::lockForUpdate()->findOrFail($id);

            if($verify->status !== 'pending'){
                return ApiResponse::sendErrorResponse('already processed', 'user already verified', 400);
            }

            $reason = $request->input('reject_reason', 'Verifikasi ditolak, coba lagi dengan menggunggah data diri dengan benar.');

            $verify->update([
                'status' => 'rejected',
                'reject_reason' => $reason
            ]);

            return ApiResponse::sendResponse('user rejected', $verify);
        });
    }
}
