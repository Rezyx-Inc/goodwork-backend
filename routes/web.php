<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
Route::middleware(['web'])->group(function () {
    Route::get('/', ['uses' => 'SiteController@index', 'as' => '/']);
    Route::post('subscription-response', ['uses' => 'SiteController@subscription_renew', 'as' => 'subscription-response']);

    Route::get('about-us', ['uses' => 'SiteController@about_us', 'as' => 'about-us']);
    Route::get('contact-us', ['uses' => 'SiteController@contact_us', 'as' => 'contact-us']);
    Route::post('contact-us-submit', ['uses' => 'SiteController@contact_us_submit', 'as' => 'contact-us-submit']);
    Route::get('faq', ['uses' => 'SiteController@faq', 'as' => 'faq']);
    Route::get('terms', ['uses' => 'SiteController@terms', 'as' => 'terms']);
    Route::get('test', ['uses' => 'SiteController@test', 'as' => 'test']);
    Route::get('privacy-policy', ['uses' => 'SiteController@privacy_policy', 'as' => 'privacy-policy']);

    // add for_employers root
    Route::get('for-employers', ['uses' => 'SiteController@for_employers', 'as' => 'for-employers']);

    // add for_recruiters root
    Route::get('for-recruiters', ['uses' => 'SiteController@for_recruiters', 'as' => 'for-recruiters']);

    // add exploreJobs root
    Route::get('explore-jobs', ['uses' => 'SiteController@explore_jobs', 'as' => 'explore-jobs']);

    Route::post('get-states',['uses'=>'SiteController@get_state','as'=>'get-states']);
    Route::post('get-cities',['uses'=>'SiteController@get_city','as'=>'get-cities']);
    Route::post('get-speciality',['uses'=>'SiteController@get_speciality','as'=>'get-speciality']);
    Route::post('get-dropdown',['uses'=>'SiteController@get_dorpdown','as'=>'get-dropdown']);

    Route::get('clear-cache','SiteController@clear_cache');

    Route::middleware(['user_not_logged_in'])->group(function () {
        /* Registration and authentication routes */
        Route::get('signup', ['uses' => 'SiteController@signup', 'as' => 'signup']);
        Route::post('signup', ['uses' => 'SiteController@post_signup', 'as' => 'signup.store']);
        Route::get('login', ['uses' => 'SiteController@login', 'as' => 'login']);
        Route::post('login', ['uses' => 'SiteController@post_login', 'as' => 'login.store']);
        Route::get('otp', ['uses' => 'SiteController@otp', 'as' => 'otp']);
        Route::post('otp', ['uses' => 'SiteController@submit_otp', 'as' => 'otp.store']);
        Route::get('resend-otp', ['uses' => 'SiteController@resend_otp', 'as' => 'resend-otp']);
        Route::get('verify', ['uses' => 'SiteController@verify', 'as' => 'verify']);
    });

    Route::middleware(['user_logged_in'])->group(function () {
            /** Dashboard routes */
        Route::get('dashboard', ['uses' => 'DashboardController@dashboard', 'as' => 'dashboard']);
        Route::get('profile-setting', ['uses' => 'DashboardController@setting', 'as' => 'profile-setting']);
        Route::post('profile-setting', ['uses' => 'DashboardController@post_edit_profile', 'as' => 'profile-setting.store']);

        Route::post('update-password', ['uses' => 'DashboardController@post_change_password', 'as' => 'update-password']);
        // Route::get('my-profile', ['uses' => 'DashboardController@my_profile', 'as' => 'my-profile']);
        Route::get('messages', ['uses' => 'DashboardController@messages', 'as' => 'messages']);
        // Route::get('my-work-journey', ['uses' => 'DashboardController@my_work_journey', 'as' => 'my-work-journey']);
        // Route::get('explore', ['uses' => 'DashboardController@explore', 'as' => 'explore']);

        Route::get('my-profile', ['uses' => 'UserController@edit', 'as' => 'my-profile']);
            Route::post('my-profile', ['uses' => 'UserController@update', 'as' => 'my-profile.store']);
            Route::get('vaccination', ['uses' => 'UserController@edit', 'as' => 'vaccination']);
            Route::post('vaccination', ['uses' => 'UserController@vaccination_submit', 'as' => 'vaccination.store']);
            Route::get('certification', ['uses' => 'UserController@edit', 'as' => 'certification']);
            Route::post('certification', ['uses' => 'UserController@certification_submit', 'as' => 'certification.store']);
            Route::get('references', ['uses' => 'UserController@edit', 'as' => 'references']);
            Route::post('references', ['uses' => 'UserController@post_references', 'as' => 'references.store']);
            Route::get('info-required', ['uses' => 'UserController@edit', 'as' => 'info-required']);
            Route::post('info-required', ['uses' => 'UserController@skills_submit', 'as' => 'info-required.store']);
            Route::get('urgency', ['uses' => 'UserController@edit', 'as' => 'urgency']);
            Route::get('float-requirement', ['uses' => 'UserController@edit', 'as' => 'float-requirement']);
            Route::get('patient-ratio', ['uses' => 'UserController@edit', 'as' => 'patient-ratio']);
            Route::get('interview-dates', ['uses' => 'UserController@edit', 'as' => 'interview-dates']);
            Route::get('bonuses', ['uses' => 'UserController@edit', 'as' => 'bonuses']);
            Route::get('work-hours', ['uses' => 'UserController@edit', 'as' => 'work-hours']);

        Route::post('worker-upload-files', ['uses' => 'UserController@post_references', 'as' => 'worker-upload-files']);
        /** Jobs routes */
        Route::get('explore', ['uses' => 'JobController@explore', 'as' => 'explore']);
        Route::get('job/{id}/details', ['uses' => 'JobController@details', 'as' => 'job-details']);
        Route::post('add-save-jobs', ['uses' => 'JobController@add_save_jobs', 'as' => 'add-save-jobs']);
        Route::post('apply-on-job', ['uses' => 'JobController@apply_on_jobs', 'as' => 'apply-on-job']);

        Route::get('my-work-journey', ['uses' => 'JobController@my_work_journey', 'as' => 'my-work-journey']);
        Route::post('fetch-job-content', ['uses' => 'JobController@fetch_job_content', 'as' => 'fetch-job-content']);

        Route::get('jobs/applied', ['uses' => 'JobController@my_work_journey', 'as' => 'applied-jobs']);
        Route::get('jobs/offered', ['uses' => 'JobController@my_work_journey', 'as' => 'offered-jobs']);
        Route::get('jobs/hired', ['uses' => 'JobController@my_work_journey', 'as' => 'hired-jobs']);
        Route::get('jobs/past', ['uses' => 'JobController@my_work_journey', 'as' => 'past-jobs']);

        Route::get('jobs/{id}/counter-offer', ['uses' => 'JobController@counter_offer', 'as' => 'counter-offer']);
        Route::post('post-counter-offer', ['uses' => 'JobController@store_counter_offer', 'as' => 'post-counter-offer']);

        Route::get('help-center', ['uses' => 'DashboardController@help_center', 'as' => 'help-center']);
        Route::get('logout', ['uses' => 'SiteController@logout', 'as' => 'logout']);

    });
});

