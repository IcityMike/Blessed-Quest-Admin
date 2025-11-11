<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\SettingsController;
Route::get('cache', function() {
	Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
});

Route::prefix('user')->namespace('Client')->group(function () {

});
/*** Client Authenticated Routes ***/
Route::prefix('user')->middleware(['auth:client'])->namespace('Client')->group(function () {

    Route::middleware(['IsUserVerified'])->group(function(){

    });
    
});


Route::get('terms-view',[SettingsController::class, 'terms_view'])->name('admin.terms.view');

Route::get('privacy-view',[SettingsController::class, 'privacy_view'])->name('admin.privacy.view');

Route::get('faq-view',[SettingsController::class, 'faq_view'])->name('admin.faq.view');

Route::get('refund-policy',[SettingsController::class, 'refund_policy'])->name('admin.refund.view');

Route::get('about-us',[SettingsController::class, 'about_us'])->name('admin.about_us.view');

Route::get('safety-report',[SettingsController::class, 'safety_report'])->name('admin.safety_report.view');

/*** Admin Routes ***/

include_once('user-routes/admin-routes.php');


/*** Refferal Routes ***/

include_once('user-routes/refferal-routes.php');
