<?php

namespace cms\admission\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExamLinkEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $admission_id;
    public $name;
    public $exam_id;
    public $notification_text;
    public $school;
    public $logoUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admission_id, $name, $exam_id, $notification_text, $school,$logoUrl)
    {
        //dd($exam_id);
        $this->admission_id=$admission_id;
        $this->name = $name;        
        $this->exam_id = $exam_id;
        $this->notification_text = $notification_text;
        $this->school = $school;
        $this->logoUrl = $logoUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
       // dd($this->school);
        return $this->markdown('admission::mail.ExamLinkEmail')
        ->subject('School Admission - Exam Link')
        ->with([
            'admission_id'=> $this->admission_id,
            'name' => $this->name,            
            'exam_id' => $this->exam_id,
            'notification_text' => $this->notification_text,
            'school' => $this->school,
            'logoUrl' =>  $this->logoUrl,
        ]);
    }
}
