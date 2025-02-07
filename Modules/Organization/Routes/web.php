<?php

use Illuminate\Support\Facades\Route;


Route::prefix('organization')->group(function () {


    Route::middleware(['organization_not_logged_in'])->group(function () {

        Route::get('/login', ['uses' => 'OrganizationAuthController@get_login', 'as' => 'organization.login']);
        Route::post('organization-login', ['uses' => 'OrganizationAuthController@post_login', 'as' => 'organization-login']);
        Route::get('/organization-verify', ['uses' => 'OrganizationAuthController@verify', 'as' => 'organization.verify']);
        Route::post('organization-otp', ['uses' => 'OrganizationAuthController@submit_otp', 'as' => 'organization.otp']);
        Route::get('/signup', ['uses' => 'OrganizationAuthController@get_signup', 'as' => 'organization-signup']);
        Route::post('signup', ['uses' => 'OrganizationAuthController@post_signup', 'as' => 'organization.signup']);
        Route::get('resend-otp', ['uses' => 'OrganizationAuthController@resend_otp', 'as' => 'organization.resend-otp']);

    });
    Route::middleware(['organization_logged_in'])->group(function () {
        Route::get('organization-dashboard', ['uses' => 'OrganizationDashboardController@index', 'as' => 'organization-dashboard']);
        Route::get('organization-profile/{type}', ['uses' => 'OrganizationDashboardController@profile', 'as' => 'organization-profile']);
        Route::post('help-and-support', ['uses' => 'OrganizationDashboardController@helpAndSupport', 'as' => 'organization-help-and-support']);
        Route::post('organization-update-profile', ['uses' => 'OrganizationDashboardController@updateProfile', 'as' => 'organization-update-profile']);
        Route::post('organization-remove-qualities', ['uses' => 'OrganizationDashboardController@organizationRemoveQualities', 'as' => 'organization-remove-qualities']);
        Route::get('organization-application',  ['uses' => 'OrganizationApplicationController@application', 'as' => 'organization-application']);
        Route::post('get-application-listing', ['uses' => 'OrganizationApplicationController@getApplicationListing', 'as' => 'organization-get-application-listing']);
        Route::post('update-application-status', ['uses' => 'OrganizationApplicationController@updateApplicationStatus', 'as' => 'organization-update-application-status']);

        Route::post('organization-send-job-offer', ['uses' => 'OrganizationApplicationController@sendJobOffer', 'as' => 'organization-send-job-offer']);

        Route::get('organization-opportunities-manager', ['uses' => 'OrganizationOpportunitiesController@index', 'as' => 'organization-opportunities-manager']);
        Route::post('load-more-jobs', ['uses' => 'OrganizationOpportunitiesController@load_more_jobs', 'as' => 'organization-opportunities-manager-load-more-jobs']);

        Route::get('organization-create-opportunity', ['uses' => 'OrganizationOpportunitiesController@create', 'as' => 'organization-create-opportunity']);
        Route::post('organization-create-opportunity/{check_type}', ['uses' => 'OrganizationOpportunitiesController@hide_job', 'as' => 'organization-create-opportunity-store']);
        Route::post('get-job-listing', ['uses' => 'OrganizationOpportunitiesController@getJobListing', 'as' => 'organization-get-job-listing']);

        Route::get('organization-logout', ['uses' => 'OrganizationAuthController@logout', 'as' => 'organization-logout']);

        Route::post('remove/{id}', ['uses' => 'OrganizationOpportunitiesController@organizationRemoveInfo', 'as' => 'organization-remove-info']);

        Route::post('ask-organization-notification', ['uses' => 'OrganizationDashboardController@askorganizationNotification', 'as' => 'ask-organization-notification']);

        Route::get('get-single-nurse-details/{id}', ['uses' => 'OrganizationDashboardController@getSingleNurseDetails', 'as' => 'organization-get-single-nurse-details']);

        Route::post('accept-reject-job-offer', ['uses' => 'OrganizationApplicationController@AcceptOrRejectJobOffer', 'as' => 'organization-accept-reject-job-offer']);

        Route::get('/getMessages', ['uses'=>'OrganizationController@get_private_messages', 'as'=>'organizationgetPrivateMessages']);
        Route::get('organization-messages', ['uses' => 'OrganizationController@get_messages', 'as' => 'organization-messages']);

        Route::post('/send-message', ['uses' => 'OrganizationController@sendMessages', 'as' => 'organizationSendMessage']);

        Route::get('add-job', ['uses' => 'OrganizationController@addJob', 'as' => 'organization-add-job']);
        Route::post('add-job', ['uses' => 'OrganizationController@addJobStore', 'as' => 'organizationaddJob']);

        Route::post('/send-otp', ['uses' => 'OrganizationController@sendOtp', 'as' => 'sendOtp']) ;
        Route::post('/update-email', ['uses' => 'OrganizationController@updateEmail', 'as' => 'updateEmail']);
        
         // disactivate account
         Route::post('disactivate-account',['uses' => 'OrganizationDashboardController@disactivate_account', 'as' => 'organization-disactivate_account']);

        // new post route for profile updating
        Route::post('update-organization-profile', ['uses' => 'OrganizationDashboardController@update_organization_profile', 'as' => 'update-organization-profile']);

        // new post route for account setting updating
        Route::post('update-organization-account-setting',['uses' => 'OrganizationDashboardController@update_organization_account_setting', 'as' => 'update-organization-account-setting']);

        // sending support tickets
        Route::post('send-support-ticket',['uses' => 'OrganizationDashboardController@send_support_ticket', 'as' => 'organization-send_support_ticket']);

        // Send amount

        Route::post('check-stripe',['uses' => 'OrganizationDashboardController@check_stripe', 'as' => 'organization-check_stripe']);

        //edit job
        Route::post('get-job-to-edit', ['uses' => 'OrganizationController@get_job_to_edit', 'as' => 'organization-get_job_to_edit']);

        Route::post('edit-job', ['uses' => 'OrganizationController@edit_job', 'as' => 'organization-edit_job']);

        // reading message notification
        Route::post('read-organization-message-notification', ['uses' => 'OrganizationController@read_organization_message_notification', 'as' => 'read-organization-message-notification']);

        // reading job notification
        Route::post('read-organization-job-notification', ['uses' => 'OrganizationController@read_organization_job_notification', 'as' => 'read-organization-job-notification']);

        // reading offer notification
        Route::post('read-organization-offer-notification', ['uses' => 'OrganizationController@read_organization_offer_notification', 'as' => 'read-organization-offer-notification']);

        // update-organization-profile-image

        Route::post('update-organization-profile-image', ['uses' => 'OrganizationDashboardController@update_organization_profile_image', 'as' => 'update-organization-profile-image']);

        // get offer information

        Route::get('get-offer-information', ['uses' => 'OrganizationApplicationController@get_offer_information', 'as'=> 'organization-get-offer-information']);

        // get_offer_information_for_edit

        Route::get('get-offer-information-for-edit', ['uses' => 'OrganizationApplicationController@get_offer_information_for_edit', 'as'=> 'organization-get-offer-information-for-edit']);

        // updateJobOffer

        Route::post('update-job-offer', ['uses' => 'OrganizationApplicationController@update_job_offer', 'as'=> 'organization-update-job-offer']);

        // get offer list by type

        Route::get('get-offers-by-type', ['uses' => 'OrganizationApplicationController@get_offers_by_type', 'as'=> 'organization-get-offers-by-type']);

        // get offers of each worker

        Route::get('get-offers-of-each-worker', ['uses' => 'OrganizationApplicationController@get_offers_of_each_worker', 'as'=> 'organization-get-offers-of-each-worker']);

        // get offers of each worker

        Route::get('get-one-offer-information', ['uses' => 'OrganizationApplicationController@get_one_offer_information', 'as'=> 'organization-get-one-offer-information']);

        // counter offer of each worker

        Route::post('organization-counter-offer', ['uses' => 'OrganizationApplicationController@organization_counter_offer', 'as'=> 'organization-counter-offer']);

          //api keys
        Route::get('keys', ['uses' => 'OrganizationController@keys', 'as' => 'organization-keys']);

        Route::post('/get-api-key',['uses'=>'OrganizationController@getapikey','as'=>'getApiKey']);
        Route::post('/delete_apikey',['uses'=>'OrganizationController@deleteapikey','as'=>'deleteApiKey']);

        // recruiters management
        Route::get('recruiters-management', ['uses' => 'OrganizationController@recruiters_management', 'as' => 'organization-recruiters-management']);

        Route::post('recruiter-registration', ['uses' => 'OrganizationController@recruiter_registration', 'as' => 'recruiter_registration']);

        // delete recruiter
        Route::post('delete-recruiter', ['uses' => 'OrganizationController@delete_recruiter', 'as' => 'delete_recruiter']);

        // get One recruiter data 
        Route::post('get-recruiter-data', ['uses' => 'OrganizationController@get_recruiter_data', 'as' => 'get_recruiter_data']);

        // update recruiter data 
        Route::post('recruiter-edit', ['uses' => 'OrganizationController@recruiter_edit', 'as' => 'recruiter_edit']);

        // get fields names & rules

        Route::get('get-preferences',['uses'=>'OrganizationController@get_preferences','as'=>'get_preferences']);

        // update fields rules 

        Route::post('add-preferences',['uses'=>'OrganizationController@add_preferences', 'as' =>'add_preferences']);

        // assign recruiter to job

        Route::post('assign-recruiter-to-job',['uses'=>'OrganizationController@assign_recruiter_to_job','as'=>'assign_recruiter_to_job']);

        // list docs
        Route::post('list-docs', ['uses' => 'OrganizationApplicationController@listDocs', 'as' => 'organization-list-worker-docs']);

        // get one doc
        Route::post('get-doc', ['uses' => 'OrganizationApplicationController@getDoc', 'as' => 'organization-get-worker-docs']);

    });
});
