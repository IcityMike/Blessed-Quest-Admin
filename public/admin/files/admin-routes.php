<?php

/** Routes without login ***/
Route::prefix('administrator')->namespace('Admin')->group(function () {

    Route::get('/', 'LoginController@showLoginForm')->name('admin.loginindex');
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/do-login', 'LoginController@login')->name('admin.login');

    Route::get('/request-password', 'ForgotPasswordController@requestPassword')->name('admin.password.request');
    Route::post('/request-password-submit', 'ForgotPasswordController@requestPasswordSubmit')->name('admin.password.request-submit');
    Route::get('/reset-password/{token}', 'ForgotPasswordController@resetPassword')->name('admin.password.reset');
    Route::post('/reset-password-submit', 'ForgotPasswordController@resetPasswordSubmit')->name('admin.password.reset-submit');
    Route::get('/verify-updated-email/{id}/{email}/{user}', 'ProfileController@verifyUpdatedEmail')->name('admin.verifyUpdatedEmail');

});

/*** Admin Authenticated Routes ***/
Route::prefix('administrator')->middleware(['auth:admin','revalidate'])->namespace('Admin')->group(function (){


    Route::get('/clients/inactive/{id}', 'ClientsController@clientBlock')->name('admin.clients.block');
    Route::get('/clients/active/{id}', 'ClientsController@clientActive')->name('admin.clients.active');

    /*** Settings ***/
    Route::get('/update-settings', 'SettingsController@updateSettings')->name('settings.update');
    Route::post('/save-settings', 'SettingsController@saveSettings')->name('settings.save');

    /*** Logout ***/
    Route::get('/logout', 'LoginController@logout')->name('admin.logout');

    /*** Dashboard ***/
     Route::get('/upcoming_subscription_list', 'AdminDashboardController@upcoming_subscription_list')->name('admin.upcoming_subscription_list');
    
    Route::get('/dashboard', 'AdminDashboardController@dashboard')->name('admin.dashboard');
    Route::get('/notification-mark-as-read/{id}', 'AdminDashboardController@markAsRead')->name('admin.notifications.markAsRead');
    Route::get('/get-user-enquiry-by-year/{year}', 'AdminDashboardController@getUseEnquiryByYear')->name('admin.userEnquiries.getUseEnquiryByYear');
    
    Route::get('/advisory-dashboard', 'AdminDashboardController@advisoryDashboard')->name('admin.dashboard.advisory');

    Route::post('/find-cities', 'AdminDashboardController@findCities')->name('admin.find_cities');
    Route::post('/find-occupations', 'AdminDashboardController@findOccupations')->name('admin.find_occupations');

    Route::post('/reports', 'AdminDashboardController@getReports')->name('admin.getReports');

    /**** Profile ***/
    Route::get('/edit-profile', 'ProfileController@editProfile')->name('admin.editProfile');
    Route::post('/update-profile', 'ProfileController@updateProfile')->name('admin.updateProfile');
    Route::get('/update-email/{email}', 'ProfileController@updateEmail')->name('admin.updateEmail');
    Route::post('/check-duplicate-email', 'ProfileController@checkDuplicateEmail')->name('admin.checkDuplicateEmail');

    Route::post('/crop-profile-picture', 'ProfileController@cropProfilePicture')->name('admin.cropProfilePicture');
    Route::get('/change-password', 'ProfileController@changePassword')->name('admin.changePassword');
    Route::post('/update-password', 'ProfileController@updatePassword')->name('admin.updatePassword');


    Route::group(['middleware' => ['isSuperAdmin','hasModuleAccess']], function () {

        /** Admin Module **/
        Route::get('/admins', 'AdminController@index')->name('admin.index');
        Route::get('/admin/create', 'AdminController@createAdmin')->name('admin.create');
        Route::post('/admin/store', 'AdminController@storeAdmin')->name('admin.store');
        Route::get('/admin/list', 'AdminController@adminList')->name('admin.list');
        Route::get('/admin/edit/{id}', 'AdminController@adminEdit')->name('admin.edit');
        Route::post('/admin/update/{id}', 'AdminController@adminUpdate')->name('admin.update');
        Route::delete('/admin/destroy/{id}', 'AdminController@adminDestroy')->name('admin.destroy');
        Route::get('/admin/inactive/{id}', 'AdminController@adminBlock')->name('admin.block');
        Route::get('/admin/active/{id}', 'AdminController@adminActive')->name('admin.active');
    });

    Route::group(['middleware' => ['hasModuleAccess']], function () {
        /*** Contact Module */
        Route::get('/tickets/{status?}', 'ContactController@index')->name('admin.contact');
        Route::get('/list', 'ContactController@ticketList')->name('admin.contact.list');
        Route::get('/view-ticket/{id}', 'ContactController@viewTicket')->name('admin.viewTicket');
        Route::post('/submit-ticket-reply', 'ContactController@submitTicketReply')->name('admin.submitTicketReply');
        Route::get('/get-reply-attachments-by-id/{id}', 'ContactController@getReplyAttachmentsById')->name('admin.contact.get_reply_attachments_by_id');
        Route::get('/get-ticket-attachments-by-id/{id}', 'ContactController@getTicketAttachmentsById')->name('admin.contact.get_ticket_attachments_by_id');
        Route::post('/export-contact', 'ContactController@exportComplianceContact')->name('admin.contact.export_contact');

        //** Type of voice  **//
        Route::get('/type_of_voice', 'Types_voiceController@index')->name('admin.type_of_voice.index');
        Route::get('/type_of_voice/list', 'Types_voiceController@list')->name('admin.type_of_voice.list');
        Route::get('/view-type_of_voice/{id}', 'Types_voiceController@view')->name('admin.type_of_voice.view');
        Route::get('/type_of_voice/inactive/{id}', 'Types_voiceController@type_of_voiceBlock')->name('admin.type_of_voice.block');
        Route::get('/type_of_voice/active/{id}', 'Types_voiceController@Voice_typeActive')->name('admin.type_of_voice.active');
         Route::get('/type_of_voice/create', 'Types_voiceController@createVoice_type')->name('admin.type_of_voice.create');
        Route::post('/type_of_voice/store', 'Types_voiceController@storeVoice_type')->name('admin.type_of_voice.store');
        Route::get('/type_of_voice/edit/{id}', 'Types_voiceController@Voice_typeEdit')->name('admin.type_of_voice.edit');
        Route::post('/type_of_voice/update/{id}', 'Types_voiceController@Voice_typeUpdate')->name('admin.type_of_voice.update');
        Route::delete('/type_of_voice/destroy/{id}', 'Types_voiceController@Voice_typeDestroy')->name('admin.type_of_voice.destroy');

        ///

        Route::get('/subscription_type', 'Types_subscriptionController@index')->name('admin.subscription_type.index');
        Route::get('/subscription_type/list', 'Types_subscriptionController@list')->name('admin.subscription_type.list');
        Route::get('/view-subscription_type/{id}', 'Types_subscriptionController@view')->name('admin.subscription_type.view');
        Route::get('/subscription_type/inactive/{id}', 'Types_subscriptionController@type_of_SubscriptionBlock')->name('admin.subscription_type.block');
        Route::get('/subscription_type/active/{id}', 'Types_subscriptionController@Subscription_typeActive')->name('admin.subscription_type.active');
         Route::get('/subscription_type/create', 'Types_subscriptionController@createSubscription_type')->name('admin.subscription_type.create');
        Route::post('/subscription_type/store', 'Types_subscriptionController@storeSubscription_type')->name('admin.subscription_type.store');
        Route::get('/subscription_type/edit/{id}', 'Types_subscriptionController@Subscription_typeEdit')->name('admin.subscription_type.edit');
        Route::post('/subscription_type/update/{id}', 'Types_subscriptionController@Subscription_typeUpdate')->name('admin.subscription_type.update');
        Route::delete('/subscription_type/destroy/{id}', 'Types_subscriptionController@Subscription_typeDestroy')->name('admin.subscription_type.destroy');
  
        /** Email template Module **/
        Route::get('/email-templates', 'EmailTemplateController@index')->name('admin.emailTemplate.index');
        Route::get('/email-templates/list', 'EmailTemplateController@emailTemplateList')->name('admin.emailTemplate.list');
        Route::get('/email-templates/edit/{id}', 'EmailTemplateController@emailTemplateEdit')->name('admin.emailTemplate.edit');
        Route::post('/email-templates/update/{id}', 'EmailTemplateController@emailTemplateUpdate')->name('admin.emailTemplate.update');

            /** cms setting Module **/
        Route::get('/cms_settings', 'CmsController@index')->name('admin.cmsSetting.index');
        Route::get('/cms_settings/list', 'CmsController@CmsList')->name('admin.cmsSetting.list');

        Route::get('/cms_templates/edit/{id}', 'CmsController@CmsEdit')->name('admin.cmsSetting.edit');
        Route::post('/cms_templates/update/{id}', 'CmsController@CmsUpdate')->name('admin.cmsSetting.update');

        // FAQ 
         Route::get('faq','SettingsController@faq')->name('admin.faq');
         Route::get('faq-add','SettingsController@faq_add')->name('admin.faq.add');
         Route::get('/faq-list/list', 'SettingsController@FaqList')->name('admin.faq.list');
         Route::get('faq-save','SettingsController@faq_save')->name('admin.faq.save');
         Route::get('faq-edit/{id}','SettingsController@faq_edit')->name('admin.faq.edit');
         Route::post('faq-update/{id}','SettingsController@faq_update')->name('admin.faq.update');

    
        /** Client module **/
        Route::get('/clients', 'ClientsController@index')->name('admin.clients.index');
        Route::get('/clients/list', 'ClientsController@clientList')->name('admin.clients.list');
        Route::get('/clients/create', 'ClientsController@createClient')->name('admin.clients.create');
        Route::post('/clients/store', 'ClientsController@storeClient')->name('admin.clients.store');
        Route::get('/clients/edit/{id}', 'ClientsController@editClient')->name('admin.clients.edit');
        Route::get('/clients/view/{id}', 'ClientsController@viewClient')->name('admin.clients.view');
        Route::post('/clients/update/{id}', 'ClientsController@updateClient')->name('admin.clients.update');
        Route::delete('/clients/destroy/{id}', 'ClientsController@destroyClient')->name('admin.clients.destroy');
        Route::post('/crop-client-profile-picture', 'ClientsController@cropProfilePicture')->name('admin.clients.cropProfilePicture');
       

        Route::get('/clients/recieved-transactions/{id}', 'ClientsController@RecievedTransactions')->name('admin.clients.recievedTransactions');
        Route::get('/clients/beneficiars/{id}', 'ClientsController@getBeneficiars')->name('admin.clients.getBeneficiars');
        Route::get('/clients/sent-transactions/{id}', 'ClientsController@sentTransactions')->name('admin.clients.sentTransactions');


        /** Event Module **/
        Route::get('/event', 'EventController@index')->name('admin.event.index');
        Route::get('/event/list', 'EventController@eventList')->name('admin.event.list');
        Route::get('/event/create', 'EventController@createEvent')->name('admin.event.create');
        Route::post('/event/store', 'EventController@storeEvent')->name('admin.event.store');
        Route::get('/event/edit/{id}', 'EventController@EventEdit')->name('admin.event.edit');
        Route::post('/event/update/{id}', 'EventController@EventUpdate')->name('admin.event.update');
        Route::delete('/event/destroy/{id}', 'EventController@eventDestroy')->name('admin.event.destroy');
        Route::get('/event/inactive/{id}', 'EventController@eventInactive')->name('admin.event.inactive');
        Route::get('/event/active/{id}', 'EventController@eventActive')->name('admin.event.active');
     
        /** Subscription Module **/
        Route::get('/subscription', 'SubscriptionController@index')->name('admin.subscription.index');
        Route::get('/subscription/list', 'SubscriptionController@subscriptionList')->name('admin.subscription.list');
        Route::get('/subscription/create', 'SubscriptionController@createSubscription')->name('admin.subscription.create');
        Route::post('/subscription/store', 'SubscriptionController@storeSubscription')->name('admin.subscription.store');
        Route::get('/subscription/edit/{id}', 'SubscriptionController@SubscriptionEdit')->name('admin.subscription.edit');
        Route::post('/subscription/update/{id}', 'SubscriptionController@SubscriptionUpdate')->name('admin.subscription.update');
        Route::delete('/subscription/destroy/{id}', 'SubscriptionController@SubscriptionDestroy')->name('admin.subscription.destroy');
        Route::get('/subscription/inactive/{id}', 'SubscriptionController@SubscriptionInactive')->name('admin.subscription.inactive');
        Route::get('/subscription/active/{id}', 'SubscriptionController@SubscriptionActive')->name('admin.subscription.active');


        /**Role Module **/
        Route::get('/roles', 'RolePermissionController@index')->name('admin.role.index');
        Route::post('/roles/store', 'RolePermissionController@storeRole')->name('admin.role.store');
        Route::get('/roles/list', 'RolePermissionController@roleList')->name('admin.role.list');
        Route::delete('/roles/destroy/{id}', 'RolePermissionController@roleDestroy')->name('admin.role.destroy');
        Route::post('/roles/update', 'RolePermissionController@roleUpdate')->name('admin.role.update');
        Route::get('/roles/inactive/{id}', 'RolePermissionController@roleInactive')->name('admin.role.inactive');
        Route::get('/roles/active/{id}', 'RolePermissionController@roleActive')->name('admin.role.active');
        Route::get('/roles/edit-permissions/{id}', 'RolePermissionController@editRolePermissions')->name('admin.role.edit_role_permissions');
        Route::post('/roles/update-permissions/{id}', 'RolePermissionController@updateRolePermissions')->name('admin.role.update_role_permissions');

        /** Beneficiars module **/
        Route::get('/beneficiars', 'BeneficiarsController@index')->name('admin.beneficiars.index');
        Route::get('/beneficiars/list', 'BeneficiarsController@list')->name('admin.beneficiars.list');
        Route::get('/view-beneficiar/{id}', 'BeneficiarsController@view')->name('admin.beneficiars.view');
        Route::get('/beneficiars/inactive/{id}', 'BeneficiarsController@beneficiarBlock')->name('admin.beneficiars.block');
        Route::get('/beneficiars/active/{id}', 'BeneficiarsController@beneficiarActive')->name('admin.beneficiars.active');


        /** Library module **/
        Route::get('/library', 'LibrarysController@index')->name('admin.library.index');
        Route::get('/library/list', 'LibrarysController@list')->name('admin.library.list');
        Route::get('/view-library/{id}', 'LibrarysController@view')->name('admin.library.view');
        Route::get('/library/inactive/{id}', 'LibrarysController@libraryBlock')->name('admin.library.block');
        Route::get('/library/active/{id}', 'LibrarysController@libraryActive')->name('admin.library.active');

         Route::get('/library/create', 'LibrarysController@createlibrary')->name('admin.library.create');
        Route::post('/library/store', 'LibrarysController@storelibrary')->name('admin.library.store');
        Route::get('/library/edit/{id}', 'LibrarysController@libraryEdit')->name('admin.library.edit');
        Route::post('/library/update/{id}', 'LibrarysController@libraryUpdate')->name('admin.library.update');
        Route::delete('/library/destroy/{id}', 'LibrarysController@libraryDestroy')->name('admin.library.destroy');
      

         /** Transactions module **/

        Route::get('/transactions', 'TransactionsController@index')->name('admin.transactions.index');
        Route::get('/transactions/list', 'TransactionsController@list')->name('admin.transactions.list');
        Route::get('/view-transaction/{id}', 'TransactionsController@view')->name('admin.transactions.view');
        Route::post('/export-transactions', 'TransactionsController@exportTransactions')->name('admin.transactions.export');

        /**transaction recieved history**/
        Route::get('/recieved-transactions', 'TransactionsController@RecievedIndex')->name('admin.RecievedTransactions.index');
        Route::get('/recieved-transactions/list', 'TransactionsController@RecievedList')->name('admin.RecievedTransactions.list');
        Route::get('/view-recieved-transaction/{id}', 'TransactionsController@RecievedView')->name('admin.RecievedTransactions.view');
        Route::post('/export-recieved-transactions', 'TransactionsController@exportReceivedTransactions')->name('admin.RecievedTransactions.export');

    
        /*** Activity log module  ***/
        Route::get('/activity-log/{id?}', 'ActivityLogController@index')->name('admin.activity_log.index');
        Route::post('/activity-log-export', 'ActivityLogController@exportLog')->name('admin.activity_log.export');

        Route::get('/activity-log-list/', 'ActivityLogController@getActivityLogList')->name('admin.activity_log.list');

        Route::delete('/delete-all-activity-log/', 'ActivityLogController@deleteAll')->name('admin.activity_log.deleteAll');

        Route::delete('delete-activity-log/{id}','ActivityLogController@deleteLog')->name('admin.activity_log.delete');
    });

   
    /*** Upload image ***/
    Route::post('/upload-image', 'ImageController@uploadImage')->name('admin.image.upload');

       
});


?>
