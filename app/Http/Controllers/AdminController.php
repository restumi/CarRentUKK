<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Car;
use App\Models\Driver;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if ($user->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki akses admin.');
        }

        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Admin Panel!');
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalCars = Car::count();
        $totalDrivers = Driver::count();
        $totalTransactions = Transaction::count();
        
        // Get pending verifications count
        $pendingVerifications = \App\Models\UserVerification::where('status', 'pending')->count();
        
        $recentTransactions = Transaction::with(['user', 'car', 'driver'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('totalUsers', 'totalCars', 'totalDrivers', 'totalTransactions', 'pendingVerifications', 'recentTransactions'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function cars(Request $request)
    {
        $query = Car::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by price range
        if ($request->filled('price_range')) {
            $range = $request->price_range;
            if ($range === '0-500000') {
                $query->where('price_per_day', '<=', 500000);
            } elseif ($range === '500000-1000000') {
                $query->where('price_per_day', '>', 500000)->where('price_per_day', '<=', 1000000);
            } elseif ($range === '1000000+') {
                $query->where('price_per_day', '>', 1000000);
            }
        }

        $cars = $query->latest()->paginate(12);
        $brands = Car::distinct()->pluck('brand')->sort();

        return view('admin.cars.index', compact('cars', 'brands'));
    }

    public function drivers(Request $request)
    {
        $query = Driver::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by age range
        if ($request->filled('age_range')) {
            $range = $request->age_range;
            if ($range === '18-25') {
                $query->where('age', '>=', 18)->where('age', '<=', 25);
            } elseif ($range === '26-35') {
                $query->where('age', '>=', 26)->where('age', '<=', 35);
            } elseif ($range === '36-50') {
                $query->where('age', '>=', 36)->where('age', '<=', 50);
            } elseif ($range === '50+') {
                $query->where('age', '>', 50);
            }
        }

        $drivers = $query->latest()->paginate(15);

        return view('admin.drivers.index', compact('drivers'));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'car', 'driver']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('car', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_transaction', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $transactions = $query->latest()->paginate(15);

        // Statistics
        $pendingCount = Transaction::where('status_transaction', 'requested')->count();
        $approvedCount = Transaction::where('status_transaction', 'accepted')->count();
        $rejectedCount = Transaction::where('status_transaction', 'rejected')->count();
        $totalRevenue = Transaction::where('payment_status', 'paid')->sum('total_price');

        return view('admin.transactions.index', compact('transactions', 'pendingCount', 'approvedCount', 'rejectedCount', 'totalRevenue'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }
} 