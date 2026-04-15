@extends('admin.layouts.app')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Manajemen Transactions</h1>
        <p class="mt-2">Kelola semua transaksi rental mobil</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium">Pending</p>
                    <p class="text-2xl font-bold">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium">Approved</p>
                    <p class="text-2xl font-bold">{{ $approvedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium">Rejected</p>
                    <p class="text-2xl font-bold">{{ $rejectedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-money-bill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium">Revenue</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium">Today's Requests</p>
                    <p class="text-2xl font-bold">{{ $todayCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium mb-2">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 bg-gray-900"
                       placeholder="Cari berdasarkan user atau car...">
            </div>
            <div>
                <label for="payment_status" class="block text-sm font-medium mb-2">Payment Status</label>
                <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 bg-gray-900">
                    <option value="">Semua Payment</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Canceled</option>
                </select>
            </div>
            <div>
                <label for="time_filter" class="block text-sm font-medium mb-2">Time Filter</label>
                <select name="time_filter" id="time_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 bg-gray-900">
                    <option value="">Semua Waktu</option>
                    <option value="today" {{ request('time_filter') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="yesterday" {{ request('time_filter') === 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                    <option value="week" {{ request('time_filter') === 'week' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="month" {{ request('time_filter') === 'month' ? 'selected' : '' }}>30 Hari Terakhir</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>

    <p class="text-md font-medium mb-2 ml-2">Status transaction :</p>
    <div class="mb-6 space-x-2">
        @php
            $baseParams = request()->query();
            unset($baseParams['status']);
        @endphp

        <a href="{{ route('admin.transactions.index', $baseParams) }}"
        class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{
            !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
        }}">
            Semua
        </a>

        <a href="{{ route('admin.transactions.index', array_merge($baseParams, ['status' => 'requested'])) }}"
        class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{
            request('status') == 'requested' ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
        }}">
            Requested
        </a>

        <a href="{{ route('admin.transactions.index', array_merge($baseParams, ['status' => 'accepted'])) }}"
        class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{
            request('status') == 'accepted' ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
        }}">
            Accepted
        </a>

        <a href="{{ route('admin.transactions.index', array_merge($baseParams, ['status' => 'rejected'])) }}"
        class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{
            request('status') == 'rejected' ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
        }}">
            Rejected
        </a>

        <a href="{{ route('admin.transactions.index', array_merge($baseParams, ['status' => 'cancelled'])) }}"
        class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{
            request('status') == 'cancelled' ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
        }}">
            Cancelled
        </a>

        <a href="{{ route('admin.transactions.index', array_merge($baseParams, ['status' => 'completed'])) }}"
        class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{
            request('status') == 'completed' ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
        }}">
            Completed
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="w-full p-4 bg-red-500/50 border border-red-500 rounded-lg shadow-md mb-6 text-center alert">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="w-full p-4 bg-green-500/50 border border-green-500 rounded-lg shadow-md mb-6 text-center alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Transactions Table -->
    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Car</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Total Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Transaction Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Request Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-900">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $transaction->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->car->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $transaction->driver ? $transaction->driver->name : 'No Driver' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->start_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->end_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-500">Rp {{ number_format($transaction->total_price) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($transaction->payment_method === 'cod') bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ strtoupper($transaction->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->payment_status === 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($transaction->payment_status === 'paid')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->status_transaction === 'requested')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Requested</span>
                            @elseif($transaction->status_transaction === 'accepted')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Accepted</span>
                            @elseif($transaction->status_transaction === 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @elseif($transaction->status_transaction === 'cancelled')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Completed</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                                <span class="text-xs">
                                    @if($transaction->created_at->isToday())
                                        <span class="text-green-500">Hari ini</span>
                                    @elseif($transaction->created_at->isYesterday())
                                        <span class="text-blue-500">Kemarin</span>
                                    @elseif($transaction->created_at->diffInDays(now()) <= 7)
                                        <span class="text-orange-500">{{ $transaction->created_at->diffInDays(now()) }} hari yang lalu</span>
                                    @elseif($transaction->created_at->diffInDays(now()) <= 30)
                                        <span class="text-purple-500">{{ $transaction->created_at->diffInDays(now()) }} hari yang lalu</span>
                                    @elseif($transaction->created_at->diffInMonths(now()) <= 1)
                                        <span class="text-gray-500">{{ $transaction->created_at->diffInMonths(now()) }} bulan yang lalu</span>
                                    @else
                                        <span class="text-gray-500">{{ $transaction->created_at->diffInMonths(now()) }} bulan yang lalu</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $transaction->updated_at->format('d M Y H:i') }}</span>
                                <span class="text-xs text-gray-500">
                                    @if($transaction->updated_at->isToday())
                                        <span class="text-green-500">Hari ini</span>
                                    @elseif($transaction->updated_at->isYesterday())
                                        <span class="text-blue-500">Kemarin</span>
                                    @elseif($transaction->updated_at->diffInDays(now()) <= 7)
                                        <span class="text-orange-500">{{ $transaction->updated_at->diffInDays(now()) }} hari yang lalu</span>
                                    @elseif($transaction->updated_at->diffInDays(now()) <= 30)
                                        <span class="text-purple-500">{{ $transaction->updated_at->diffInDays(now()) }} hari yang lalu</span>
                                    @elseif($transaction->updated_at->diffInMonths(now()) <= 1)
                                        <span class="text-gray-500">{{ $transaction->updated_at->diffInMonths(now()) }} bulan yang lalu</span>
                                    @else
                                        <span class="text-gray-500">{{ $transaction->updated_at->diffInMonths(now()) }} bulan yang lalu</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col space-y-1">
                                @if($transaction->status_transaction === 'requested')
                                    <form action="{{ route('admin.transactions.approve', $transaction->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 p-2 rounded-md text-xs w-full">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.transactions.reject', $transaction->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 p-2 rounded-md text-xs w-full">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                @else
                                    @if($transaction->payment_status === 'pending' && $transaction->payment_method === 'cod')
                                        <form action="{{ route('admin.transactions.pay', $transaction->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 p-2 rounded-md text-xs w-full">
                                                <i class="fas fa-money-bill"></i> Mark Paid
                                            </button>
                                        </form>
                                    @endif

                                    @if($transaction->status_transaction === 'accepted')
                                        <form action="{{ route('admin.transactions.completed', $transaction->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-purple-500 hover:bg-purple-600 p-2 rounded-md text-xs w-full">
                                                <i class="fas fa-check-double"></i> Complete
                                            </button>
                                        </form>
                                    @endif

                                    @if($transaction->payment_status === 'pending')
                                        <form action="{{ route('admin.transactions.cencel-payment', $transaction->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 p-2 rounded-md text-xs w-full">
                                                <i class="fas fa-ban"></i> Cancel Payment
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="px-6 py-4 text-center text-gray-500">Tidak ada transaksi ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="bg-gray-800 px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if($transactions->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                            Previous
                        </span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                            Previous
                        </a>
                    @endif

                    @if($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                            Next
                        </a>
                    @else
                        <span class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                            Next
                        </span>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm">
                            Showing <span class="font-medium text-blue-500">{{ $transactions->firstItem() }}</span> to <span class="font-medium text-blue-500">{{ $transactions->lastItem() }}</span> of <span class="font-medium text-blue-500">{{ $transactions->total() }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            @if($transactions->onFirstPage())
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-900 text-sm font-medium text-gray-500 cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-900 text-sm font-medium text-gray-500">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                @if($page == $transactions->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-800 text-sm font-medium text-blue-600">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-900 text-sm font-medium hover:bg-gray-800">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-900 text-sm font-medium text-gray-500">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-900 text-sm font-medium text-gray-500 cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);
</script>
@endsection
