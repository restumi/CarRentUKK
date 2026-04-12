@extends('admin.layouts.app')

@section('content')
<div class="fade-in-up">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold">Manajemen Cars</h1>
                <p class="mt-2">Kelola semua mobil yang tersedia untuk rental</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.cars.create') }}" class="action-btn primary">
                    <i class="fas fa-plus mr-2"></i>Tambah Mobil
                </a>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex items-center justify-between">
            <div>
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 flex items-center justify-between">
            <div>
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Search and Filter -->
    <div class="admin-form mb-6 bg-gray-800 text-white">
        <form method="GET" action="{{ route('admin.cars.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="form-group">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nama, brand, atau seat...">
            </div>
            <div class="form-group">
                <label for="brand">Filter Brand</label>
                <select name="brand" id="brand">
                    <option value="">Semua Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="price_range">Price Range</label>
                <select name="price_range" id="price_range">
                    <option value="">Semua Harga</option>
                    <option value="0-500000" {{ request('price_range') === '0-500000' ? 'selected' : '' }}>Rp 0 - 500.000</option>
                    <option value="500000-1000000" {{ request('price_range') === '500000-1000000' ? 'selected' : '' }}>Rp 500.000 - 1.000.000</option>
                    <option value="1000000+" {{ request('price_range') === '1000000+' ? 'selected' : '' }}>Rp 1.000.000+</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="action-btn primary w-full">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>

    <!-- Cars Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($cars as $car)
        <div class="bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 hover-lift">
            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-car text-gray-400 text-4xl"></i>
                    </div>
                @endif
            </div>

            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold truncate">{{ $car->name }}</h3>
                    <span class="status-badge active">Available</span>
                </div>

                <div class="space-y-2 text-sm text-gray-300">
                    <div class="flex justify-between">
                        <span class="font-medium">Brand:</span>
                        <span>{{ $car->brand }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Seat:</span>
                        <span class="font-mono">{{ $car->seat }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Price/Day:</span>
                        <span class="font-semibold text-green-600">Rp {{ number_format($car->price_per_day) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Created:</span>
                        <span>{{ $car->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Updated:</span>
                        <span>{{ $car->updated_at->format('d M Y') }}</span>
                    </div>
                </div>



                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('admin.cars.edit', $car) }}" class="action-btn primary flex-1 text-center">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mobil ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn danger w-full">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <i class="fas fa-car text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada mobil</h3>
                <p class="text-gray-500 mb-6">Belum ada mobil yang ditambahkan ke sistem.</p>
                <a href="{{ route('admin.cars.create') }}" class="action-btn primary">
                    <i class="fas fa-plus mr-2"></i>Tambah Mobil Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($cars->hasPages())
    <div class="mt-8">
        {{ $cars->links() }}
    </div>
    @endif
</div>
@endsection
