<?php
  
namespace App\Mail;
   
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

use App\Models\Settings;
use App\Models\EmailTemplate;

  
class ClientPaymentDetailEmail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $data;
    public $fileNames;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        // $this->fileNames = $filesNames;
    
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
       $data['userData'] = $this->data['user'];
       $data['payment_detail'] = $this->data['payment_detail'];
       $emailTemplate = EmailTemplate::find(config('settings.email_template.payment_detail_email_to_client'));
       $data['body'] = $emailTemplate->body;
       $subject = $emailTemplate->subject;
        
        $email = $this->subject($subject)
                    ->bcc(config('settings.bcc_email_address'))
                    ->view('emails.paymentDetailEmail', $data);
                // foreach($this->fileNames as $file_name){
                //     $email->attach(public_path('quote_binding_document/'.$this->data['insurance_type'].'/'.$this->data['quotation_id'].'/'.$file_name), [
                //         'as' => $file_name,
                //         'mime' => 'application/pdf']);
                // }

        return $email;
    }

    
}