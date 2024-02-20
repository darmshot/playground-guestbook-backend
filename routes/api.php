<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group([
    'middleware' => ['auth:sanctum']
], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('messages', \App\Http\Controllers\Api\MessageController::class)->except(['index']);
    Route::patch('message-answers/{id}', [\App\Http\Controllers\Api\AnswerMessageController::class, 'update']);
});

Route::get('/messages', [\App\Http\Controllers\Api\MessageController::class, 'index'])->name('messages.index');

Route::post('/payments', [\App\Http\Controllers\Api\PaymentController::class, 'store'])->name('payments.store');


