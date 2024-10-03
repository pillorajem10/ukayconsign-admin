<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationsForOrderStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->view('emails.admin_order_status_notification')
                    ->subject('Order Status Updated - Order ID: ' . $this->order->id)
                    ->with([
                        'order' => $this->order,
                    ]);
    }
}
