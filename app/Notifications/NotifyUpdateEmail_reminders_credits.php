<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;

use App\Models\Settings;
use App\Models\EmailTemplate;


class NotifyUpdateEmail_reminders_credits extends Notification
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

       $emailTemplate = EmailTemplate::find(config('settings.email_template.email_update_notification'));
       
       $data['body'] = $emailTemplate->body;
       $subject = $emailTemplate->subject;

       $data['userName'] = $this->userData->first_name." ".$this->userData->last_name;
       $data['siteTitle'] = $settings->site_title;
       $data['siteLogo'] = $settings->site_logo;
       $data['user'] =  $this->user;
       $data['siteEmail'] = $settings->email_address;
       $date = date('Y-m-d', strtotime("3 days"));

      $data['body'] = str_replace("[date]",$date, $data['body']);

       /*** Log email details to database ***/

       $emailView = View('emails.notifyUpdateEmail')->with( $data);

     
       $mail = (new MailMessage)
       ->view(
           'emails.notifyUpdateEmail', $data
       )
       ->subject(Lang::get($subject));
      
       return $mail;
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
