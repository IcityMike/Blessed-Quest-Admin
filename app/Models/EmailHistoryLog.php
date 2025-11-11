<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmailType;



class EmailHistoryLog extends Model
{
    
	protected $dates = ['created_at'];
	public $timestamps = false;

	/*** Table associated with Model ***/
    protected $table = 'email_history_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','user_type','email_type','associated_id','associated_type', 'sent_on', 'attachments', 'content','subject', 'sent_to'
    ];
    
    public function emailType()
    {
        return $this->belongsTo(EmailType::class,'email_type');
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Add record in Email history table
    ***/

    public static function createLog( $user_id, $user_type, $associated_id, $email_type, $associate_type = NULL, $attachments = NULL, $content = NULL, $subject = NULL, $sent_to = NULL)
    {
        
    	try
    	{
            
            $data['user_id'] = $user_id;
            $data['content'] = $content;
            $data['subject'] = $subject;
            $data['sent_to'] = $sent_to;
    		$data['user_type'] = $user_type;
    		$data['associated_id'] = $associated_id;
            $data['email_type'] = $email_type;
            $data['attachments'] = json_encode($attachments);
    		$data['sent_on'] = now();
            if($associate_type)
            {
                $data['associated_type'] = $associate_type;
            }
            
    		if(self::create($data))
    			return true;
            else
                return false;
    	}
    	catch(\Exception $e)
    	{
            echo $e->getMessage(); die;
    		return false;
    	}
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Get email log history 
    ***/
    public static function getHistory($startDate = null, $endDate = null)
    {
        
        $query = self::query();
        if($startDate){
            $startDate = str_replace('/', '-', $startDate);
            $startDate = date("Y-m-d H:i:s", strtotime($startDate));
            
            $query->where('sent_on','>=',$startDate);
        }
        if($endDate){
            $endDate = str_replace('/', '-', $endDate);
            $endDate = date("Y-m-d H:i:s", strtotime($endDate." 23:59:59"));
            $query->where('sent_on','<=',$endDate);
        }
       // echo $startDate." ".$endDate." ". $query->toSql(); die;   
        $data = $query->orderBy('sent_on','desc')->get();
        
        $emailHistory = [];
        if($data)
        {
            foreach($data as $d)
            {
                $history['id'] = $d->id;
                $history['user_type'] = $d->user_type == "CAR" ? $d->user_type : config('settings.user_types_reverse.'.$d->user_type);
                $history['user_name'] = self::getUserName($d->user_type, $d->user_id);
                $history['email_type'] = EmailType::find($d->email_type)->name;
                $history['associated_item_title'] = self::findAssociatedItemTitle($d->associated_id, $d->associated_type);
                $type = config('settings.email_log_associate_types_reverse.'.$d->associated_type);
                $history['associated_item'] = $type ? $type : '-';
                $history['sent_on'] = date("d/m/Y H:i:s",strtotime($d->sent_on));
                $history['attachments'] = $d->attachments;
                $emailHistory[] = $history;
            }
        }
        
        return $emailHistory;
        
    }

     /***
    *   Developed by: Radhika Savaliya
    *   Description: Get email log history 
    ***/
    public static function getUserName($userType, $userId)
    {
      
        $name = "Unknown";
        if($userType == config('settings.user_types.client'))
        {
            $user = Client::find($userId);
            
        }
        else if($userType == config('settings.user_types.admin'))
        {
            $user = Admin::find($userId);
          
        }
        else if($userType == config('settings.user_types.referral'))
        {
            $user = ReferralPartner::find($userId);
            
        }
       
        return $name;
    }

     /***
    *   Developed by: Radhika Savaliya
    *   Description:  Find associated item title
    ***/
    public static function findAssociatedItemTitle($associatedId, $associatedType)
    {
        if($associatedType == config('settings.email_log_associate_types.support_ticket'))
        {
            $supportTicket = SupportTicket::find($associatedId);
            if($supportTicket)
                $title = strlen($supportTicket->subject) > 200 ? substr($supportTicket->subject, 0 , 200). " ..." : $supportTicket->subject;
            else
                $title = 'No longer available';
        }
        else if($associatedType == config('settings.email_log_associate_types.support_ticket_reply'))
        {
            $supportTicketReply = SupportTicketReply::find($associatedId);
            if($supportTicketReply){
                $text = $supportTicketReply->support_ticket->subject;
                $title = strlen($text) > 200 ? substr($text, 0 , 200). " ..." : $text;
            }                
            else
                $title = 'No longer available';
        }
        else if($associatedType == config('settings.email_log_associate_types.post_spam_report'))
        {
            $report = ForumPostSpamReport::find($associatedId);
            if($report){
                $text = $report->spam_report_resaon;
                $title = strlen($text) > 200 ? substr($text, 0 , 200). " ..." : $text;
            }                
            else
                $title = 'No longer available';
        }
        else if($associatedType == config('settings.email_log_associate_types.user_spam_report'))
        {
            $report = ForumUserSpamReport::find($associatedId);
            if($report){
                $text = $report->spam_report_resaon;
                $title = strlen($text) > 200 ? substr($text, 0 , 200). " ..." : $text;
            }                
            else
                $title = 'No longer available';
        }
        else
        {
            $title = '-';
        }
        return $title;
    }
}

?>