<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationCreated implements ShouldBroadcast
{
    public $userId;
    public $title;
    public $message;
    public $createdAt;

    public $connection = 'redis';

    public $queue = 'notification';

    public function __construct($userId, $title, $message)
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
        $this->createdAt = date('c');
    }

    public function broadcastOn()
    {
        return new Channel('user.notifications.global');
        // return new PrivateChannel('user.notifications.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'notification.created';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'title' => $this->title,
            'message' => $this->message,
            'created_at' => $this->createdAt,
        ];
    }
}

