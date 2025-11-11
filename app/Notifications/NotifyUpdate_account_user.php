<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;

use App\Models\Settings;
use App\Models\EmailTemplate;


class NotifyUpdate_account_user extends Notification
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
     * @return \Illuminate\Notifications\Messages\MailMessage Receive reminders 3 days before subscription auto-renews
     */
    public function toMail($notifiable)
    {
       $settings = Settings::first();

       $emailTemplate = EmailTemplate::find(config('settings.email_template.email_notificationUpdate_account_user'));
       
       $data['body'] = $emailTemplate->body;
       $subject = $emailTemplate->subject;

       $data['userName'] = $this->userData->name;
       $data['siteTitle'] = $settings->site_title;
       $data['siteLogo'] = $settings->site_logo;
       $data['user'] =  $this->user;
       $data['siteEmail'] = $settings->email_address;
       $date = date('Y-m-d', strtotime("3 days"));

       //dd($this->userData->name,$this->userData->phone,$this->userData->status,$this->userData->password, $this->userData->profile_picture);
      $data['phone'] = $this->userData->phone_number;
      $data['status'] = $this->userData->status;
      $data['email'] = $this->userData->email;
      $data['password'] = $this->userData->password;

      $data['body'] = str_replace('user',$date, $data['body']);

       /*** Log email details to database ***/

       $emailView = View('emails.email_Update_account_user')->with( $data);

       $mail = (new MailMessage)
       ->view(
           'emails.email_Update_account_user', $data
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
