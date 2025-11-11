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


class ClientWelcomeEmail extends Notification
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

     // dd(config('settings.email_template.client_welcome_email'));
       $settings = Settings::first();
       $emailTemplate = EmailTemplate::find(13);
       $data['body'] = $emailTemplate->body;
       $subject = $emailTemplate->subject;

       $data['email'] = $this->data['email'];
       $data['password'] = $this->data['password'];
       $data['name'] = $this->data['name'];
     //  $data['loginLink'] =  url(route('client.dashboard'));

       $data['loginLink'] =  "";
        // $url = "";
        // if(!empty($this->data['reffered_by']) && !empty($this->data['referral_code'])){
        //     $url = url(route('client.loginindex',['ref_code' => $this->data['referral_code']]));
        // }else{
        //     $url =  url(route('client.dashboard'));
        // }
        // $data['loginLink'] =  $url;
       

       $data['siteTitle'] = $settings->site_title;
       $data['siteLogo'] = $settings->site_logo;
       
       $data['siteEmail'] = $settings->email_address;
        
       /*** Log email details to database ***/
        $logData = $data;
        $logData['password'] = '******';

        // Characters to be replaced
        $search = array('[email]', '[password]');

        // Replacement characters
        $replace = array($data['email'], $data['password']);

        $data['body'] = str_replace($search, $replace,$data['body']);
        $emailView = View('emails.welcomeUserEmail')->with( $logData);
  
        // EmailHistoryLog::createLog( $this->data['clientId'], config('settings.user_types.client'), NULL, config('settings.email_types.client_welcome_email'), NULL, NULL, $emailView, $subject, $data['email']);

       $mail = (new MailMessage)
       ->view(
           'emails.welcomeUserEmail', $data
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
