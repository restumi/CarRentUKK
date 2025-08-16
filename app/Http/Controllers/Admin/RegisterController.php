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

        return ApiResponse::sendResponse('verifications request list', $list);
    }

    public function approve($id)
    {
        try {
            $verify = UserVerification::findOrFail($id);

            if($verify->status !== 'pending'){
                return ApiResponse::no('user validated');
            }

            $user = User::create([
                'name'     => $verify->name,
                'email'    => $verify->email,
                'password' => $verify->password,
                'phone_number' => $verify->phone_number,
                'address'      => $verify->address,
                'nik'          => $verify->nik,
                'ktp_image'    => $verify->ktp_image,
                'face_image'   => $verify->face_image,
            ]);

            $verify->update([
                'status' => 'approved'
            ]);

            return ApiResponse::sendResponse('user approved', $user);
        } catch (\Throwable $e){
            return ApiResponse::sendErrorResponse('something went wrong', $e->getMessage());
        }
    }

    public function reject($id, Request $request)
    {
        try {
            $verify = UserVerification::findOrFail($id);

            if($verify->status !== 'pending'){
                return ApiResponse::no('user validated');
            }

            $verify->update([
                'status' => 'rejected',
                'reject_reason' => 'upload data dengan baik dan benar'
            ]);

            return ApiResponse::sendResponse('user rejected', $verify);
        } catch(\Throwable $e){
            return ApiResponse::sendErrorResponse('something went wrong', $e->getMessage());
        }
    }
}
