<?php

namespace App\Http\Repositories\ChatMessage;

interface ChatMessageRepositoryInterface
{
    public function query();

    public function getConversation(int $userId, int $otherUserId, string $order = 'asc');

    public function totalChats();

    public function markAsRead(int $senderId, int $receiverId);

    public function countUnread(int $senderId, int $receiverId);

    public function countAllUnreadForUser(int $receiverId);

    public function create($data);
}
