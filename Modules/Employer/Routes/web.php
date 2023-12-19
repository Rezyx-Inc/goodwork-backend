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

Route::prefix('employer')->group(function() {
    Route::get('/', 'EmployerController@index');



    Route::middleware(['employer_not_logged_in'])->group(function () {
        Route::get('/login', ['uses' => 'EmployerAuthController@get_login', 'as' => 'employer.login']);
        Route::post('employer-login', ['uses' => 'EmployerAuthController@post_login', 'as' => 'employer-login']);
        Route::get('/employer-verify', ['uses' => 'EmployerAuthController@verify', 'as' => 'employer.verify']);
        Route::post('employer-otp', ['uses' => 'EmployerAuthController@submit_otp', 'as' => 'employer.otp']);
        Route::get('/signup', ['uses' => 'EmployerAuthController@get_signup', 'as' => 'employer-signup']);
        Route::post('signup', ['uses' => 'EmployerAuthController@post_signup', 'as' => 'employer.signup']);
    });

    Route::middleware(['employer_logged_in'])->group(function () {
        Route::get('employer-logout', ['uses' => 'EmployerAuthController@logout', 'as' => 'employer-logout']);
        Route::get('employer-dashboard', ['uses' => 'EmployerController@index', 'as' => 'employer-dashboard']);
        Route::get('add-job', ['uses' => 'EmployerController@addJob', 'as' => 'add-job']);
        Route::post('add-job', ['uses' => 'EmployerController@addJobStore', 'as' => 'addJob.store']);
        Route::get('home', ['uses' => 'EmployerController@home', 'as' => 'home']);
        Route::get('explore-employees', ['uses' => 'EmployerController@explore_employees', 'as' => 'explore-employees']);
        Route::get('employer-opportunities-manager', ['uses' => 'EmployerController@opportunities_manager', 'as' => 'employer-opportunities-manager']);
        Route::get('employer-create-job-request', ['uses' => 'EmployerController@create_job_request', 'as' => 'employer-create-job-request']);
        Route::get('employer-messages', ['uses' => 'EmployerController@get_messages', 'as' => 'employer-messages']);
        Route::get('employer-profile', ['uses' => 'EmployerController@get_profile', 'as' => 'employer-profile']);

        // added apis from recruiter module
        
        // Route::get('employer-messages', ['uses' => 'EmployerDashboardController@communication', 'as' => 'employer-messages']);
        // Route::get('employer-profile', ['uses' => 'EmployerDashboardController@profile', 'as' => 'employer-profile']);
        Route::post('help-and-support', ['uses' => 'EmployerDashboardController@helpAndSupport', 'as' => 'help-and-support']);
        Route::post('employer-update-profile', ['uses' => 'EmployerDashboardController@updateProfile', 'as' => 'employer-update-profile']);
        Route::post('employer-remove-qualities', ['uses' => 'EmployerDashboardController@employerRemoveQualities', 'as' => 'employer-remove-qualities']);

        Route::get('employer-application',  ['uses' => 'ApplicationController@application', 'as' => 'employer-application']);
        Route::post('get-application-listing', ['uses' => 'ApplicationController@getApplicationListing', 'as' => 'get-application-listing']);
        // Route::get('employer-single-job/{id}', ['uses' => 'EmployerDashboardController@getSinglejob', 'as' => 'employer-single-job']);
        Route::post('update-application-status', ['uses' => 'ApplicationController@updateApplicationStatus', 'as' => 'update-application-status']);
        Route::post('employer-send-job-offer', ['uses' => 'ApplicationController@sendJobOffer', 'as' => 'employer-send-job-offer']);

        Route::get('employer-opportunities-manager', ['uses' => 'OpportunitiesController@index', 'as' => 'employer-opportunities-manager']);
        Route::get('employer-create-opportunity', ['uses' => 'OpportunitiesController@create', 'as' => 'employer-create-opportunity']);
        Route::post('employer-create-opportunity/{check_type}', ['uses' => 'OpportunitiesController@store', 'as' => 'employer-create-opportunity-store']);
        Route::post('get-job-listing', ['uses' => 'OpportunitiesController@getJobListing', 'as' => 'get-job-listing']);



        Route::post('remove/{id}', ['uses' => 'OpportunitiesController@employerRemoveInfo', 'as' => 'employer-remove-info']);

        Route::post('ask-employer-notification', ['uses' => 'EmployerDashboardController@askEmployerNotification', 'as' => 'ask-employer-notification']);

        Route::get('get-single-nurse-details/{id}', ['uses' => 'EmployerDashboardController@getSingleNurseDetails', 'as' => 'get-single-nurse-details']);

        Route::post('send-job-offer-employer', ['uses' => 'ApplicationController@sendJobOfferEmployer', 'as' => 'send-job-offer-employer']);
    }

    );
});









