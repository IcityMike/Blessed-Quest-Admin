<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Lang;
use App\Models\ReferralPartner;
use App\Models\Settings;
use App\Models\EmailTemplate;
use App\Models\Admin;
use App\Models\User;


class ResetPassword extends ResetPasswordNotification
{
    use Queueable;
    public $token;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token,$user)
    {
        $this->token = $token;
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
     * Updated by: Radhika Savaliya
     *
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = $notifiable->getEmailForPasswordReset();
        switch($this->user)
        {
            case "admin":
                $user = Admin::where('email','=', $email)->first();
                $name = $user->first_name." ".$user->last_name;
                break;
            case "client":
                $user = User::where('email','=', $email)->first();
                $name = $user->first_name." ".$user->last_name;
                break;
            case "refferal":
               $user = ReferralPartner::where('email','=', $email)->first();
               $name = $user->first_name." ".$user->last_name;
                break;
            case "default":
                $name = null;
                break;
        }
       
        if(!$name) return false;
        
        $settings = Settings::first();
        $emailTemplate = EmailTemplate::find(config('settings.email_template.password_reset_email'));

       $data['resetUrl'] = url(config('app.url').route($this->user.'.password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false));
       
       $data['siteTitle'] = $settings->site_title;
       $data['siteLogo'] = $settings->site_logo;
       $data['siteEmail'] = $settings->email_address;
       $data['token'] = $this->token;
       $data['user'] =  $this->user;
       $data['name'] =  $name;
       $data['aboveBody'] = EmailTemplate::get_string_between($emailTemplate->body, '[textAboveLink]', '[/textAboveLink]');
       $data['belowBody'] = EmailTemplate::get_string_between($emailTemplate->body, '[textBelowLink]', '[/textBelowLink]');
       $subject = $emailTemplate->subject;

        /*** Log email details to database ***/
        $emailView = View('emails.passwordEmail')->with( $data);

       return (new MailMessage)
            ->view(
                'emails.passwordEmail', $data
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
