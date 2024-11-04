<?php

namespace cms\payrool\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayrollPaySlip extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $month;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdf, $month)
    {
        $this->pdf = $pdf;
        $this->month = $month;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown("payrool::mail.payslip")
            ->with([
                "month" => $this->month,
            ])
            ->attachData($this->pdf->output(), "payslip.pdf", [
                "mime" => "application/pdf",
            ]);
    }
}
