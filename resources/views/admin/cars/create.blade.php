@extends('admin.layouts.app')

@section('title', 'Tambah Mobil')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Mobil Baru</h1>
            <p class="text-gray-600">Tambahkan mobil baru ke dalam sistem</p>
        </div>
        <a href="{{ route('admin.cars.index') }}" 
           class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.cars.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Brand -->
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Toyota, Honda, Suzuki">
                    @error('brand')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                    <input type="text" id="model" name="model" value="{{ old('model') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Avanza, Brio, Ertiga">
                    @error('model')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License Plate -->
                <div>
                    <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">Plat Nomor</label>
                    <input type="text" id="license_plate" name="license_plate" value="{{ old('license_plate') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: B 1234 ABC">
                    @error('license_plate')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number" id="year" name="year" value="{{ old('year') }}" required min="1990" max="{{ date('Y') + 1 }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: 2020">
                    @error('year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                    <input type="text" id="color" name="color" value="{{ old('color') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Putih, Hitam, Merah">
                    @error('color')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transmission -->
                <div>
                    <label for="transmission" class="block text-sm font-medium text-gray-700 mb-2">Transmisi</label>
                    <select id="transmission" name="transmission" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Transmisi</option>
                        <option value="Manual" {{ old('transmission') === 'Manual' ? 'selected' : '' }}>Manual</option>
                        <option value="Automatic" {{ old('transmission') === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="CVT" {{ old('transmission') === 'CVT' ? 'selected' : '' }}>CVT</option>
                    </select>
                    @error('transmission')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fuel Type -->
                <div>
                    <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Bahan Bakar</label>
                    <select id="fuel_type" name="fuel_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Bahan Bakar</option>
                        <option value="Bensin" {{ old('fuel_type') === 'Bensin' ? 'selected' : '' }}>Bensin</option>
                        <option value="Solar" {{ old('fuel_type') === 'Solar' ? 'selected' : '' }}>Solar</option>
                        <option value="Hybrid" {{ old('fuel_type') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="Electric" {{ old('fuel_type') === 'Electric' ? 'selected' : '' }}>Electric</option>
                    </select>
                    @error('fuel_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Per Day -->
                <div>
                    <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-2">Harga per Hari</label>
                    <input type="number" id="price_per_day" name="price_per_day" value="{{ old('price_per_day') }}" required min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: 500000">
                    @error('price_per_day')
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
                        <option value="rented" {{ old('status') === 'rented' ? 'selected' : '' }}>Disewa</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Deskripsi mobil, fitur-fitur, kondisi, dll">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Foto Mobil</label>
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.cars.index') }}" 
                   class="px-6 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Mobil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 