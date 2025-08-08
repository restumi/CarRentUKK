@extends('admin.layouts.app')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Transactions</h1>
        <p class="text-gray-600 mt-2">Kelola semua transaksi rental mobil</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $approvedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $rejectedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-money-bill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Today's Requests</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Cari berdasarkan user atau car...">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Transaction</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="requested" {{ request('status') === 'requested' ? 'selected' : '' }}>Requested</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Payment</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Canceled</option>
                </select>
            </div>
            <div>
                <label for="time_filter" class="block text-sm font-medium text-gray-700 mb-2">Time Filter</label>
                <select name="time_filter" id="time_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $transaction->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->car->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->driver ? $transaction->driver->name : 'No Driver' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->start_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->end_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">Rp {{ number_format($transaction->total_price) }}</td>
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Canceled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->status_transaction === 'requested')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Requested</span>
                            @elseif($transaction->status_transaction === 'accepted')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Accepted</span>
                            @elseif($transaction->status_transaction === 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Completed</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                                <span class="text-xs text-gray-500">
                                    @if($transaction->created_at->isToday())
                                        <span class="text-green-600">Hari ini</span>
                                    @elseif($transaction->created_at->isYesterday())
                                        <span class="text-blue-600">Kemarin</span>
                                    @elseif($transaction->created_at->diffInDays(now()) <= 7)
                                        <span class="text-orange-600">{{ $transaction->created_at->diffInDays(now()) }} hari yang lalu</span>
                                    @elseif($transaction->created_at->diffInDays(now()) <= 30)
                                        <span class="text-purple-600">{{ $transaction->created_at->diffInDays(now()) }} hari yang lalu</span>
                                    @elseif($transaction->created_at->diffInMonths(now()) <= 1)
                                        <span class="text-gray-600">{{ $transaction->created_at->diffInMonths(now()) }} bulan yang lalu</span>
                                    @else
                                        <span class="text-gray-500">{{ $transaction->created_at->diffInMonths(now()) }} bulan yang lalu</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $transaction->updated_at->format('d M Y H:i') }}</span>
                                <span class="text-xs text-gray-500">
                                    @if($transaction->updated_at->isToday())
                                        <span class="text-green-600">Hari ini</span>
                                    @elseif($transaction->updated_at->isYesterday())
                                        <span class="text-blue-600">Kemarin</span>
                                    @elseif($transaction->updated_at->diffInDays(now()) <= 7)
                                        <span class="text-orange-600">{{ $transaction->updated_at->diffInDays(now()) }} hari yang lalu</span>
                                    @elseif($transaction->updated_at->diffInDays(now()) <= 30)
                                        <span class="text-purple-600">{{ $transaction->updated_at->diffInDays(now()) }} hari yang lalu</span>
                                    @elseif($transaction->updated_at->diffInMonths(now()) <= 1)
                                        <span class="text-gray-600">{{ $transaction->updated_at->diffInMonths(now()) }} bulan yang lalu</span>
                                    @else
                                        <span class="text-gray-500">{{ $transaction->updated_at->diffInMonths(now()) }} bulan yang lalu</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col space-y-1">
                                @if($transaction->status_transaction === 'requested')
                                    <button onclick="approveTransaction({{ $transaction->id }})" class="text-green-600 hover:text-green-900 text-xs">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button onclick="rejectTransaction({{ $transaction->id }})" class="text-red-600 hover:text-red-900 text-xs">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @endif
                                
                                @if($transaction->payment_status === 'pending' && $transaction->payment_method === 'cod')
                                    <button onclick="updatePaymentStatus({{ $transaction->id }})" class="text-blue-600 hover:text-blue-900 text-xs">
                                        <i class="fas fa-money-bill"></i> Mark Paid
                                    </button>
                                @endif
                                
                                @if($transaction->status_transaction === 'accepted')
                                    <button onclick="markAsCompleted({{ $transaction->id }})" class="text-purple-600 hover:text-purple-900 text-xs">
                                        <i class="fas fa-check-double"></i> Complete
                                    </button>
                                @endif
                                
                                @if($transaction->payment_status === 'pending')
                                    <button onclick="cancelPayment({{ $transaction->id }})" class="text-orange-600 hover:text-orange-900 text-xs">
                                        <i class="fas fa-ban"></i> Cancel Payment
                                    </button>
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
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
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
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $transactions->firstItem() }}</span> to <span class="font-medium">{{ $transactions->lastItem() }}</span> of <span class="font-medium">{{ $transactions->total() }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            @if($transactions->onFirstPage())
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                @if($page == $transactions->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
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
function approveTransaction(id) {
    if (confirm('Apakah Anda yakin ingin menyetujui transaksi ini?')) {
        fetch(`/admin/transactions/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada server');
        });
    }
}

function rejectTransaction(id) {
    if (confirm('Apakah Anda yakin ingin menolak transaksi ini?')) {
        fetch(`/admin/transactions/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada server');
        });
    }
}

function updatePaymentStatus(id) {
    if (confirm('Apakah Anda yakin ingin menandai pembayaran ini sebagai lunas?')) {
        fetch(`/admin/transactions/${id}/pay`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada server');
        });
    }
}

function markAsCompleted(id) {
    if (confirm('Apakah Anda yakin ingin menandai transaksi ini sebagai selesai?')) {
        fetch(`/admin/transactions/${id}/completed`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada server');
        });
    }
}

function cancelPayment(id) {
    if (confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')) {
        fetch(`/admin/transactions/${id}/cencel-payment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada server');
        });
    }
}
</script>
@endsection 