<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use App\Models\Settings;
use App\Models\EmailTemplate;

class BirthdayReminderEmailToAdmin extends Notification
{
    use Queueable;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $settings = Settings::first();

        $data['siteTitle'] = $settings->site_title;
        $data['siteLogo'] = $settings->site_logo;
        $data['siteEmail'] = $settings->email_address;
        $emailTemplate = EmailTemplate::find(config('settings.email_template.birthday_reminder_email'));
        $data['body'] = $emailTemplate->body;
        $userName = $this->user->first_name." ".$this->user->last_name;
        $data['body'] = str_replace("[user_name]",$userName, $data['body']);
        $subject = $emailTemplate->subject;

        $email = (new MailMessage)
        ->view(
           'emails.birthdayReminderEmail', $data
        )
        ->subject(Lang::get($subject));

        return $email;
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
            //
        ];
    }
}
