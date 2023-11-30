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
        Route::get('employer-dashboard', ['uses' => 'EmployerAuthController@index', 'as' => 'employer-dashboard']);
    }
    
    );
});





