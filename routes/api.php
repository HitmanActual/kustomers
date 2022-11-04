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
        Route::get('/send/{id}',[\App\Http\Controllers\Api\SiteSurvey\SiteSurveyController::class,'sendToCRM']);
    });

    Route::group(['prefix' => 'utility_bill'],function (){
        Route::get('/',[\App\Http\Controllers\Api\UtilityBill\UtilityBillController::class,'index']);
        Route::post('/upload_file',[\App\Http\Controllers\Api\UtilityBill\UtilityBillController::class,'upload_file']);
        Route::get('/send/{id}',[\App\Http\Controllers\Api\UtilityBill\UtilityBillController::class,'sendToCRM']);
    });

    Route::group(['prefix' => 'solution'],function (){
        Route::get('/',[\App\Http\Controllers\Api\Solution\SolutionController::class,'index']);
    });

    Route::group(['prefix' => 'contract'],function (){
        Route::get('/',[\App\Http\Controllers\Api\Contract\ContractController::class,'index']);
    });

    Route::group(['prefix' => 'pm'],function (){
        Route::get('/pm_users',[\App\Http\Controllers\Api\PM\PMController::class,'getPMUserById']);
        Route::get('/pm-user-by-ticket-id/{ticket_id}',[\App\Http\Controllers\Api\PM\PMController::class,'GetPMUserByTicketId']);
        Route::get('/pm_status',[\App\Http\Controllers\Api\PM\PMController::class,'getPMStatus']);
        Route::get("/pm-status-by-ticket_id/{ticket_id}",[\App\Http\Controllers\Api\PM\PMController::class,'GetPMStatusByTicketID']);
    });

    Route::group(['prefix' => 'status_finance'],function (){
        Route::get('/get_financed',[\App\Http\Controllers\Api\StatusFinance\StatusFinanceController::class,'getFinanced']);
        Route::get('/get-financed-by-ticket-id/{ticket_id}',[\App\Http\Controllers\Api\StatusFinance\StatusFinanceController::class,'getFinancedByTicketId']);
        Route::get('/get_status_sunlight/{project_id}',[\App\Http\Controllers\Api\StatusFinance\StatusFinanceController::class,'getStatusForSunlight']);
    });

    Route::group(['prefix' => 'tickets'],function (){
        Route::get("/",[\App\Http\Controllers\Api\Ticket\TicketController::class,"index"]);
        Route::get("/get-ticket-by-id/{ticket_id}",[\App\Http\Controllers\Api\Ticket\TicketController::class,"getTicketById"]);
    });


    Route::get('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
});



