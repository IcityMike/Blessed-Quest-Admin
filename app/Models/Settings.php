<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    
	/*** Table associated with Model ***/
    protected $table = 'external_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_title','site_logo','site_favicon','email_address', 'phone_number', 'address', 'footer_logo','facebook_link', 'twitter_link', 'youtube_link', 'linkedin_link','about_banner','details_banner','about_banner_link','details_banner_link','enquiry_admin','forum_admin','support_admin','monoova_admin','monoova_public_key','monoova_certificate_file','NIUM_X_API_KEY','SWIFTID_SECRET_KEY','type_of_voice_id','default_event_id','email'
    ];
}

?>