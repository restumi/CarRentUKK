<?php

namespace App\Services\Admin;

use App\Http\Repositories\User\UserRepositoryInterface;
use App\Http\Repositories\UserVerification\UserVerificationRepositoryInterface;
use Illuminate\Http\Request;
use App\Classes\ApiResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\approvedNotify;
use App\Mail\rejectedNotify;

class UserRegistationService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserVerificationRepositoryInterface $userVerificationRepository
    ){}

    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = $this->userVerificationRepository->query();

        if($status !== 'all'){
            $query->where('status', $status);
        }

        $verifications =$query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'pending' => $this->userVerificationRepository->status('pending')->count(),
            'approved' => $this->userVerificationRepository->status('approved')->count(),
            'rejected' => $this->userVerificationRepository->status('rejected')->count(),
        ];

        return [
            'status' => $status,
            'verifications' => $verifications,
            'stats' => $stats
        ];
    }

    public function show($id)
    {
        return $this->userVerificationRepository->find($id);
    }

    public function processVerification($id, $action, $rejectReason = null)
    {
        return ApiResponse::withTransaction(function () use ($id, $action, $rejectReason) {
            $query = $this->userVerificationRepository->query();
            $verify = $query->lockForUpdate()->findOrFail($id);

            if ($verify->status !== 'pending') {
                throw new \Exception('User already verified', 400);
            }

            if ($action === 'approve') {
                if ($this->userRepository->checkExists($verify->email)) {
                    throw new \Exception('Email already used', 409);
                }

                $userData = [
                    'name'     => $verify->name,
                    'email'    => $verify->email,
                    'password' => $verify->password,
                ];
                $user = $this->userRepository->create($userData);

                $verify->update(['status' => 'approved']);

                Mail::to($verify->email)->send(new approvedNotify($verify));

                return $user;

            } elseif ($action === 'reject') {
                $reason = $rejectReason ?? 'Verifikasi ditolak, coba lagi dengan data yang benar.';

                $verify->update([
                    'status'        => 'rejected',
                    'reject_reason' => $reason
                ]);

                Mail::to($verify->email)->send(new rejectedNotify($verify));

                return $verify;
            } else {
                throw new \Exception('Invalid action', 400);
            }
        }, false);
    }
}
