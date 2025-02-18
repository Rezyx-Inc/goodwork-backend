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

Route::prefix('worker')->group(function () {

    Route::get('/', 'WorkerController@index');

    // Handle fallback
    Route::fallback(['uses'=> 'WorkerController@fallback']);

    Route::middleware(['user_not_logged_in'])->group(function () {
        Route::get('/login', ['uses' => 'WorkerAuthController@get_login', 'as' => 'worker.login']);
        Route::post('worker-login', ['uses' => 'WorkerAuthController@post_login', 'as' => 'worker-login']);
        Route::get('/verify', ['uses' => 'WorkerAuthController@verify', 'as' => 'worker.verify']);
        Route::post('worker-otp', ['uses' => 'WorkerAuthController@submit_otp', 'as' => 'worker.otp']);
        Route::get('/signup', ['uses' => 'WorkerAuthController@get_signup', 'as' => 'worker-signup']);
        Route::post('worker-signup', ['uses' => 'WorkerAuthController@post_signup', 'as' => 'worker.signup']);
        Route::get('resend-otp', ['uses' => 'WorkerAuthController@resend_otp', 'as' => 'worker.resend-otp']);

    });

    Route::middleware(['user_logged_in'])->group(
        function () {
            Route::get('/logout', ['uses' => 'WorkerAuthController@logout', 'as' => 'worker.logout']);
            Route::get('/dashboard', ['uses' => 'WorkerDashboardController@dashboard', 'as' => 'worker.dashboard']);

            //Route::get(' home', ['uses' => 'WorkerController@home', 'as' => 'home']);
            Route::get('messages', ['uses' => 'WorkerController@get_messages', 'as' => 'worker.messages']);
            Route::get('profile/{type}', ['uses' => 'WorkerDashboardController@my_profile', 'as' => 'profile']);
            Route::get('explore', ['uses' => 'WorkerDashboardController@explore', 'as' => 'worker.explore']);
            Route::post('explore', ['uses' => 'WorkerDashboardController@explore', 'as' => 'worker.exploreSearch']);
            Route::post('help-and-support', ['uses' => 'WorkerDashboardController@helpAndSupport', 'as' => 'worker-help-and-support']);
            Route::post('worker-update-profile', ['uses' => 'WorkerDashboardController@updateProfile', 'as' => 'worker-update-profile']);

            Route::post('worker-send-job-offer', ['uses' => 'ApplicationController@sendJobOffer', 'as' => 'worker-send-job-offer']);

            //  Route::get('worker-messages/{idOrganization}', ['uses' => 'WorkerController@get_messages', 'as' => 'worker-messages']);
            // Route::get('worker-messages', ['uses' => 'WorkerController@get_messages', 'as' => 'worker-messages']);
            Route::get('/getMessages', ['uses' => 'WorkerController@get_private_messages', 'as' => 'WorkergetPrivateMessages']);
            Route::get('my-work-journey', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'worker.my-work-journey']);

            Route::post('/send-otp-worker', ['uses' => 'WorkerAuthController@sendOtp_worker', 'as' => 'sendOtp-worker']) ;
            Route::post('/update-email-worker', ['uses' => 'WorkerAuthController@updateEmail_worker', 'as' => 'updateEmail-worker']);

            //Route::get('my-work-journey', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'my-work-journey']);
            Route::post('fetch-job-content', ['uses' => 'WorkerController@fetch_job_content', 'as' => 'worker-fetch-job-content']);

            Route::get('jobs/applied', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'applied-jobs']);
            Route::get('jobs/saved', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'saved-jobs']);
            Route::get('jobs/offered', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'offered-jobs']);
            Route::get('jobs/hired', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'hired-jobs']);
            Route::get('jobs/past', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'past-jobs']);

            Route::post('/send-message', ['uses' => 'WorkerController@sendMessages', 'as' => 'send.message']);
            Route::get('/get-messages', ['uses' => 'WorkerController@getMessages', 'as' => 'worker-GetMessages']);

            // still in use ???
            Route::get('/get-rooms', ['uses' => 'WorkerController@get_rooms', 'as' => 'worker-GetRooms']);
            Route::get('job/{id}/details', ['uses' => 'WorkerController@details', 'as' => 'worker_job-details']);

            // accept offer
            Route::post('accept-offer', ['uses' => 'WorkerController@accept_offer', 'as' => 'accept-offer']);

            // reject offer
            Route::post('reject-offer', ['uses' => 'WorkerController@reject_offer', 'as' => 'reject-offer']);

            // counter offer

            Route::post('post-counter-offer', ['uses' => 'WorkerDashboardController@store_counter_offer', 'as' => 'post-counter-offer']);

            // new post route for profile updating
            Route::post('update-worker-profile', ['uses' => 'WorkerDashboardController@update_worker_profile', 'as' => 'update-worker-profile']);


            // new post route for account setting updating
            Route::post('update-worker-account-setting', ['uses' => 'WorkerDashboardController@update_worker_account_setting', 'as' => 'update-worker-account-setting']);

            // new post route for worker payment
            Route::post('add-worker-payment', ['uses' => 'WorkerDashboardController@add_worker_payment', 'as' => 'add_worker_payment']);

            // sending support tickets
            Route::post('send-support-ticket', ['uses' => 'WorkerDashboardController@send_support_ticket', 'as' => 'worker-send_support_ticket']);

            // disactivate account
            Route::post('disactivate-account', ['uses' => 'WorkerDashboardController@disactivate_account', 'as' => 'worker-disactivate_account']);

            // add stripe account
            Route::post('add-stripe-account', ['uses' => 'WorkerDashboardController@add_stripe_account', 'as' => 'add_stripe_account']);

            // check onboarding status
            Route::post('check-onboarding-status', ['uses' => 'WorkerDashboardController@check_onboarding_status', 'as' => 'check_onboarding_status']);

            // redirecting to login

            Route::post('login-to-stripe-account', ['uses' => 'WorkerDashboardController@login_to_stripe_account', 'as' => 'login_to_stripe_account']);

            // add saved job
            Route::post('add-save-jobs', ['uses' => 'WorkerDashboardController@add_save_jobs', 'as' => 'worker-add-save-jobs']);

            // apply on job
            Route::post('apply-on-job', ['uses' => 'WorkerDashboardController@apply_on_jobs', 'as' => 'apply-on-job']);

            // thanks-for-applying
            Route::get('thanks-for-applying', ['uses' => 'WorkerDashboardController@thanks_for_applying', 'as' => 'thanks-for-applying']);

            // reading message notification
            Route::post('read-message-notification', ['uses' => 'WorkerController@read_message_notification', 'as' => 'read-message-notification']);

            //reading offer notification
            Route::post('read-offer-notification', ['uses' => 'WorkerController@read_offer_notification', 'as' => 'read-offer-notification']);

            // add documents
            Route::post('add-docs', ['uses' => 'WorkerController@addDocuments', 'as' => 'add-docs']);

            // delete doc
            Route::post('del-doc', ['uses' => 'WorkerController@deleteDoc', 'as' => 'del-doc']);

            // list docs
            Route::post('list-docs', ['uses' => 'WorkerController@listDocs', 'as' => 'list-docs']);

            // get one doc
            Route::post('get-doc', ['uses' => 'WorkerController@getDoc', 'as' => 'get-doc']);

            // update worker profile picture
            Route::post('update-worker-profile-picture', ['uses' => 'WorkerDashboardController@update_worker_profile_picture', 'as' => 'update-worker-profile-picture']);

            Route::post('certification', ['uses' => 'WorkerController@certification_submit', 'as' => 'worker.certification']);
            Route::post('vaccination', ['uses' => 'WorkerController@vaccination_submit', 'as' => 'worker.vaccination']);

            // matching worker information with job information

            Route::post('match-worker-job', ['uses' => 'WorkerController@match_worker_job', 'as' => 'match-worker-job']);
            // Route::post('my-profile', ['uses' => 'UserController@update', 'as' => 'my-profile.store']);

            // get offers by type

            Route::get('worker-get-offers-by-type', ['uses' => 'WorkerController@get_offers_by_type', 'as'=> 'worker-get-offers-by-type']);

            // get offers of each organization

            Route::get('get-offers-of-each-organization', ['uses' => 'WorkerController@get_offers_of_each_organization', 'as'=> 'get-offers-of-each-organization']);

            // get one offer information

            Route::get('worker-get-one-offer-information', ['uses' => 'WorkerController@get_one_offer_information', 'as'=> 'worker-get-one-offer-information']);

              // get offer information

            Route::get('worker-get-offer-information', ['uses' => 'WorkerController@get_offer_information', 'as'=> 'worker-get-offer-information']);

            // counter offer of each organization

            Route::post('worker-counter-offer', ['uses' => 'WorkerController@worker_counter_offer', 'as'=> 'worker-counter-offer']);

            // update worker offer stateus
            Route::post('worker-update-application-status', ['uses' => 'WorkerController@updateApplicationStatus', 'as' => 'worker-update-application-status']);

            Route::post('worker-accept-reject-job-offer', ['uses' => 'WorkerController@AcceptOrRejectJobOffer', 'as' => 'worker-accept-reject-job-offer']);

            // update worker information

            Route::post('worker-update-information', ['uses' => 'WorkerController@worker_update_information', 'as' => 'worker-update-information']);


        }

    );
});









