<?php

namespace App\Notifications;
use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;
use Kreait\Firebase\Exception\Messaging\NotFound;

class FirebasePushNotification extends Notification
{
    use Queueable;

    protected $msg_title = "";
    protected $msg_text = "";
    protected $data = [];
    protected $image = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        $msg_title,
        $msg_text,
        $data = [],
        $image = null
    ) {
        $this->msg_title = $msg_title;
        $this->msg_text = $msg_text;
        $this->data = $data;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toFcm($notifiable)
    {
        try {
            $notification = \NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($this->msg_title)
                ->setBody($this->msg_text);
            if ($this->image) {
                $notification->setImage($this->image);
            }
            if (empty($this->data)) {
                $this->data = ["route" => "notification"];
            }

            return FcmMessage::create()

                ->setData($this->data)
                ->setNotification($notification)

                ->setAndroid(
                    AndroidConfig::create()
                        ->setFcmOptions(
                            AndroidFcmOptions::create()->setAnalyticsLabel(
                                "analytics"
                            )
                        )
                        ->setNotification(
                            AndroidNotification::create()->setColor("#324398")
                        )
                )
                ->setApns(
                    ApnsConfig::create()->setFcmOptions(
                        ApnsFcmOptions::create()->setAnalyticsLabel(
                            "analytics_ios"
                        )
                    )
                );
        } catch (NotFound $th) {
            $t = $th->errors();
            \Log::error($t);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function fcmProject($notifiable, $message)
    {
        // $message is what is returned by `toFcm`
        return config("firebase.default"); // name of the firebase project to use
    }
}
