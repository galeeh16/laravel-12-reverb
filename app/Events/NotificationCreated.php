<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationCreated implements ShouldBroadcast
{
    /**
     * @var string $createdAt  Waktu dibuatnya notification
     */
    public string $createdAt;

    /**
     * @var string $connection  The name of the queue connection to use when broadcasting the event.
     */
    public $connection = 'redis';

    /**
     * @var string $queue  The name of the queue on which to place the broadcasting job.
     */
    public $queue = 'notification';

    /**
     * NotificationCreated constructor.
     *
     * @param integer $userId
     * @param string $title
     * @param string $message
     */
    public function __construct(
        public int $userId, 
        public string $title, 
        public string $message)
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
        $this->createdAt = date('c');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [
            // new Channel('user.notifications.global'),
            new PrivateChannel('user.notifications.' . $this->userId),
        ];
    }

    /**
     * The event's broadcast name.
     */
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
            'channel' => 'user.notifications.' . $this->userId,
        ];
    }
}

