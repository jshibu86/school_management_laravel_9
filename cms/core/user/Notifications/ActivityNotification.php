<?php

namespace cms\core\user\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ActivityNotification extends Notification
{
    use Queueable;

    protected $notify_type = "";
    protected $notify_msg = "";
    protected $created_by = "";

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notify_type, $notify_msg, $created_by)
    {
        $this->notify_type = $notify_type;
        $this->notify_msg = $notify_msg;
        $this->created_by = $created_by;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["database"];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line("The introduction to the notification.")
            ->action("Notification Action", url("/"))
            ->line("Thank you for using our application!");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "notify_type" => $this->notify_type,
            "notify_msg" => $this->notify_msg,
            "created_by" => $this->created_by,
        ];
    }
}
