@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="fade-in-up">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Admin Panel Kadar Rent Car</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="stat-card blue hover-lift" onclick="window.location.href='{{ route('admin.users.index') }}'">
            <div class="flex items-center">
                <div class="icon-container">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ml-4">
                    <p class="stat-label">Total Users</p>
                    <p class="stat-number">{{ $totalUsers }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-blue-600 hover:text-blue-800" style="cursor: pointer">Lihat Detail →</span>
            </div>
        </div>

        <!-- Total Cars -->
        <div class="stat-card green hover-lift" onclick="window.location.href='{{ route('admin.cars.index') }}'">
            <div class="flex items-center">
                <div class="icon-container">
                    <i class="fas fa-car"></i>
                </div>
                <div class="ml-4">
                    <p class="stat-label">Total Cars</p>
                    <p class="stat-number">{{ $totalCars }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 hover:text-green-800" style="cursor: pointer">Lihat Detail →</span>
            </div>
        </div>

        <!-- Total Drivers -->
        <div class="stat-card yellow hover-lift" onclick="window.location.href='{{ route('admin.drivers.index') }}'">
            <div class="flex items-center">
                <div class="icon-container">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="ml-4">
                    <p class="stat-label">Total Drivers</p>
                    <p class="stat-number">{{ $totalDrivers }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-yellow-600 hover:text-yellow-800" style="cursor: pointer">Lihat Detail →</span>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="stat-card purple hover-lift" onclick="window.location.href='{{ route('admin.transactions.index') }}'">
            <div class="flex items-center">
                <div class="icon-container">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="ml-4">
                    <p class="stat-label">Total Transactions</p>
                    <p class="stat-number">{{ $totalTransactions }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-purple-600 hover:text-purple-800" style="cursor: pointer">Lihat Detail →</span>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="stat-card orange hover-lift" onclick="window.location.href='{{ route('admin.verification.index') }}'">
            <div class="flex items-center">
                <div class="icon-container">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="ml-4">
                    <p class="stat-label">Pending Verifications</p>
                    <p class="stat-number">{{ $pendingVerifications ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-orange-600 hover:text-orange-800" style="cursor: pointer">Lihat Detail →</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Aksi Cepat</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.cars.create') }}"
                   class="flex flex-col items-center p-6 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors hover-lift">
                    <i class="fas fa-plus-circle text-blue-600 text-3xl mb-3"></i>
                    <span class="text-sm font-medium text-blue-900">Tambah Mobil</span>
                    <span class="text-xs text-blue-600 mt-1">Tambah mobil baru</span>
                </a>

                <a href="{{ route('admin.drivers.create') }}"
                   class="flex flex-col items-center p-6 bg-green-50 rounded-lg hover:bg-green-100 transition-colors hover-lift">
                    <i class="fas fa-user-plus text-green-600 text-3xl mb-3"></i>
                    <span class="text-sm font-medium text-green-900">Tambah Driver</span>
                    <span class="text-xs text-green-600 mt-1">Tambah driver baru</span>
                </a>

                <a href="{{ route('admin.transactions.index') }}"
                   class="flex flex-col items-center p-6 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors hover-lift">
                    <i class="fas fa-list-alt text-purple-600 text-3xl mb-3"></i>
                    <span class="text-sm font-medium text-purple-900">Lihat Transaksi</span>
                    <span class="text-xs text-purple-600 mt-1">Kelola transaksi</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex flex-col items-center p-6 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors hover-lift">
                    <i class="fas fa-users-cog text-yellow-600 text-3xl mb-3"></i>
                    <span class="text-sm font-medium text-yellow-900">Kelola Users</span>
                    <span class="text-xs text-yellow-600 mt-1">Kelola pengguna</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Transaksi Terbaru</h2>
            </div>
            <div class="p-6">
                @if($recentTransactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentTransactions->take(5) as $transaction)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-receipt text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->car->brand }} {{ $transaction->car->model }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                                    <span class="status-badge {{ $transaction->status === 'completed' ? 'completed' : ($transaction->status === 'pending' ? 'pending' : 'active') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
