<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Confirmation - #' . $this->order->id)
            ->greeting('Hello ' . $notifiable->name)
            ->line('Thank you for your order!')
            ->line('Your order #' . $this->order->id . ' has been confirmed.')
            ->line('Estimated delivery time: ' . $this->order->estimated_delivery_time->format('H:i'))
            ->action('View Order Details', route('customer.orders.show', $this->order))
            ->line('Thank you for choosing our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'total_amount' => $this->order->total_amount,
            'estimated_delivery_time' => $this->order->estimated_delivery_time,
        ];
    }
}

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->user->name,
            'total_amount' => $this->order->total_amount,
            'items_count' => $this->order->orderItems->count(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'total_amount' => $this->order->total_amount,
            'status' => 'new',
        ];
    }
}
