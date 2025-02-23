<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthApi\AuthApiController;
use Modules\Recruiter\Http\Controllers\OpportunitiesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/recruiter', 'AuthApiController@getUser');

Route::middleware('auth:api')->get('/recruiter', [AuthApiController::class, 'getUser']);


Route::get('/cities/{id}', [OpportunitiesController::class, 'get_cities']);