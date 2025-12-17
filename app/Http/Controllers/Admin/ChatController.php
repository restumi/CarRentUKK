<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->whereHas('sentMessages')
            ->orWhereHas('receivedMessages')
            ->with(['sentMessages', 'receivedMessages'])
            ->orderBy('name')
            ->get();

        return view('admin.chats.index', compact('users'));
    }

    public function show(User $user)
    {
        $messages = ChatMessage::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })
            ->orWhere(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        ChatMessage::where('sender_id', $user->id)->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Ambil semua user lain
        $otherUsers = User::where('id', '!=', Auth::id())->get();

        // Hitung pesan yang belum dibaca dari masing-masing user ke admin yang login
        $unreadCount = [];
        foreach ($otherUsers as $u) {
            $unreadCount[$u->id] = ChatMessage::where('sender_id', $u->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }

        return view('admin.chats.show', compact('user', 'messages', 'otherUsers', 'unreadCount'));
    }

    public function sendMessages(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message
        ]);

        broadcast(new MessageSent($message));

        return redirect()->back()->with('success');
    }
}
