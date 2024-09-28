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

Route::prefix('organization')->group(function () {




    Route::middleware(['organization_not_logged_in'])->group(function () {
        Route::get('/login', ['uses' => 'OrganizationAuthController@get_login', 'as' => 'organization.login']);
        Route::post('organization-login', ['uses' => 'OrganizationAuthController@post_login', 'as' => 'organization-login']);
        Route::get('/verify', ['uses' => 'OrganizationAuthController@verify', 'as' => 'organization.verify']);
        Route::post('organization-otp', ['uses' => 'OrganizationAuthController@submit_otp', 'as' => 'organization.otp']);
        Route::get('/signup', ['uses' => 'OrganizationAuthController@get_signup', 'as' => 'organization-signup']);
        Route::post('signup', ['uses' => 'OrganizationAuthController@post_signup', 'as' => 'organization.signup']);
    });

    Route::middleware(['organization_logged_in'])->group(
        function () {
            Route::get('/', 'OrganizationController@index');
            Route::get('organization-logout', ['uses' => 'OrganizationAuthController@logout', 'as' => 'organization-logout']);
            Route::get('organization-dashboard', ['uses' => 'OrganizationController@index', 'as' => 'organization-dashboard']);
            Route::get('add-job', ['uses' => 'OrganizationController@addJob', 'as' => 'organization-add-job']);
            Route::post('add-job', ['uses' => 'OrganizationController@addJobStore', 'as' => 'organization-addJob.store']);
            Route::get('home', ['uses' => 'OrganizationController@home', 'as' => 'home']);
            Route::get('explore-employees', ['uses' => 'OrganizationController@explore_employees', 'as' => 'explore-employees']);
            Route::get('organization-opportunities-manager', ['uses' => 'OrganizationController@opportunities_manager', 'as' => 'organization-opportunities-manager']);
            Route::get('organization-create-job-request', ['uses' => 'OrganizationController@create_job_request', 'as' => 'organization-create-job-request']);
            Route::get('organization-messages/{idWorker}', ['uses' => 'OrganizationController@get_messages', 'as' => 'organization-messages']);
            //Route::get('messages', ['uses' => 'OrganizationController@get_messages', 'as' => 'organization-messages']);
            Route::get('/getMessages', ['uses' => 'OrganizationController@get_private_messages', 'as' => 'OrganizationgetPrivateMessages']);
            Route::get('profile', ['uses' => 'OrganizationController@get_profile', 'as' => 'organization-profile']);

            // added apis from recruiter module

            // Route::get('organization-messages', ['uses' => 'OrganizationDashboardController@communication', 'as' => 'organization-messages']);
            // Route::get('organization-profile', ['uses' => 'OrganizationDashboardController@profile', 'as' => 'organization-profile']);
            Route::post('help-and-support', ['uses' => 'OrganizationDashboardController@helpAndSupport', 'as' => 'organization-help-and-support']);
            Route::post('organization-update-profile', ['uses' => 'OrganizationDashboardController@updateProfile', 'as' => 'organization-update-profile']);
            Route::post('organization-remove-qualities', ['uses' => 'OrganizationDashboardController@organizationRemoveQualities', 'as' => 'organization-remove-qualities']);

            Route::get('organization-application', ['uses' => 'ApplicationController@application', 'as' => 'organization-application']);
            Route::post('get-application-listing', ['uses' => 'ApplicationController@getApplicationListing', 'as' => 'organization-get-application-listing']);
            // Route::get('organization-single-job/{id}', ['uses' => 'OrganizationDashboardController@getSinglejob', 'as' => 'organization-single-job']);
            Route::post('update-application-status', ['uses' => 'ApplicationController@updateApplicationStatus', 'as' => 'organization-update-application-status']);
            Route::post('organization-send-job-offer', ['uses' => 'ApplicationController@sendJobOffer', 'as' => 'organization-send-job-offer']);

            //Route::get('opportunities-manager', ['uses' => 'OpportunitiesController@index', 'as' => 'organization-opportunities-manager']);
            Route::get('organization-create-opportunity', ['uses' => 'OpportunitiesController@create', 'as' => 'organization-create-opportunity']);
            Route::post('organization-create-opportunity/{check_type}', ['uses' => 'OpportunitiesController@store', 'as' => 'organization-create-opportunity-store']);
            Route::post('get-job-listing', ['uses' => 'OpportunitiesController@getJobListing', 'as' => 'organization-get-job-listing']);



            Route::post('remove/{id}', ['uses' => 'OpportunitiesController@organizationRemoveInfo', 'as' => 'organization-remove-info']);

            Route::post('ask-organization-notification', ['uses' => 'OrganizationDashboardController@askOrganizationNotification', 'as' => 'ask-organization-notification']);

            Route::get('get-single-nurse-details/{id}', ['uses' => 'OrganizationDashboardController@getSingleNurseDetails', 'as' => 'organization-get-single-nurse-details']);

            Route::post('send-job-offer-organization', ['uses' => 'ApplicationController@sendJobOfferOrganization', 'as' => 'send-job-offer-organization']);

            //api keys
            // Route::get('keys', ['uses' => 'OrganizationController@keys', 'as' => 'organization-keys']);

            // Route::post('/get-api-key',['uses'=>'OrganizationController@getapikey','as'=>'getApiKey']);
            // Route::post('/delete_apikey',['uses'=>'OrganizationController@deleteapikey','as'=>'deleteApiKey']);

            // test messaging
            Route::post('/send-message', ['uses' => 'OrganizationController@sendMessages', 'as' => 'OrganizationSendMessage']);
            Route::get('/get-messages', ['uses' => 'OrganizationController@getMessages', 'as' => 'GetMessages']);
            Route::get('/get-rooms', ['uses' => 'OrganizationController@get_rooms', 'as' => 'GetRooms']);
        }

    );
});









