<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('recruiter')->group(function () {
    // Route::get('/', 'RecruiterController@index');

    Route::middleware(['recruiter_not_logged_in'])->group(function () {
        // Route::get('/', 'RecruiterController@index');
        Route::get('/login', ['uses' => 'RecruiterAuthController@get_login', 'as' => 'recruiter.login']);
        Route::post('recruiter-login', ['uses' => 'RecruiterAuthController@post_login', 'as' => 'recruiter-login']);
        Route::get('/recruiter-verify', ['uses' => 'RecruiterAuthController@verify', 'as' => 'recruiter.verify']);
        Route::post('recruiter-otp', ['uses' => 'RecruiterAuthController@submit_otp', 'as' => 'recruiter.otp']);
        Route::get('/signup', ['uses' => 'RecruiterAuthController@get_signup', 'as' => 'recruiter-signup']);
        Route::post('signup', ['uses' => 'RecruiterAuthController@post_signup', 'as' => 'recruiter.signup']);
        
    });
    Route::middleware(['recruiter_logged_in'])->group(function () {
        /** Dashboard routes */
        Route::get('recruiter-dashboard', ['uses' => 'RecruiterDashboardController@index', 'as' => 'recruiter-dashboard']);
        Route::get('recruiter-messages', ['uses' => 'RecruiterDashboardController@communication', 'as' => 'recruiter-messages']);
        Route::get('recruiter-profile', ['uses' => 'RecruiterDashboardController@profile', 'as' => 'recruiter-profile']);
        Route::post('help-and-support', ['uses' => 'RecruiterDashboardController@helpAndSupport', 'as' => 'help-and-support']);
        Route::post('recruiter-update-profile', ['uses' => 'RecruiterDashboardController@updateProfile', 'as' => 'recruiter-update-profile']);
        Route::post('recruiter-remove-qualities', ['uses' => 'RecruiterDashboardController@recruiterRemoveQualities', 'as' => 'recruiter-remove-qualities']);
        
        Route::get('recruiter-application', ['uses' => 'ApplicationController@application', 'as' => 'recruiter-application']);
        Route::post('get-application-listing', ['uses' => 'ApplicationController@getApplicationListing', 'as' => 'get-application-listing']);
        // Route::get('recruiter-single-job/{id}', ['uses' => 'RecruiterDashboardController@getSinglejob', 'as' => 'recruiter-single-job']);
        Route::post('update-application-status', ['uses' => 'ApplicationController@updateApplicationStatus', 'as' => 'update-application-status']);
        Route::post('recruiter-send-job-offer', ['uses' => 'ApplicationController@sendJobOffer', 'as' => 'recruiter-send-job-offer']);
        
        Route::get('recruiter-opportunities-manager', ['uses' => 'OpportunitiesController@index', 'as' => 'recruiter-opportunities-manager']);
        Route::get('recruiter-create-opportunity', ['uses' => 'OpportunitiesController@create', 'as' => 'recruiter-create-opportunity']);
        Route::post('recruiter-create-opportunity/{check_type}', ['uses' => 'OpportunitiesController@store', 'as' => 'recruiter-create-opportunity-store']);
        Route::post('get-job-listing', ['uses' => 'OpportunitiesController@getJobListing', 'as' => 'get-job-listing']);
        
        Route::get('recruiter-logout', ['uses' => 'RecruiterAuthController@logout', 'as' => 'recruiter-logout']);
        
        Route::post('remove/{id}', ['uses' => 'OpportunitiesController@recruiterRemoveInfo', 'as' => 'recruiter-remove-info']);
        
        Route::post('ask-recruiter-notification', ['uses' => 'RecruiterDashboardController@askRecruiterNotification', 'as' => 'ask-recruiter-notification']);
        
        Route::get('get-single-nurse-details/{id}', ['uses' => 'RecruiterDashboardController@getSingleNurseDetails', 'as' => 'get-single-nurse-details']);

        Route::post('send-job-offer-recruiter', ['uses' => 'ApplicationController@sendJobOfferRecruiter', 'as' => 'send-job-offer-recruiter']);

        

    });
});
