<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Admin\UserService;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {}

    public function index(Request $request)
    {
        $users = $this->userService->all($request);
        return view('admin.users.index', compact('users'));
    }

    public function toAdmin(User $user)
    {
        try {
            $this->userService->updateRole('admin', $user->id);
            return redirect()->back()->with('success', 'Role berhasil diperbarui');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Role gagal diperbarui');
        }
    }

    public function toUser(User $user)
    {
        try {
            $this->userService->updateRole('user', $user->id);
            return redirect()->back()->with('success', 'Role berhasil diperbarui');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Role gagal diperbarui');
        }
    }
}
