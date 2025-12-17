<?php

namespace App\Http\Controllers\Chat;

use App\Classes\ApiResponse;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\CreateChatRequest;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(CreateChatRequest $request)
    {
        $request->validated();

        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        broadcast(new MessageSent($message));

        return ApiResponse::sendResponse('', $message, 201);
    }

    public function getMessage($receiver_id)
    {
        $message = ChatMessage::where(function($query) use ($receiver_id){
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver_id);
        })
        ->orWhere(function ($query) use ($receiver_id){
            $query->where('sender_id', $receiver_id)
                ->where('receiver_id', Auth::id());
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return ApiResponse::sendResponse('', $message);
    }
}
