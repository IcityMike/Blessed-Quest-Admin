<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\SupportTicketNotification;
use App\Notifications\SupportTicketReplyNotification;
use App\Notifications\ClientWelcomeEmail;
use App\Notifications\ClientSwiftWelcomeEmail; 
use App\Notifications\NotifyUpdateEmail;
use App\Notifications\UserForgotPasswordEmail;
use App\Notifications\UserTransferMoneyConfirmationEmail;
use App\Notifications\UserEmailVerificationEmail;
use App\Notifications\UserresendEmailVerificationEmail;
use \DateTime;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use Notifiable;

    protected $guard = 'client';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','first_name','last_name', 'email','date_of_birth','phone_number','country_id','state','city','postal_address_line1','postal_address_line2','gender','profile_picture','referral_code','email_verified_at','password','remember_token','status','reffered_by','google_id','facebook_id','created_at','updated_at','deleted_at','postal_address','login_type','otp','account_balance','bank_account_number','bsb_number','PayId','PayIdName','PayIdStatus','bank_account_name','clientUniqueId','isEmailVerifed','isPhoneNumberVerified','email_verification_otp','phone_no_verification_otp','swift_user_type','swift_user_uniqueId','swift_user_status','individual_properties','company_properties','joint_properties','corporatetrust_properties','individualtrust_properties','language','default_img_id','type_of_voice','default_prayer_id'
    ];
    protected $appends = ['dob_formatted'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function beneficiaries()
    {
        return $this->hasMany(ClientBeneficiaries::class,'user_id');
    }

    public function getDobFormattedAttribute()
    {
        return ($this->date_of_birth) ? date("d/m/Y",strtotime($this->date_of_birth)) : '';
    }
    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token,"client"));
    }

    public function sendPasswordResetSuccessNotification($client)
    {
      // $this->notify(new ResetPasswordNotification("client", $client));

        $this->notify(new ResetPasswordNotification("client", $client));
    }
    public function sendSupportTicketReplyNotification($ticketId, $client)
    {
       $this->notify(new SupportTicketReplyNotification("client",$ticketId, $client));
    }
    public function sendWelcomeNotification($data)
    {
        $this->notify(new ClientWelcomeEmail($data));
    }
    public function sendSwiftWelcomeNotification($data)
    {
        $this->notify(new ClientSwiftWelcomeEmail($data));
    }
    public function sendUpdateEmailNotification($client)
    {
        $this->notify(new NotifyUpdateEmail("client", $client));
    }

    public function sendForgotPasswordOtpNotification($data)
    {
        $this->notify(new UserForgotPasswordEmail($data));
    }

    public function sendTransferMoneyOtpNotification($data)
    {
        $this->notify(new UserTransferMoneyConfirmationEmail($data));
    }

    public function sendEmailVerificationOtpNotification($data)
    {
        $this->notify(new UserEmailVerificationEmail($data));
    }

    public function sendEmailresendVerificationOtpNotification($data)
    {
        $this->notify(new UserresendEmailVerificationEmail($data));
    }

    public function getAgeAttribute()
    {
        if($this->date_of_birth != "")
        {
            $from = new DateTime($this->date_of_birth);
            $to   = new DateTime('today');
            return $from->diff($to)->y;
        }
        else
            return null;
       
    }
    public function referBy()
    {
        return $this->belongsTo(ReferralPartner::class,'reffered_by');
    }

    public function userDevice()
    {
        return $this->hasOne(UserDevice::class);
    }
}
