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

  
class ClientPersonalInfoEmail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $piData;
    public $data;
    public $fileName;
    public $efileName;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($piData, $data, $fileName, $efileName = null)
    {
        $this->data = $data;
        $this->piData = $piData;
        $this->fileName = $fileName;
        $this->efileName = $efileName;
    
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
       $data['userData'] = $this->data;
       $data['piData'] = $this->piData;
       $emailTemplate = EmailTemplate::find(config('settings.email_template.client_personal_info_email'));
       $data['body'] = $emailTemplate->body;
       $subject = $emailTemplate->subject;
        
        $mail =  $this->subject($subject)
                    ->view('emails.clientPersonalInfoEmail', $data)
                    ->attach(public_path('pi_pdf/'.$this->data['id'].'/'.$this->fileName), [
                        'as' => $this->fileName,
                        'mime' => 'application/pdf',
                   ]);
                
        if($this->efileName)
        {
            $mail->attach(public_path('pdf/'.$this->efileName), [
                'as' => $this->efileName,
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
                   
    }

    
}