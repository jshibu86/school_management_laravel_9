<?php

namespace App\Jobs;

use cms\core\user\Mail\PasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PasswordSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $password;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $password, $email)
    {
        $this->user = $user;
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (config("app.env") == "production") {
            \CmsMail::setMailConfig();

            $send_email = new PasswordMail($this->user, $this->password);

            \Mail::to($this->email)->send($send_email);
        }
    }
}
