<?php

namespace App\Events;

use App\Models\Activity;
use App\Transformers\ActivityTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActivityLogged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('activity.' . $this->activity->user->id);
    }

    public function broadcastWith()
    {
        return fractal($this->activity, new ActivityTransformer())->toArray();
    }
}
