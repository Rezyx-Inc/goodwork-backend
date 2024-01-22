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
        Route::get('/worker-verify', ['uses' => 'WorkerAuthController@verify', 'as' => 'worker.verify']);
        Route::post('worker-otp', ['uses' => 'WorkerAuthController@submit_otp', 'as' => 'worker.otp']);
        Route::get('/signup', ['uses' => 'WorkerAuthController@get_signup', 'as' => 'worker-signup']);
        Route::post('signup', ['uses' => 'WorkerAuthController@post_signup', 'as' => 'worker.signup']);
    });

    Route::middleware(['user_logged_in'])->group(function () {
        Route::get('worker-logout', ['uses' => 'WorkerAuthController@logout', 'as' => 'worker-logout']);
        Route::get('worker-dashboard', ['uses' => 'WorkerController@index', 'as' => 'worker-dashboard']);
      
        Route::get(' home', ['uses' => 'WorkerController@home', 'as' => 'home']);
        Route::get('worker-messages', ['uses' => 'WorkerController@get_messages', 'as' => 'worker-messages']);
        Route::get('worker-profile', ['uses' => 'WorkerController@get_profile', 'as' => 'worker-profile']);


        Route::post('help-and-support', ['uses' => 'WorkerDashboardController@helpAndSupport', 'as' => 'worker-help-and-support']);
        Route::post('worker-update-profile', ['uses' => 'WorkerDashboardController@updateProfile', 'as' => 'worker-update-profile']);

        Route::post('worker-send-job-offer', ['uses' => 'ApplicationController@sendJobOffer', 'as' => 'worker-send-job-offer']);

    //    Route::get('worker-messages/{idEmployer}', ['uses' => 'WorkerController@get_messages', 'as' => 'worker-messages']);
      //  Route::get('worker-messages', ['uses' => 'WorkerController@get_messages', 'as' => 'worker-messages']);
        Route::get('/getMessages', ['uses'=>'WorkerController@get_private_messages', 'as'=>'getPrivateMessages']);







        //Route::post('/get-api-key',['uses'=>'WorkerController@getapikey','as'=>'getApiKey']);
    }

    );
});









