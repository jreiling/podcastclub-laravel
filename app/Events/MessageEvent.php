<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{

    public $handle;
    public $text;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($handle,$message)
    {
        echo 'Send ! "'.$message.'" to '.$handle;
        $this->handle = $handle;
        $this->text = $message;
    }
    public function broadcastOn()
    {
        return new Channel('messages');
    }

    public function broadcastAs()
    {
      return 'messages.send';
    }

}
