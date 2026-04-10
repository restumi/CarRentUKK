<?php

namespace App\Http\Repositories\ChatMessage;

use App\Models\ChatMessage;

class ChatMessageRepository implements ChatMessageRepositoryInterface
{
    public function __construct(private ChatMessage $model)
    {}

    public function query()
    {
        return $this->model->query();
    }

    public function getConversation(int $userId, int $otherUserId, string $order = 'asc')
    {
        return $this->query()
            ->where(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $userId)->where('receiver_id', $otherUserId);
            })
            ->orWhere(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $otherUserId)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', $order)
            ->get();
    }

    public function totalChats()
    {
        $userIds = $this->model->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->pluck('sender_id', 'receiver_id')
            ->flatten()
            ->unique()
            ->filter(fn($id) => $id != auth()->id());

        return $userIds->count();
    }

    public function markAsRead(int $senderId, int $receiverId)
    {
        return $this->query()
            ->where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function countUnread(int $senderId, int $receiverId)
    {
        return $this->query()
            ->where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('is_read', false)
            ->count();
    }

    public function countAllUnreadForUser(int $receiverId)
    {
        return $this->query()
            ->where('receiver_id', $receiverId)
            ->where('is_read', false)
            ->count();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }
}
