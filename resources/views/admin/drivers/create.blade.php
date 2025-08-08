@extends('admin.layouts.app')

@section('title', 'Tambah Driver')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Driver Baru</h1>
            <p class="text-gray-600">Tambahkan driver baru ke dalam sistem</p>
        </div>
        <a href="{{ route('admin.drivers.index') }}" 
           class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.drivers.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: 081234567890">
                    @error('phone_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: driver@email.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License Number -->
                <div>
                    <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor SIM</label>
                    <input type="text" id="license_number" name="license_number" value="{{ old('license_number') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: 1234567890123456">
                    @error('license_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License Type -->
                <div>
                    <label for="license_type" class="block text-sm font-medium text-gray-700 mb-2">Jenis SIM</label>
                    <select id="license_type" name="license_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Jenis SIM</option>
                        <option value="A" {{ old('license_type') === 'A' ? 'selected' : '' }}>SIM A (Motor)</option>
                        <option value="B1" {{ old('license_type') === 'B1' ? 'selected' : '' }}>SIM B1 (Mobil Penumpang)</option>
                        <option value="B2" {{ old('license_type') === 'B2' ? 'selected' : '' }}>SIM B2 (Mobil Barang)</option>
                        <option value="C" {{ old('license_type') === 'C' ? 'selected' : '' }}>SIM C (Truck)</option>
                        <option value="D" {{ old('license_type') === 'D' ? 'selected' : '' }}>SIM D (Bus)</option>
                    </select>
                    @error('license_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Experience Years -->
                <div>
                    <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">Pengalaman (Tahun)</label>
                    <input type="number" id="experience_years" name="experience_years" value="{{ old('experience_years') }}" required min="0" max="50"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: 5">
                    @error('experience_years')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Status</option>
                        <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="busy" {{ old('status') === 'busy' ? 'selected' : '' }}>Sedang Bertugas</option>
                        <option value="off" {{ old('status') === 'off' ? 'selected' : '' }}>Libur</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea id="address" name="address" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.drivers.index') }}" 
                   class="px-6 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Driver
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 