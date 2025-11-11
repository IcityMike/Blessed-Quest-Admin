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


class TransferMoneyConfirmationEmailToAdmin extends Notification
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
       $settings = Settings::first();
       $emailTemplate = EmailTemplate::find(config('settings.email_template.user_payment_verification_otp'));
       $data['body'] = $emailTemplate->body;
       $subject = $emailTemplate->subject;

       $data['email'] = $this->data['email'];
       $data['name'] = $this->data['name'];
       $data['otp'] = $this->data['monoova_to_nium_otp'];
       
       $data['siteTitle'] = $settings->site_title;
       $data['siteLogo'] = $settings->site_logo;
       
       $data['siteEmail'] = $settings->email_address;
       $mail = (new MailMessage)
       ->view(
           'emails.paymentVerificationOtp', $data
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
