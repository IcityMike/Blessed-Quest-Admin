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
use App\Models\Admin;
use App\Models\ReferralPartner;
use App\Models\User;
use App\Models\Employee;
use App\Models\EmailHistoryLog;
  
class VerifyNewEmail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $userId;
    public $user;
    public $email;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->userId = $data['userId'];
        $this->user = $data['user'];
        $this->email = $data['email'];
    }
   
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['verificationUrl'] =$this->verificationUrl($this->userId, $this->email, $this->user);
        $settings = Settings::first();

        switch($this->user)
        {
            case "client":
                $user = User::find($this->userId);
                $name = $user->first_name." ".$user->last_name;
                break;
            case "admin":
               $user = Admin::find($this->userId);
               $name = $user->first_name." ".$user->last_name;
                break;
            case "refferal":
               $user = ReferralPartner::find($this->userId);
               $name = $user->first_name." ".$user->last_name;
                break;
            case "default":
                $name = null;
                break;

       }
      
       if(!$name) return false;

       $emailTemplate = EmailTemplate::find(config('settings.email_template.update_email_verification'));

        $data['siteTitle'] = $settings->site_title;
        $data['siteLogo'] = $settings->site_logo;
        $data['siteEmail'] = $settings->email_address;
        $data['name'] = $name;
        $data['aboveBody'] = EmailTemplate::get_string_between($emailTemplate->body, '[textAboveLink]', '[/textAboveLink]');
        $data['belowBody'] = EmailTemplate::get_string_between($emailTemplate->body, '[textBelowLink]', '[/textBelowLink]');
        $subject = $emailTemplate->subject;
     
        return $this->subject($subject)
                    ->view('emails.verifyUpdateEmail', $data);
    }

    /**
    * Get the verification URL for the given notifiable.
    *
    * @param mixed $notifiable
    * @return string
    */
    protected function verificationUrl($id, $email, $user)
    {
        if($user == "client"){

            return url(route('client.verifyUpdatedEmail',['id' => $id,'email' => $email, 'user' => $user])); 
        }elseif($user == "refferal"){

            return url(route('refferal.verifyUpdatedEmail',['id' => $id,'email' => $email, 'user' => $user])); 
        }

        return url(route('admin.verifyUpdatedEmail',['id' => $id,'email' => $email, 'user' => $user])); 
        
        // this will basically mimic the email endpoint with get request
    }
}