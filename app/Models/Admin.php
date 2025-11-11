<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Notifications\SupportTicketReplyNotification;
use App\Notifications\SupportTicketNotification;
use App\Notifications\ResetPassword;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\PostSpamReportNotification;
use App\Notifications\BirthdayReminderEmailToAdmin;
use App\Notifications\TransferMoneyConfirmationEmailToAdmin;
use App\Notifications\NotifyUpdateEmail;
use App\Notifications\AdminWelcomeEmail;

class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'role_id','first_name',  'last_name', 'email', 'gender','phone', 'password','first_name','profile_picture','type','forum_status','monoova_to_nium_otp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $appends = ['role_name'];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function getRoleNameAttribute()
    {
       if($this->role_id)
       {
          return $this->role->name;
       }
       else
         return null;
    }
    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token,"admin"));
    }

    public function sendWelcomeEmail($admin)
    {
       $this->notify(new AdminWelcomeEmail($admin));
    }

    public function sendPasswordResetSuccessNotification($admin)
    {
       $this->notify(new ResetPasswordNotification("admin", $admin));
    }

      
    public function sendUpdateEmailNotification($admin)
    {
        $this->notify(new NotifyUpdateEmail("admin", $admin));
    }

    public function sendPostSpamReportNotification($postId, $spamReportId)
    {
       $this->notify(new PostSpamReportNotification("admin",$postId, $spamReportId));
    }
    public function sendSupportTicketReplyNotification($ticketId, $admin)
    {
       $this->notify(new SupportTicketReplyNotification("admin",$ticketId, $admin));
    }
    public function sendSupportTicketNotification($ticketId)
    {
       $this->notify(new SupportTicketNotification("admin",$ticketId));
    }

    public function sendBirthdayReminderEmail($user)
    {
       $this->notify(new BirthdayReminderEmailToAdmin($user));
    }

   public function sendAddMoneyOtpNotification($data)
   {
      $this->notify(new TransferMoneyConfirmationEmailToAdmin($data));
   }

}
