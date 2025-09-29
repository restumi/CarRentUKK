<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Classes\ApiResponse;
use App\Models\UserVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\approvedNotify;
use App\Mail\rejectedNotify;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = UserVerification::query();

        if($status !== 'all'){
            $query->where('status', $status);
        }

        $verifications =$query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'pending' => UserVerification::where('status', 'pending')->count(),
            'approved' => UserVerification::where('status', 'approved')->count(),
            'rejected' => UserVerification::where('status', 'rejected')->count(),
        ];

        return view('admin.users.verification', compact('status', 'verifications', 'stats'));
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

            Mail::to($verify->email)->send(new approvedNotify($verify));

            return redirect()
                ->route('admin.verification.index')
                ->with('success', $user['name'] . ' created');
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

            Mail::to($verify->email)->send(new rejectedNotify($verify));

            return redirect()
                ->route('admin.verification.index')
                ->with('error', $verify->name . ' rejected');
        });
    }
}
