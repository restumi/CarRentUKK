@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Verifikasi User Baru</h1>
        <p class="text-gray-600 mt-2">Kelola permintaan pendaftaran user baru</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-md bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-md bg-red-100 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-2xl font-semibold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600">Approved</p>
            <p class="text-2xl font-semibold text-green-600">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600">Rejected</p>
            <p class="text-2xl font-semibold text-red-600">{{ $stats['rejected'] }}</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 space-x-2">
        <a href="{{ route('admin.verification.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-md {{ $status=='pending' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
           Pending
        </a>
        <a href="{{ route('admin.verification.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-md {{ $status=='approved' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
           Approved
        </a>
        <a href="{{ route('admin.verification.index', ['status' => 'rejected']) }}"
           class="px-4 py-2 rounded-md {{ $status=='rejected' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
           Rejected
        </a>
        <a href="{{ route('admin.verification.index', ['status' => 'all']) }}"
           class="px-4 py-2 rounded-md {{ $status=='all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
           Semua
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($verifications as $verification)
                    <tr onclick="openModal({{ $verification->id }})" class="cursor-pointer hover:bg-gray-100">
                        <td class="px-6 py-4">#{{ $verification->id }}</td>
                        <td class="px-6 py-4">{{ $verification->name }}</td>
                        <td class="px-6 py-4">{{ $verification->email }}</td>
                        <td class="px-6 py-4">
                            @if($verification->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($verification->status == 'approved')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Approved</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $verification->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 flex space-x-2" onclick="event.stopPropagation()">
                            @if($verification->status == 'pending')
                                <form action="{{ route('admin.verification.approve', $verification->id) }}" method="POST">
                                    @csrf
                                    <button class="text-green-600 hover:underline">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.verification.reject', $verification->id) }}" method="POST">
                                    @csrf
                                    <button class="text-red-600 hover:underline">
                                        Reject
                                    </button>
                                </form>
                            @elseif($verification->status == 'rejected' && $verification->reject_reason)
                                <span class="text-gray-500">Alasan: {{ $verification->reject_reason }}</span>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $verifications->links() }}
    </div>
</div>

<!-- Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 max-h-screen overflow-y-auto p-6 relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            ✕
        </button>
        <h3 id="modalName" class="text-xl font-bold"></h3>
        <p id="modalEmail" class="text-gray-600"></p>

        <div class="mt-3 space-y-2 text-sm">
            <p><span class="font-medium">Status:</span> <span id="modalStatus"></span></p>
            <p><span class="font-medium">Tanggal:</span> <span id="modalTanggal"></span></p>
        </div>

        <div id="modalKtp" class="mt-3"></div>
        <div id="modalFace" class="mt-3"></div>

        <div id="modalReason" class="mt-3 text-sm text-red-600"></div>

        <div class="mt-6 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-blue-600 text-white rounded">
                Tutup
            </button>
        </div>
    </div>
</div>


<script>
    function openModal(id) {
        fetch(`/admin/verification/${id}`)
            .then(res => res.json())
            .then(response => {
                const verify = response.data;

                document.getElementById('modalName').innerText = verify.name;
                document.getElementById('modalEmail').innerText = verify.email;
                document.getElementById('modalStatus').innerText = verify.status;
                document.getElementById('modalTanggal').innerText = verify.created_at;

                // Foto KTP
                document.getElementById('modalKtp').innerHTML = verify.ktp_image
                    ? `<p class="font-medium">Foto KTP:</p><img src="/storage/${verify.ktp_image}" class="w-full rounded">`
                    : '';

                // Foto Wajah
                document.getElementById('modalFace').innerHTML = verify.face_image
                    ? `<p class="font-medium">Foto Wajah:</p><img src="/storage/${verify.face_image}" class="w-full rounded">`
                    : '';

                // Alasan reject
                document.getElementById('modalReason').innerText = verify.reject_reason ? `Alasan: ${verify.reject_reason}` : '';

                document.getElementById('detailModal').classList.remove('hidden');
            });
    }

    function closeModal(){
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>

@endsection
