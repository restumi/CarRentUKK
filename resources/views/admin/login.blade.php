<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Kadar Rent Car</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center text-white">
    <div class="bg-gray-800 rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-car text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold">Kadar Rent Car</h1>
            <p class="mt-2">Admin Panel</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium mb-2">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-900"
                       placeholder="Masukkan email admin">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-2">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>

                <div class="relative">
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-gray-900"
                           placeholder="Masukkan password">

                    <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none cursor-pointer transition-colors"
                        aria-label="Toggle password visibility">
                        <i class="fa-solid fa-eye"></i>
                        <i class="fa-solid fa-eye-slash hidden"></i>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium">
                <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    this.querySelector('.fa-eye').classList.toggle('hidden');
                    this.querySelector('.fa-eye-slash').classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
