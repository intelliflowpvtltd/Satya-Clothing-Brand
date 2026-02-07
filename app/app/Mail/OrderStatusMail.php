<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $previousStatus) {}

    public function envelope(): Envelope
    {
        $statusLabels = [
            'confirmed' => 'Order Confirmed',
            'packed' => 'Order Packed',
            'shipped' => 'Order Shipped',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Order Delivered',
            'cancelled' => 'Order Cancelled',
        ];

        $subject = ($statusLabels[$this->order->order_status] ?? 'Order Update')
            . ' - #' . $this->order->order_number;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status',
        );
    }
}
