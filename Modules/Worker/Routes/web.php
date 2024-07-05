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

Route::prefix('worker')->group(function() {
    Route::get('/', 'WorkerController@index');




    Route::middleware(['user_not_logged_in'])->group(function () {
        Route::get('/login', ['uses' => 'WorkerAuthController@get_login', 'as' => 'worker.login']);
        Route::post('worker-login', ['uses' => 'WorkerAuthController@post_login', 'as' => 'worker-login']);
        Route::get('/verify', ['uses' => 'WorkerAuthController@verify', 'as' => 'worker.verify']);
        Route::post('worker-otp', ['uses' => 'WorkerAuthController@submit_otp', 'as' => 'worker.otp']);
        Route::get('/signup', ['uses' => 'WorkerAuthController@get_signup', 'as' => 'worker-signup']);
        Route::post('worker-signup', ['uses' => 'WorkerAuthController@post_signup', 'as' => 'worker.signup']);
        Route::get('resend-otp', ['uses' => 'WorkerAuthController@resend_otp', 'as' => 'worker.resend-otp']);

    });

    Route::middleware(['user_logged_in'])->group(function () {
        Route::get('/logout', ['uses' => 'WorkerAuthController@logout', 'as' => 'worker.logout']);
        Route::get('/dashboard', ['uses' => 'WorkerDashboardController@dashboard', 'as' => 'worker.dashboard']);

        //Route::get(' home', ['uses' => 'WorkerController@home', 'as' => 'home']);
        Route::get('messages', ['uses' => 'WorkerController@get_messages', 'as' => 'worker.messages']);
        Route::get('profile/{type}', ['uses' => 'WorkerDashboardController@my_profile', 'as' => 'profile']);
        Route::get('explore', ['uses' => 'WorkerDashboardController@explore', 'as' => 'worker.explore']);
        Route::post('help-and-support', ['uses' => 'WorkerDashboardController@helpAndSupport', 'as' => 'worker-help-and-support']);
        Route::post('worker-update-profile', ['uses' => 'WorkerDashboardController@updateProfile', 'as' => 'worker-update-profile']);

        Route::post('worker-send-job-offer', ['uses' => 'ApplicationController@sendJobOffer', 'as' => 'worker-send-job-offer']);

      //  Route::get('worker-messages/{idEmployer}', ['uses' => 'WorkerController@get_messages', 'as' => 'worker-messages']);
       // Route::get('worker-messages', ['uses' => 'WorkerController@get_messages', 'as' => 'worker-messages']);
        Route::get('/getMessages', ['uses'=>'WorkerController@get_private_messages', 'as'=>'getPrivateMessages']);
        Route::get('my-work-journey', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'worker.my-work-journey']);


        //Route::get('my-work-journey', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'my-work-journey']);
        Route::post('fetch-job-content', ['uses' => 'WorkerController@fetch_job_content', 'as' => 'fetch-job-content']);

        Route::get('jobs/applied', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'applied-jobs']);
        Route::get('jobs/saved', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'saved-jobs']);
        Route::get('jobs/offered', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'offered-jobs']);
        Route::get('jobs/hired', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'hired-jobs']);
        Route::get('jobs/past', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'past-jobs']);

        Route::post('/send-message', ['uses' => 'WorkerController@sendMessages', 'as' => 'send.message']);
        Route::get('/get-messages', ['uses' => 'WorkerController@getMessages', 'as' => 'GetMessages']);

        // still in use ???
        Route::get('/get-rooms', ['uses' => 'WorkerController@get_rooms', 'as' => 'GetRooms']);
        Route::get('job/{id}/details', ['uses' => 'WorkerController@details', 'as' => 'worker_job-details']);

        // accept offer
        Route::post('accept-offer',['uses'=>'WorkerController@accept_offer', 'as'=>'accept-offer']);

        // reject offer
        Route::post('reject-offer',['uses'=>'WorkerController@reject_offer', 'as'=>'reject-offer']);

        // counter offer

        Route::post('post-counter-offer', ['uses' => 'WorkerDashboardController@store_counter_offer', 'as' => 'post-counter-offer']);

        // new post route for profile updating
        Route::post('update-worker-profile', ['uses' => 'WorkerDashboardController@update_worker_profile', 'as' => 'update-worker-profile']);


        // new post route for account setting updating
        Route::post('update-worker-account-setting',['uses' => 'WorkerDashboardController@update_worker_account_setting', 'as' => 'update-worker-account-setting']);

        // new post route for worker payment
        Route::post('add-worker-payment',['uses' => 'WorkerDashboardController@add_worker_payment', 'as' => 'add_worker_payment']);

        // sending support tickets
        Route::post('send-support-ticket',['uses' => 'WorkerDashboardController@send_support_ticket', 'as' => 'send_support_ticket']);

        // disactivate account
        Route::post('disactivate-account',['uses' => 'WorkerDashboardController@disactivate_account', 'as' => 'disactivate_account']);

        // add stripe account
        Route::post('add-stripe-account',['uses'=>'WorkerDashboardController@add_stripe_account','as'=>'add_stripe_account']);

        // check onboarding status
        Route::post('check-onboarding-status',['uses'=>'WorkerDashboardController@check_onboarding_status','as'=>'check_onboarding_status']);

        // redirecting to login

        Route::post('login-to-stripe-account',['uses'=>'WorkerDashboardController@login_to_stripe_account','as'=>'login_to_stripe_account']);

        // add saved job
        Route::post('add-save-jobs', ['uses' => 'WorkerDashboardController@add_save_jobs', 'as' => 'add-save-jobs']);

        // apply on job
        Route::post('apply-on-job', ['uses' => 'WorkerDashboardController@apply_on_jobs', 'as' => 'apply-on-job']);

        // reading message notification
        Route::post('read-message-notification', ['uses' => 'WorkerController@read_message_notification', 'as' => 'read-message-notification']);

        //reading offer notification
        Route::post('read-offer-notification', ['uses' => 'WorkerController@read_offer_notification', 'as' => 'read-offer-notification']);
        // add doc
         Route::post('add-docs', ['uses' => 'WorkerController@addDocuments', 'as' => 'add-docs']);

        // delete doc
        Route::post('del-doc', ['uses' => 'WorkerController@deleteDoc', 'as' => 'del-doc']);

        // list docs
        Route::post('list-docs', ['uses' => 'WorkerController@listDocs', 'as' => 'list-docs']);

        // update worker profile picture
        Route::post('update-worker-profile-picture', ['uses' => 'WorkerDashboardController@update_worker_profile_picture', 'as' => 'update-worker-profile-picture']);

        Route::post('certification', ['uses' => 'WorkerController@certification_submit', 'as' => 'worker.certification']);
        Route::post('vaccination', ['uses' => 'WorkerController@vaccination_submit', 'as' => 'worker.vaccination']);

    }

    );
});









