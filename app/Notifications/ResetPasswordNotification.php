<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;

use App\Models\Settings;
use App\Models\EmailTemplate;
use App\Models\Admin;
use App\Helpers\Common;

class ResetPasswordNotification extends Notification
{
    use Queueable;
    public $user;
    public $userData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $userData)
    {
        $this->user = $user;
        $this->userData = $userData;
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
     * Updated by: Radhika Savaliya
     *
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
       $settings = Settings::first();
       
        $emailTemplate = EmailTemplate::find(17);

        $data['siteTitle'] = $settings->site_title;
        $data['siteLogo'] = $settings->site_logo;
        $data['user'] =  @$this->userData->first_name;
        $data['siteEmail'] = $settings->email_address;
        $data['body'] = $emailTemplate->body;
        $subject = $emailTemplate->subject;
        $data['name'] =  @$this->userData->first_name.' '.@$this->userData->last_name;
        // $emailView = View('emails.resetPasswordNotification')->with( $data);
        return (new MailMessage)
        ->view(
            'emails.resetPasswordNotification', $data
        )
        ->subject(Lang::get($subject));
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
