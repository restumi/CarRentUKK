<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Admin\UserService;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {}

    public function index(Request $request)
    {
        $users = $this->userService->all($request);
        return view('admin.users.index', compact('users'));
    }
}
