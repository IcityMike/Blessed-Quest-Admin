<?php
/***
Developed by: Radhika Savaliya
Description: Messages used all over project
***/

// resources/lang/en/messages.php

return [
    /*** General messages ***/
    'serverError'   =>  'Server error. Please try again.',
    'invalidPhoneNumber' => 'Invalid phone number',
    'invalidEmportExportValue' => 'Emport / Export value can’t be greater than turn over value',
    'invalidData' => 'Invalid data',
    'required' => 'This value is required.',
    'invalidUrl' => 'Invalid URL',
    'nopermission' => 'You do not have permission to access this page.',
    'invalidFileFormatError' => 'Invalid file type.',
    'invalidImportExportValue' => 'Import / Export value can’t be greater than turn over value.',

    'userPolicyAssignSuccess' => 'User Insurance Policy assigned successfully.',
    'QuotationAcceptSuccess' => 'Quotation accepted successfully.',

    /*** Get Quote messages ***/
    'getQuoteAddSuccess' => 'User quotation request created successfully.',
    'getQuoteChangeStatusSuccess' => 'User quotation request status changed successfully.',

    /*** Edit Profile messages ***/
    'emailAlreadyExist' => 'This email address is already in use.',

    /*** Change password messages ***/
    'invalidCurrentPassword' => 'Invalid current Password.',
    'passwordUpdateSuccess' => 'Password updated successfully.',
    
    /*** Auth messages ***/
    'passwordRequestSuccess' => 'Password reset email sent successfully.',
    'passwordResetSuccess' => 'Password reset successfully.',
    'invalidRequest' => 'Invalid request. Please try again.',
    'emailAlreadyVerified' => 'Email account is already verified.',
    'emailVerificationSent' => 'Email verification sent successfully.',

    /*** Notification message **/
    'notificationDeleteSuccess' => 'Notifications deleted successfully.',
    
    /*** Admin CRUD messages ***/
    'adminAddSuccess' => 'Admin created successfully.',
    'adminUpdateSuccess' => 'Admin updated successfully.',
    'adminDeleteSuccess' => 'Admin deleted successfully.',
    'adminActivateSuccess' => 'Admin activated successfully.',
    'adminDeactivateSuccess' => 'Admin deactivated successfully.',

    /*** Currency Setting CRUD messages ***/
    'currencySettingAddSuccess' => 'Currency Setting created successfully.',
    'currencySettingUpdateSuccess' => 'Currency Setting updated successfully.',
    'currencySettingDeleteSuccess' => 'Currency Setting deleted successfully.',
    'currencySettingActivateSuccess' => 'Currency Setting activated successfully.',
    'currencySettingDeactivateSuccess' => 'Currency Setting deactivated successfully.',

    /*** Team member CRUD messages ***/
    'teamMemberAddSuccess' => 'Team member created successfully.',
    'teamMemberUpdateSuccess' => 'Team member updated successfully.',
    'teamMemberDeleteSuccess' => 'Team member deleted successfully.',
    'teamMemberActivateSuccess' => 'Team member activated successfully.',
    'teamMemberDeactivateSuccess' => 'Team member deactivated successfully.',

    /*** Advisor Team member CRUD messages ***/
    'advisorTeamMemberAddSuccess' => 'Team member created successfully.',
    'advisorTeamMemberUpdateSuccess' => 'Team member updated successfully.',
    'advisorTeamMemberDeleteSuccess' => 'Team member deleted successfully.',
    'advisorTeamMemberActivateSuccess' => 'Team member activated successfully.',
    'advisorTeamMemberDeactivateSuccess' => 'Team member deactivated successfully.',

    /*** Refferal Partner CRUD messages ***/
    'refferalPartnerAddSuccess' => 'Refferal partner created successfully.',
    'refferalPartnerUpdateSuccess' => 'Refferal partner updated successfully.',
    'refferalPartnerDeleteSuccess' => 'Refferal partner deleted successfully.',
    'refferalPartnerActivateSuccess' => 'Refferal partner activated successfully.',
    'refferalPartnerDeactivateSuccess' => 'Refferal partner deactivated successfully.',

     /*** Client user CRUD messages ***/
    'clientAddSuccess' => 'Client user created successfully.',
    'clientUpdateSuccess' => 'Client user updated successfully.',
    'clientDeleteSuccess' => 'Client user deleted successfully.',
    'clientActivateSuccess' => 'Client user activated successfully.',
    'clientDeactivateSuccess' => 'Client user deactivated successfully.',
    'clientAccoutDeactivated' => 'Your account is deactivated. using contact us please contact our administrator',

     /*** Advisor CRUD messages ***/
    'advisorAddSuccess' => 'Advisor created successfully.',
    'advisorUpdateSuccess' => 'Advisor updated successfully.',
    'advisorDeleteSuccess' => 'Advisor deleted successfully.',
    'advisorActivateSuccess' => 'Advisor activated successfully.',
    'advisorDeactivateSuccess' => 'Advisor deactivated successfully.',

    /*** Blog post CRUD messages ***/
    'blogPostAddSuccess' => 'Blog post created successfully.',
    'blogPostUpdateSuccess' => 'Blog post updated successfully.',
    'blogPostDeleteSuccess' => 'Blog post deleted successfully.',
    'blogPostActivateSuccess' => 'Blog post activated successfully.',
    'blogPostDeactivateSuccess' => 'Blog post deactivated successfully.',
    'blogPostFeatureSuccess' => 'Blog post marked as featured successfully.',
    'blogPostUnfeatureSuccess' => 'Blog post unmarked as featured successfully.',
    
    /*** Contact us messages ***/
    'submitTicketSuccess' => 'Your ticket is submitted successfully. One of our support representatives will contact you soon!',
    'submitTicketReplySuccess' => 'Reply submitted successfully.',
    'closed_ticket_text' => 'This ticket has been closed.',
    'supportTicketStatusUpdateSuccess' => 'Template updated successfully.',

    /*** Support ticket category CRUD messages ***/
    'supportTicketCategoryAddSuccess' => 'Support ticket category created successfully.',
    'supportTicketCategoryUpdateSuccess' => 'Support ticket category updated successfully.',
    'supportTicketCategoryDeleteSuccess' => 'Support ticket category deleted successfully.',
    'supportTicketCategoryActivateSuccess' => 'Support ticket category activated successfully.',
    'supportTicketCategoryDeactivateSuccess' => 'Support ticket category deactivated successfully.',
    
    /*** Settings messages ***/
    'settingsUpdateSuccess' => 'Settings updated successfully.',

    /*** Profile and user settings messages***/
    'profileUpdateSuccess' => 'Profile updated successfully.',
    'emailUpdateMailSentSuccess' => 'We have sent verification email on your new email address. Please verify it to update.',
    'emailUpdateSuccess' => 'Email address updated successfully',
    
    /*** Login messages ***/
    'accountDeactivatedError' => 'Account is deactivated.',
    'credentialsError' => 'Credentials does not not match our records.',

     /*** Email templates messages ***/
    'emailTemplateUpdateSuccess' => 'Email template updated successfully.',
   
    /** Contact form messages */
    'contactEmailSentSuccess' => 'Thank you for contacting. Your details has been sent, we will get back to you ASAP.',
    'contactEmailSentError' => 'Error in sending email. Please try again.',

    /*** User Enquiry messages **/
    'userEnquiryDeleteSuccess' => 'User enquiry deleted successfully.',
    'userEnquiryUpdateSuccess' => 'User enquiry updated successfully.',

    /*** Scheduled call messages **/
    'scheduledCallbackDeleteSuccess' => 'Scheduled call deleted successfully.',
    'scheduledcallUpdateSuccess' =>  'Scheduled call updated successfully.',
    'scheduledcallAddSuccess' => 'Scheduled call created successfully',


     /*** Portfolio feature messages **/
     'portfolioFeatureActivateSuccess' => 'Feature activated successfully.',
     'portfolioFeatureDeactivateSuccess' => 'Feature deactivated successfully.',

    /*** Omnilife suppliers messages **/
    'omnilifeSupplierActivateSuccess' => 'Supplier activated successfully.',
    'omnilifeSupplierDeactivateSuccess' => 'Supplier deactivated successfully.',

    /*** Role CRUD messages ***/
    'roleAddSuccess' => 'Role created successfully.',
    'roleUpdateSuccess' => 'Role updated successfully.',
    'roleDeleteSuccess' => 'Role deleted successfully.',
    'roleActivateSuccess' => 'Role activated successfully.',
    'roleDeactivateSuccess' => 'Role deactivated successfully.',
    'rolePermissionUpdateSuccess' => 'Permissions updated successfully.',

    /*** Activity log messages ***/
    'logDeleteSuccess' => 'Log deleted successfully.',
    'allLogDeleteSuccess' => 'All logs deleted successfully.',

    /*** Industry CRUD messages ***/
    'industryAddSuccess' => 'Industry created successfully.',
    'industryUpdateSuccess' => 'Industry updated successfully.',
    'industryDeleteSuccess' => 'Industry deleted successfully.',

    /*** Application messages **/
    'applicationDeleteSuccess' => 'Application deleted successfully.',
    'applicationUpdateSuccess' => 'Application updated successfully.',
    'userEnquiryConvertSuccess' => 'Enquiry converted to PIF enquiry successfully.',

    /*** Business detail CRUD messages ***/
    'businessDetailAddSuccess' => 'Business Insurance Detail created successfully.',
    'businessDetailUpdateSuccess' => 'Business Insurance Detail updated successfully.',
    'businessDetailDeleteSuccess' => 'Business Insurance Detail deleted successfully.',
    'businessDetailEndorsementSuccess' => 'Endorsement of Business Insurance Details done successfully.',


    /*** home insurance CRUD messages ***/
    'homeInsuranceAddSuccess' => 'Home Insurance Detail created successfully.',
    'homeInsuranceUpdateSuccess' => 'Home Insurance Detail updated successfully.',
    'homeInsuranceDeleteSuccess' => 'Home Insurance Detail deleted successfully.',
    'homeInsuranceEndorsementSuccess' => 'Endorsement of Home Insurance Details done successfully.',

    /*** Vehicle insurance CRUD messages ***/
    'vehicleInsuranceAddSuccess' => 'Vehicle Insurance Detail created successfully.',
    'vehicleInsuranceUpdateSuccess' => 'Vehicle Insurance Detail updated successfully.',
    'vehicleInsuranceDeleteSuccess' => 'Vehicle Insurance Detail deleted successfully.',
    'vehicleInsuranceEndorsementSuccess' => 'Endorsement of Vehicle Insurance Details done successfully.',

    /*** Travel insurance CRUD messages ***/
    'travelInsuranceAddSuccess' => 'Travel Insurance Detail created successfully.',
    'travelInsuranceUpdateSuccess' => 'Travel Insurance Detail updated successfully.',
    'travelInsuranceDeleteSuccess' => 'Travel Insurance Detail deleted successfully.',

    /*** Equipment insurance CRUD messages ***/
    'EquipmentInsuranceAddSuccess' => 'Equipment Insurance Detail created successfully.',
    'EquipmentInsuranceUpdateSuccess' => 'Equipment Insurance Detail updated successfully.',
    'EquipmentInsuranceDeleteSuccess' => 'Equipment Insurance Detail deleted successfully.',
    'EquipmentInsuranceEndorsementSuccess' => 'Endorsement of Equipment Insurance Details done successfully.',

    /*** CTP insurance CRUD messages ***/
    'ctpInsuranceAddSuccess' => 'CTP Insurance Detail created successfully.',
    'ctpInsuranceUpdateSuccess' => 'CTP Insurance Detail updated successfully.',
    'ctpInsuranceDeleteSuccess' => 'CTP Insurance Detail deleted successfully.',
    'ctpInsuranceEndorsementSuccess' => 'Endorsement of Ctp Insurance Details done successfully.',

    /*** insurance provider CRUD messages ***/
    'insuranceProviderAddSuccess' => 'Insurance Provider created successfully.',
    'insuranceProviderUpdateSuccess' => 'Insurance Provider updated successfully.',
    'insuranceProviderDeleteSuccess' => 'Insurance Provider deleted successfully.',
    

    /*** Occupation CRUD messages ***/
    'OccupationAddSuccess' => 'Occupation created successfully.',
    'OccupationUpdateSuccess' => 'Occupation updated successfully.',
    'OccupationDeleteSuccess' => 'Occupation deleted successfully.',

    /** Quotation Upload message **/
    'DocumentUploadSuccess' => 'Document uploaded successfully.',
    'DocumentUploadFail' => 'Document not uploaded successfully.',
    'clientDocumentDeleteSuccess' => 'Document deleted successfully.',
    'QuotationBindingSuccess' => 'Binding policy successfully.',
    'QuotationPaymentSuccess' => 'Payment added successfully.',
    'QuotationCocSuccess' => 'COc document uploaded successfully.',

     /*** PI insurance CRUD messages ***/
    'PiInsuranceAddSuccess' => 'Professional Idemnity Insurance Detail created successfully.',
    'PiInsuranceUpdateSuccess' => 'Professional Idemnity Insurance Detail updated successfully.',
    'PiInsuranceDeleteSuccess' => 'Professional Idemnity Insurance Detail deleted successfully.',
    'PiInsuranceEndorsementSuccess' => 'Endorsement of Professional Idemnity Insurance Details done successfully.',

    /*** Marine insurance CRUD messages ***/
    'MarineInsuranceAddSuccess' => 'Marine Insurance Detail created successfully.',
    'MarineInsuranceUpdateSuccess' => 'Marine Insurance Detail updated successfully.',
    'MarineInsuranceDeleteSuccess' => 'Marine Insurance Detail deleted successfully.',
    'MarineInsuranceEndorsementSuccess' => 'Endorsement of Marine Insurance Details done successfully.',

    /*** Claim messages **/
    'ClaimDeleteSuccess' => 'Claim deleted successfully.',
    'ClaimUpdateSuccess' => 'Claim updated successfully.',
    'ClaimAddSuccess' => 'Claim created successfully.',

    /** Cancellation Request messages**/
    'CancellationRequestRejectSuccess' => 'Cancellation request rejected successfully.',
    'CancellationRequestAcceptSuccess' => 'Cancellation request accepted successfully.',
    'CancellationSuccess' => 'Your Policy Cancelled successfully.',

    /** Endorsement Request messages**/
    'EndorsementRequestRejectSuccess' => 'Endorsement request rejected successfully.',

    /** Renewal Request messages**/
    'RenewalRequestDeclinedSuccess' => 'Renewal Declined.',

    /*** Forum post category CRUD messages ***/
    'forumPostCategoryAddSuccess' => 'Forum post category created successfully.',
    'forumPostCategoryUpdateSuccess' => 'Forum post category updated successfully.',
    'forumPostCategoryDeleteSuccess' => 'Forum post category deleted successfully.',
    'forumPostCategoryActivateSuccess' => 'Forum post category activated successfully.',
    'forumPostCategoryDeactivateSuccess' => 'Forum post category deactivated successfully.',

    /*** Forum Abusive words CRUD messages ***/
    'wordAddSuccess' => 'Word created successfully.',
    'wordUpdateSuccess' => 'Word updated successfully.',
    'wordDeleteSuccess' => 'Word deleted successfully.',

    /*** Spam Report messages ***/
    'spamReportMarkSuccess' => 'Post marked as spam successfully.',
    'spamReportDeleteSuccess' => 'Spam report deleted successfully.',
    'spamReportUnMarkSuccess' => 'Post unmarked as spam successfully.',

    /*** User spam report messages ***/
    'activateForumUserSuccess' => 'User activated for forum successfully.',
    'deactivateForumUserSuccess' => 'User deactivated for forum successfully.',

    /*** Middleware messages ***/
    'forumDeactivated' => 'You are not allowed to access forum. Please contact site administrator.',
    'subscriptionExpired' => 'Your subscription plan has been expired. Please renew it to continue access.',
    'pseudoNameMissing'=> 'Please enter pseudo name to continue access to forum.',
    'trialPeriodExpired' => 'Your trial period has been expired.',
    
    /*** Currency Settings CRUD messages ***/
    'currencySettingAddSuccess' => 'currency Setting created successfully.',
    'currencySettingUpdateSuccess' => 'currency Setting updated successfully.',
    'currencySettingDeleteSuccess' => 'currency Setting deleted successfully.',
    'currencySettingActivateSuccess' => 'currency Setting activated successfully.',
    'currencySettingDeactivateSuccess' => 'currency Setting deactivated successfully.',

    /*** beneficiary ***/
    'beneficiaryActivateSuccess' => 'beneficiary user activated successfully.',
    'beneficiaryDeactivateSuccess' => 'beneficiary user black listed successfully.',
];