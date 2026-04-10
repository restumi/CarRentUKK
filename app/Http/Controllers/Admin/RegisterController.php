<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Classes\ApiResponse;
use Illuminate\Http\Request;
use App\Services\Admin\UserRegistationService;

class RegisterController extends Controller
{
    public function __construct(
        private UserRegistationService $userRegistationService
    ){}

    public function index(Request $request)
    {
        try{
            $data = $this->userRegistationService->index($request);

            return view('admin.users.verification', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $verify = $this->userRegistationService->show($id);

            return ApiResponse::sendResponse('verification details', $verify);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $user = $this->userRegistationService->processVerification($id, 'approve');

            return redirect()
                ->route('admin.verification.index')
                ->with('success', $user->name . ' created');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject($id, Request $request)
    {
        try {
            $verify = $this->userRegistationService->processVerification($id, 'reject', $request->input('reject_reason'));

            return redirect()->route('admin.verification.index')->with('success', $verify->name . ' rejected');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
