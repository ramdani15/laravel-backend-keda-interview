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
Route::name('api.v1.')
->namespace('Api\\V1')
->prefix('v1')
->group(function () {
    // Auth
    Route::name('auth.')->prefix('auth')->group(function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('register', 'AuthController@register')->name('register');
    });

    Route::middleware('auth:api')->group(function () {
        // Customers
        Route::apiResource('customers', 'CustomerController')->only(['index', 'destroy'])->middleware('isStaff');

        // Reports
        Route::get('reports', 'ReportController@index')->name('reports.index')->middleware('isStaff');
        Route::post('reports', 'ReportController@store')->name('reports.store')->middleware('isCustomer');

        // Chats
        Route::apiResource('chats', 'ChatController')->only(['index', 'store']);

        // Messages
        Route::get('messages/{chatId}', 'MessageController@index')->name('messages.index');
        Route::post('messages/{chatId}', 'MessageController@store')->name('messages.store');
    });
});
