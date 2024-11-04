<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExternalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $content;
    public $files;
    public $subject;
    public $fromEmail;
    public $fromName;
    public $logoUrl;
    public $school;

    public function __construct($name, $content, $files, $subject, $fromEmail, $fromName,$logoUrl,$school)
    {
        $this->name = $name;
        $this->content = $content;
        $this->files = $files;
        $this->subject = $subject;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->logoUrl = $logoUrl;
        $this->school = $school;
    }

    public function build()
    {
        $email = $this->markdown('gmailcomunication::admin.externalmail')
            ->subject($this->subject)
            ->with([
                'name' => $this->name,
                'content' => $this->content,
                'logoUrl' => $this->logoUrl,
                'school' => $this->school,
            ])
            ->from($this->fromEmail, $this->fromName);

        if ($this->files) {
            foreach ($this->files as $filePath) {
                if (!empty($filePath) && file_exists(public_path($filePath))) {
                    $email->attach(public_path($filePath), [
                        'as' => basename($filePath),
                        'mime' => mime_content_type(public_path($filePath)),
                    ]);
                } else {
                    throw new \Exception('File path cannot be empty or file does not exist');
                }
            }
        }

        return $email;
    }
}
