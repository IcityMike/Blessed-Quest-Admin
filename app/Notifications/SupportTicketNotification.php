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
use App\Models\EmailHistoryLog;

class SupportTicketNotification extends Notification
{
    use Queueable;
    public $user;
    public $ticketId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$ticketId)
    {
        $this->user = $user;
        $this->ticketId = $ticketId;
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
       
       $emailTemplate = EmailTemplate::find(config('settings.email_template.support_ticket_submission'));
       $admin = Admin::find($settings->support_admin);
       $data['name'] = $admin->first_name." ".$admin->last_name;

        $data['ticketsUrl'] =route('admin.viewTicket',['id'=>$this->ticketId]);
        $data['siteTitle'] = $settings->site_title;
        $data['siteLogo'] = $settings->site_logo;
        $data['user'] =  $this->user;
        $data['siteEmail'] = $settings->email_address;
        $data['body'] = $emailTemplate->body;
        $ticket_number = str_pad($this->ticketId,5,"0",STR_PAD_LEFT);
        $data['body'] = str_replace("[ticket_number]",$ticket_number,$data['body']);
        $subject = $emailTemplate->subject;

         /*** Log email details to database ***/
        $emailView = View('emails.supportTicketNotification')->with($data);

        // EmailHistoryLog::createLog($admin->id, config('settings.user_types.'.$this->user), $this->ticketId, config('settings.email_types.support_ticket_submission'),config('settings.email_log_associate_types.support_ticket'), NULL, $emailView , $subject, $admin->email);

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
