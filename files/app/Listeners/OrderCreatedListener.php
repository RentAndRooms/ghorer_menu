<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCreatedListener implements ShouldQueue
{
    public function handle(OrderCreated $event)
    {
        // Notify branch staff
        $event->order->branch->staff->each(function ($staff) use ($event) {
            $staff->notify(new NewOrderNotification($event->order));
        });

        // Send confirmation to customer
        $event->order->user->notify(new OrderConfirmationNotification($event->order));
    }
}
