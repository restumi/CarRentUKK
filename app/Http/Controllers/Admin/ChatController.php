<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\Admin\SendMessagesRequest;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\Admin\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chatService
    ){}

    public function index()
    {
        $users = $this->chatService->index();

        return view('admin.chats.index', compact('users'));
    }

    public function show(User $user)
    {
        $data = $this->chatService->showConversation($user->id);

        return view('admin.chats.show', $data);
    }

    public function sendMessages(SendMessagesRequest $request, User $user)
    {
        $request->validated();

        $data = [
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message
        ];

        $this->chatService->sendMessages($data);

        return redirect()->back()->with('success');
    }
}
