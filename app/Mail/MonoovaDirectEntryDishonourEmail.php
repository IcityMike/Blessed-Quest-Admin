<?php
  
namespace App\Mail;
   
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use App\Models\EmailTemplate;
use App\Models\Settings;
use Carbon\Carbon;

  
class MonoovaDirectEntryDishonourEmail extends Mailable
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
      
      
    }

    
}