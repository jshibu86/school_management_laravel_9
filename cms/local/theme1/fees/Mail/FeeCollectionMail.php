<?php

namespace cms\fees\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeeCollectionMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $pdf;
    protected $studentinfo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdf, $studentinfo)
    {
        $this->pdf = $pdf;
        $this->studentinfo = $studentinfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown("fees::mail.feecollectionmail")
            ->with(["studentinfo" => $this->studentinfo])
            ->subject("Fee Payment")
            ->attachData($this->pdf->output(), "receipt.pdf");

        //->attachData($this->pdf->output(), "receipt.pdf");
    }
}
