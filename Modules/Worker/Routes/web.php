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
    });

    Route::middleware(['user_logged_in'])->group(function () {
        Route::get('/logout', ['uses' => 'WorkerAuthController@logout', 'as' => 'worker.logout']);
        Route::get('/dashboard', ['uses' => 'WorkerDashboardController@dashboard', 'as' => 'worker.dashboard']);
      
        //Route::get(' home', ['uses' => 'WorkerController@home', 'as' => 'home']);
        Route::get('messages', ['uses' => 'WorkerController@get_messages', 'as' => 'worker.messages']);
        Route::get('profile', ['uses' => 'WorkerDashboardController@my_profile', 'as' => 'profile']);
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
        Route::get('jobs/offered', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'offered-jobs']);
        Route::get('jobs/hired', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'hired-jobs']);
        Route::get('jobs/past', ['uses' => 'WorkerController@get_my_work_journey', 'as' => 'past-jobs']);

        Route::post('/send-message', ['uses' => 'WorkerController@sendMessages', 'as' => 'send.message']);
        Route::get('/get-messages', ['uses' => 'WorkerController@getMessages', 'as' => 'GetMessages']);
        
        // still in use ??? 
        Route::get('/get-rooms', ['uses' => 'WorkerController@get_rooms', 'as' => 'GetRooms']);
        Route::get('job/{id}/details', ['uses' => 'WorkerController@details', 'as' => 'worker_job-details']);
    }

    );
});









