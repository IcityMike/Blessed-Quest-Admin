<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;

use App\Models\Settings;
use App\Models\EmailTemplate;
use App\Models\EmailHistoryLog;


class UserresendEmailVerificationEmail extends Notification
{
    use Queueable;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        //dd(config('settings.email_template.user_email_verification_otp'));
       $settings = Settings::first();
       $emailTemplate = EmailTemplate::find(16);
       $data['body'] = $emailTemplate->body;
       $subject = $emailTemplate->subject;
      // dd($this->data['email']);
       
       $data['name'] = $this->data['name'];
      
       
       $data['siteTitle'] = $settings->site_title;
       $data['siteLogo'] = $settings->site_logo;
       
       $data['siteEmail'] = $settings->email_address;
       $data['email'] = $this->data['email'];
     //  $data['password'] = $this->data['password'];
       $data['otp'] = $this->data['email_verification_otp'];

      // $data['body'] = str_replace("[password]",$data['password'], $data['body']);

       $data['body'] = str_replace("[email]",$data['email'], $data['body']);
       
       $mail = (new MailMessage)
       ->view(
           'emails.emailVerificationOtp', $data
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
