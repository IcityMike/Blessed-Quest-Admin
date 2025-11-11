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
use App\Models\SupportTicketStatus;

class SupportTicketReplyNotification extends Notification
{
    use Queueable;
    public $user;
    public $ticketId;
    public $userData;
    //public $portal;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$ticketId, $userData, $status = null)
    {
        $this->user = $user;
        $this->userData = $userData;
        $this->ticketId = $ticketId;
        // $this->portal = $portal;
        // $this->portal = $portal;
        $this->status = $status;
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
       
       switch($this->user)
       {
            case "client":
                $name = $this->userData->first_name." ".$this->userData->last_name;
                break;
            case "admin":
                $name = $this->userData->first_name." ".$this->userData->last_name;
                break;   
            case "referral":
                $name = $this->userData->first_name." ".$this->userData->last_name;
                break;          
            case "default":
                $name = null;
                break;

       }

       if(!$name) return false;
       $emailTemplate = EmailTemplate::find(config('settings.email_template.support_ticket_reply_submission'));
       // dd($emailTemplate);
       if($this->user == "client")
       {
            $data['ticketsUrl'] =route($this->user.'.viewTicket',['id'=>$this->ticketId]);
       }
       else if($this->user == "admin"){

           $data['ticketsUrl'] =route($this->user.'.viewTicket',['id'=>$this->ticketId]);
       }
       $data['siteTitle'] = $settings->site_title;
       $data['siteLogo'] = $settings->site_logo;
       $data['user'] =  $this->user;
       $data['name'] =  $name;
       $data['siteEmail'] = $settings->email_address;
       $data['body'] = $emailTemplate->body;
       $subject = $emailTemplate->subject;
       $data['statusContent'] = null;
       if($this->status)
       {
           $statusC = SupportTicketStatus::find($this->status);
           if($statusC)
           {
               $data['statusContent'] = $statusC->template;
           }
       }
       
       $emailView = View('emails.supportTicketNotification')->with($data);

        /*** Log email details to database ***/

        // EmailHistoryLog::createLog($this->userData->id, config('settings.user_types.'.$this->user), $this->ticketId, config('settings.email_types.support_ticket_reply_submission'),config('settings.email_log_associate_types.support_ticket_reply'), NULL, $emailView, $subject,$this->userData->email);

       return (new MailMessage)
            ->view(
                'emails.supportTicketNotification', $data
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
