<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register',[\App\Http\Controllers\Auth\AuthController::class, 'reqister_user']);
Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::post('forgot-password',[\App\Http\Controllers\Auth\AuthController::class, 'forgot_password']);
Route::get('callback_reset_password',[\App\Http\Controllers\Auth\AuthController::class, 'callback_reset']);
Route::post('reset-password',[\App\Http\Controllers\Auth\AuthController::class, 'reset_password']);




Route::group(['middleware' => 'auth:api'],function (){
    Route::group(['prefix' => 'customer'],function (){
        Route::get('/','CustomersController\CustomerController@show');
    });

    Route::group(['prefix' => 'site_survey'],function (){
        Route::get('/',[\App\Http\Controllers\Api\SiteSurvey\SiteSurveyController::class,'index']);
        Route::post('/upload_file',[\App\Http\Controllers\Api\SiteSurvey\SiteSurveyController::class,'upload_file']);
    });

    Route::group(['prefix' => 'utility_bill'],function (){
        Route::get('/',[\App\Http\Controllers\Api\UtilityBill\UtilityBillController::class,'index']);
        Route::post('/upload_file',[\App\Http\Controllers\Api\UtilityBill\UtilityBillController::class,'upload_file']);
    });

    Route::get('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
});


