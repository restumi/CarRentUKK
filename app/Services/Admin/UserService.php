<?php

namespace App\Services\Admin;

use App\Http\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {}

    public function all(Request $request)
    {
        $query = $this->userRepository->withVerificationQuery();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('verification', function($subQ) use ($search) {
                      $subQ->where('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10);

        return $users;
    }
}
