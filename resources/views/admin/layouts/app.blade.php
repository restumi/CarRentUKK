<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Kadar Rent Car</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-900 text-white">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 lg:hidden" style="display: none;">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
        </div>

        <!-- Sidebar -->
        <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-64 admin-sidebar lg:translate-x-0 lg:static lg:inset-0" style="display: none;">
            <div class="flex items-center justify-between h-16 px-6 border-b border-blue-700">
                <div class="flex items-center">
                    <i class="fas fa-car text-2xl text-blue-300 mr-3"></i>
                    <h1 class="text-xl font-bold text-white">Kadar Rent Car</h1>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-blue-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="mt-6 px-3 custom-scrollbar">
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Users
                    </a>

                    <a href="{{ route('admin.verification.index') }}" class="nav-link {{ request()->routeIs('admin.verification.*') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i>
                        Verifikasi User
                    </a>

                    <a href="{{ route('admin.cars.index') }}" class="nav-link {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">
                        <i class="fas fa-car"></i>
                        Cars
                    </a>

                    <a href="{{ route('admin.drivers.index') }}" class="nav-link {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        Drivers
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        Transactions
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div class="lg:pl-0">
            <!-- Top header -->
            <div class="admin-header sticky top-0 z-10 shadow shadow-p">
                <div class="flex items-center bg-gray-800 h-26 px-4 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <div class="flex items-center justify-between w-full">
                        <div class="user-info">
                            <div class="text-right">
                                <a class="group flex items-center space-x-4" href="{{ route('admin.dashboard') }}">
                                    <img src="{{ asset('images/header.png') }}" alt="" class="h-[50px] w-auto">
                                    <p class="text-2xl font-bold group-hover:text-gray-300">Kadar Rent Car</p>
                                </a>
                            </div>
                        </div>

                        <div x-data="{ dropdownOpen: false }"
                            @mouseenter="dropdownOpen = true"
                            @mouseleave="dropdownOpen = false"
                            class="relative flex items-center">

                            <div class="user-avatar cursor-pointer select-none flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-bold text-lg hover:bg-blue-500 transition-colors"
                                @click="dropdownOpen = !dropdownOpen">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>

                            <div x-show="dropdownOpen"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                class="absolute right-0 top-12 w-48 bg-gray-800 rounded-lg shadow-xl border border-gray-700 py-2 z-50"
                                style="display: none;"
                                @click.outside="dropdownOpen = false">

                                <div class="px-4 py-2 border-b border-gray-700">
                                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? 'Admin' }}</p>
                                </div>

                                <form method="POST" action="{{ route('admin.logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700 hover:text-red-300 transition-colors flex items-center gap-2">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
