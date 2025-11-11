<?php
  
namespace App\Mail;
   
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use App\Models\EmailTemplate;
use Carbon\Carbon;

use App\Models\Settings;

  
class PaymentStatusEmail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $data;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    
    }
   
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      
       $settings = Settings::first();

       $data['siteTitle'] = $settings->site_title;
       $data['siteLogo'] = $settings->site_logo;
       $data['siteEmail'] = $settings->email_address;
       $data['callbackData'] = $this->data;
       // $subject = $data['siteTitle']." - ".config('settings.MonoovaNPPReturnEmailSubject');
        $emailTemplate = EmailTemplate::find(config('settings.email_template.payment_status_update_email_to_user'));
       
        $subject = $emailTemplate->subject;
        $data['aboveBody'] = EmailTemplate::get_string_between($emailTemplate->body, '[textAboveLink]', '[/textAboveLink]');
       
      
        return $this->subject($subject)
                    ->view('emails.beneficiaryPaymentDetailEmail', $data);
    }

    
}