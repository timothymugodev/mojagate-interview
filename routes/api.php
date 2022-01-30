<?php

use App\Http\Controllers\MojagateSmsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix" => "v1"], function(){
    Route::get('/authenticate', [MojagateSmsController::class, 'authenticate'])->name('authenticate');
    Route::get('/balance',[MojagateSmsController::class, 'checkBalance'])->name('check-balance');
    Route::post('/sms-webhook', [MojagateSmsController::class, 'webHookCallBack'])->name('sms-webhook');
    Route::post('/sms', [MojagateSmsController::class, 'sendSMS'])->name('send-sms');
});