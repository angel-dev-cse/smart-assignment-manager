<?php

namespace App\Listeners;

use App\Events\SendMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MessageSentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        // $message = $event->message;
        
        // broadcast(new SendMessage($message))->toOthers();

        // Log::debug("listener".$event->message);
    }
}
