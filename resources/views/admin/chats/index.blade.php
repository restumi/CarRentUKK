@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="fade-in-up">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
        <p class="text-gray-600">Daftar user yang bisa kamu chat</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($users->count())
            <div class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <a href="{{ route('admin.chats.show', $user) }}"
                       class="block p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-comments text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada user untuk di-chat.</p>
            </div>
        @endif
    </div>
</div>
@endsection
