<?php

use Illuminate\Http\Request;

/* 
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'namespace' => 'API'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::post('forgotpassword', 'AuthController@ForgotPassword');
    Route::post('verify-otp', 'AuthController@verifyOtp');
     Route::post('verifyOtpforlogin', 'AuthController@verifyOtpforlogin');
    Route::post('change-password', 'AuthController@changePassword');

    Route::get('currency-settings','CommonDetailController@currencySettings');
    Route::get('GetVoiceTypeList','CommonDetailController@GetVoiceTypeList');

    Route::post('reminders_before_credits_expire_cron','CommonDetailController@reminders_before_credits_expire_cron');
    Route::post('reminders_before_subscription_auto_renews_cron','CommonDetailController@reminders_before_subscription_auto_renews_cron');

    // login with google and facebook
    Route::post('loginWithGoogle','AuthController@loginWithGoogle');
    Route::post('loginWithRegister','AuthController@loginWithRegister');
    Route::post('resetPassword','AuthController@resetPassword');
    
    Route::post('social_login_register','AuthController@social_login_register');
    Route::post('social_register','AuthController@social_register');
        
//
    // currency converter routes

    Route::group([
      'middleware' => ['auth:api','APIToken']
    ], function() {
        // device Registration api route
        Route::get('deleteAccount', 'AuthController@deleteAccount');

        Route::post('paymentStore', 'UserController@paymentStore');

        Route::post('deviceRegister', 'DeviceController@registerDevice');
        Route::post('deviceUnregister', 'DeviceController@unregisterDevice');
        Route::get('Profile_img_delete','UserController@Profile_img_delete');

        Route::post('update_user_voice_type','UserController@update_user_voice_type');

        Route::post('prayer_like_unlike','UserController@prayer_like_unlike');  
        Route::post('sendPhoneNumberOtp','AuthController@sendPhoneNumberOtp');
        
        Route::post('verifyEmailAddress','AuthController@verifyEmailAddress');
        Route::post('verifyPhoneNumber','AuthController@verifyPhoneNumber');

        Route::post('emailVerificationR
            esendOTP','AuthController@emailVerificationResendOTP');
        //for web
        Route::post('phoneNoVerificationResendOTP','AuthController@phoneNoVerificationResendOTP');

        Route::get('logout', 'AuthController@logout');
        
        Route::post('deactivate-account','AuthController@deactivateUser');
        Route::get('getProfile', 'UserController@getProfile');
        Route::post('updateEmail', 'UserController@updateEmail');

        Route::post('updateprofile', 'UserController@updateProfile');
        Route::post('uploadprofilepicture','UserController@uploadprofilepicture');

        // notification routes
        
        Route::get('default_profile_picture_get','AuthController@default_profile_picture_get');

        // get beneficiary detail form field
        Route::get('get-beneficiaryDetail-form-field','CommonDetailController@getBeneficiaryDetail');
        // search user api
        Route::post('send-sendMoney-otp','CommonDetailController@sendMoneyOTP');
        Route::post('verify-send-sendMoney-otp','CommonDetailController@VerifysendMoneyOTP');
        Route::post('get-members','CommonDetailController@getMembers');
        Route::post('get-recent-members','CommonDetailController@getRecentMembers');

        // Location 
        Route::post('location-add','UserController@location_add');
        Route::get('location-list','UserController@location_list');
        Route::post('location-remove','UserController@location_remove');

        // list library song
        Route::post('library-song-list','UserController@library_song_list');
        Route::post('library_song_list_without_user','UserController@library_song_list_without_user');
        Route::post('library_song_list_like','UserController@library_song_list_like');
        Route::post('Getnotifications','UserController@Getnotifications');
        Route::post('notificationsRead','UserController@notificationsRead');
        Route::get('event_user_list','UserController@event_user_list');

        Route::post('set_as_library','UserController@set_as_library');

        // payment api
        Route::get('subscription_list','UserController@subscription_list');
        Route::post('subscription_details','UserController@subscription_details');
        Route::get('user_subscription_details','UserController@user_subscription_details');
        Route::post('cancel_subscription','UserController@cancel_subscription');
        Route::post('endDate_subscription','UserController@endDate_subscription');
        Route::post('add-transactions','UserController@addTransactions');

        Route::post('blessed_journey_store','UserController@blessed_journey_store');
        Route::post('blessed_journey_store_backend','UserController@blessed_journey_store_backend'); // for test
        
        Route::post('blessed_journey_list','UserController@blessed_journey_list');
        Route::post('blessed_remove','UserController@blessed_remove');

        Route::post('review_rattings_store','UserController@review_rattings_store');

        Route::post('user_noti_settings_update','UserController@user_noti_settings_update');
        Route::get('user_noti_settings_update_list','UserController@user_noti_settings_update_list');

        Route::post('default_prayer_update_store','UserController@default_prayer_update_store');
        Route::get('notification_count','UserController@notification_count');

    });

});


