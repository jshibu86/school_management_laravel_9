<?php

namespace cms\shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order_data;
    public $pdf;
    public $user_data;
    public function __construct($order_data, $pdf, $user_data)
    {
        $this->order_data = $order_data;
        $this->pdf = $pdf;

        $this->user_data = $user_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown("shop::admin.mail.ordermail", [
            "user_data" => $this->user_data,
            "order_number" => $this->order_data->order_number,
        ])
            ->subject("ordermail - " . $this->order_data->order_number)
            ->attachData($this->pdf->output(), "invoice.pdf");
    }
}
