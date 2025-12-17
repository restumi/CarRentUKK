@extends('admin.layouts.app')

@section('title', 'Chat with ' . $user->name)

@section('content')
    <div class="fade-in-up flex flex-col lg:flex-row gap-6 h-screen overflow-hidden">
        <!-- Sidebar: Daftar User -->
        <div class="w-full lg:w-1/4 bg-white rounded-lg shadow-md h-fit">
            <div class="p-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">Chats</h2>
            </div>
            <div class="max-h-96 overflow-y-auto custom-scrollbar">
                @foreach ($otherUsers as $u)
                    <a href="{{ route('admin.chats.show', $u) }}"
                        class="flex items-center p-3 hover:bg-gray-50 {{ $u->id === $user->id ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <span class="ml-3 text-sm {{ $u->id === $user->id ? 'font-bold' : '' }}">{{ $u->name }}</span>

                        @if (($unreadCount[$u->id] ?? 0) > 0)
                            <span
                                class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $unreadCount[$u->id] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Chat Area -->
        <div class="w-full lg:w-3/4 flex flex-col bg-white rounded-lg shadow-md h-[87vh]">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 flex items-center">
                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="ml-3">
                    <h2 class="font-bold text-gray-900">{{ $user->name }}</h2>
                </div>
            </div>

            <!-- Messages -->
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto custom-scrollbar">
                @foreach ($messages as $msg)
                    @if ($msg->sender_id == auth()->id())
                        <!-- Admin message (right) -->
                        <div class="flex justify-end mb-3">
                            <div class="bg-blue-500 text-white rounded-lg px-4 py-2 max-w-xs lg:max-w-md">
                                <p>{{ $msg->message }}</p>
                                <p class="text-xs mt-1 text-blue-200">{{ $msg->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    @else
                        <!-- User message (left) -->
                        <div class="flex justify-start mb-3">
                            <div class="bg-gray-200 text-gray-800 rounded-lg px-4 py-2 max-w-xs lg:max-w-md">
                                <p>{{ $msg->message }}</p>
                                <p class="text-xs mt-1 text-gray-500">{{ $msg->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Input -->
            <div class="p-4 border-t border-gray-200">
                <form id="chat-form" class="flex gap-2">
                    @csrf
                    <input type="hidden" id="receiver_id" value="{{ $user->id }}">
                    <input type="text" id="message-input" placeholder="Ketik pesan..."
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        maxlength="1000">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Lock scroll di seluruh halaman, biar yang bisa di-scroll cuma area chat & sidebar
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';

        // Pusher setup
        Pusher.logToConsole = true;

        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        });

        // Subscribe ke channel private chat
        const channel = pusher.subscribe('private-chat.{{ auth()->id() }}');

        channel.bind('App\\Events\\MessageSent', function(data) {
            const messagesDiv = document.getElementById('chat-messages');
            const currentUserId = {{ $user->id }}; // user yang sedang di-chat

            // Tampilkan pesan di chat area (hanya jika sedang chat dengan pengirim)
            if (data.sender_id == currentUserId) {
                const msg = `
                    <div class="flex justify-start mb-3">
                        <div class="bg-gray-200 text-gray-800 rounded-lg px-4 py-2 max-w-xs lg:max-w-md">
                            <p>${data.message}</p>
                            <p class="text-xs mt-1 text-gray-500">${new Date(data.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</p>
                        </div>
                    </div>
                `;
                messagesDiv.insertAdjacentHTML('beforeend', msg);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

            // 🔥 UPDATE BADGE DI SIDEBAR
            const badge = document.querySelector(`a[href$="/admin/chats/${data.sender_id}"] .bg-red-500`);
            if (badge) {
                // Jika badge sudah ada, tambah 1
                let count = parseInt(badge.textContent) + 1;
                badge.textContent = count;
            } else {
                // Jika belum ada badge, buat baru
                const userLink = document.querySelector(`a[href$="/admin/chats/${data.sender_id}"]`);
                if (userLink) {
                    const badgeEl = document.createElement('span');
                    badgeEl.className = 'ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
                    badgeEl.textContent = '1';
                    userLink.appendChild(badgeEl);
                }
            }
        });

        // Kirim pesan
        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const message = input.value.trim();
            const receiverId = {{ $user->id }};

            if (!message) return;

            // Tampilkan pesan lokal dulu
            const messagesDiv = document.getElementById('chat-messages');
            const localMsg = `
                <div class="flex justify-end mb-3">
                    <div class="bg-blue-500 text-white rounded-lg px-4 py-2 max-w-xs lg:max-w-md">
                        <p>${message}</p>
                        <p class="text-xs mt-1 text-blue-200">${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</p>
                    </div>
                </div>
            `;
            messagesDiv.insertAdjacentHTML('beforeend', localMsg);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            input.value = '';

            // Kirim ke server
            fetch('/api/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Authorization': 'Bearer {{ auth()->user()->createToken('admin-chat')->plainTextToken }}'
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    message: message
                })
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mengirim pesan.');
                // Opsional: hapus pesan lokal terakhir
                messagesDiv.lastElementChild?.remove();
            });
        });
    </script>
@endsection
