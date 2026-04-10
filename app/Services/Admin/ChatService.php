<?php

namespace App\Services\Admin;

use App\Http\Repositories\ChatMessage\ChatMessageRepositoryInterface;
use App\Http\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatService
{
    public function __construct(
        private ChatMessageRepositoryInterface $chatMessageRepository,
        private UserRepositoryInterface $userRepository
    ){}

    public function index()
    {
        $query = $this->userRepository->query();
        $users = $query->where('id', '!=', Auth::id())
            ->whereHas('sentMessages')
            ->orWhereHas('receivedMessages')
            ->with(['sentMessages', 'receivedMessages'])
            ->orderBy('name')
            ->get();

        return $users;
    }

    public function showConversation($userId)
    {
        $authId = Auth::id();

        $user = $this->userRepository->find($userId);

        $messages = $this->chatMessageRepository->getConversation($authId, $userId);

        $this->chatMessageRepository->markAsRead($userId, $authId);

        $otherUsers = $this->userRepository->query()
            ->where('id', '!=', Auth::id())
            ->get();

        $unreadCount = [];
        foreach ($otherUsers as $u) {
            $unreadCount[$u->id] = $this->chatMessageRepository->countUnread($u->id, $authId);
        }

        return [
            'user' => $user,
            'messages' => $messages,
            'otherUsers' => $otherUsers,
            'unreadCount' => $unreadCount
        ];
    }

    public function sendMessage(array $data)
    {
        $messages = $this->chatMessageRepository->create($data);

        broadcast(new MessageSent($messages));

        return $messages;
    }
}
