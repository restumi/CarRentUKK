<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Classes\ApiResponse;
use App\Models\UserVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $list = UserVerification::where('status', 'pending')->latest()->get();
        return ApiResponse::sendResponse('pending verifications', $list);
    }

    public function approve($id)
    {
        return DB::transaction(function() use ($id) {
            $v = UserVerification::findOrFail($id);

            if($v->status !== 'pending'){
                return ApiResponse::sendErrorResponse('Already processed', '');
            }

            if(User::where('email', $v->email->exists()) || User::where('nik', $v->nik->exists())){
                $v->update([
                    'status' => 'rejected',
                    'reject_reason' => 'Email / NIK Already used'
                ]);
                return ApiResponse::sendErrorResponse('Email / NIK Already used', '');
            }

            $user = User::create([
                'name' => $v->name,
                'email' => $v->email,
                'password' => $v->password,
                'role' => 'user',
                'phone_number' => $v->phone_number,
                'address' => $v->address,
                'nik' => $v->nik,
                'ktp_face' => $v->ktp_image,
                'face_image' => $v->face_image
            ]);

            $v->update(['status' => 'approved']);
            return ApiResponse::sendResponse('User approved & created', $user);
        });
    }

    public function reject($id, Request $request)
    {
        $v = UserVerification::findOrFail($id);
        $v->update([
            'status' => 'rejected',
            'reject_reason' => $request->input('reason', 'rejected by admin')
        ]);

        return ApiResponse::sendResponse('Regist rejected', $v);
    }
}
