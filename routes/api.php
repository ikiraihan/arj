<?php

use App\Http\Controllers\Api\EventClassController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RacerController;
use App\Http\Controllers\Api\RegistrationClassController;
use App\Http\Controllers\Api\RegulationController;
use App\Models\RegistrationClass;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);

    Route::post('/event/registration', [EventController::class, 'storeFormRegistration']);
    Route::get('/event/pendaftar/{eventId}', [EventController::class, 'indexPendaftar']);
    Route::post('/event/approval-payment/{registrationId}', [EventController::class,'approvalPayment']);
    Route::post('/event/approval-race/{registrationId}', [EventController::class,'approvalRaceStatus']);


    Route::delete('/registration/delete/{registrationId}', [EventController::class, 'destroyRegistration']);
    Route::put('/registration/change-fine/{registrationId}', [EventController::class, 'updateFineStatus']);

    Route::get('/event-classes/{eventId}', [EventClassController::class, 'index']);
    Route::post('/event-classes', [EventClassController::class, 'store']);
    Route::get('/event-classes/{id}/show', [EventClassController::class, 'show']);
    Route::put('/event-classes/{id}', [EventClassController::class, 'update']);
    Route::delete('/event-classes/{id}', [EventClassController::class, 'destroy']);

    Route::get('/event/payment/{userId}', [PaymentController::class, 'index']);
    Route::post('/event/payment/upload/{registrationId}', [PaymentController::class,'uploadPaymentProof']);

    Route::get('/registration-classes/{eventId}', [RegistrationClassController::class, 'index']);
    Route::put('/registration-classes/{id}/edit', [RegistrationClassController::class, 'update']);
    Route::get('/registration-classes/{id}/report-income', [RegistrationClassController::class, 'summary']);
    Route::get('/registration-classes/{id}/report-income-payment', [RegistrationClassController::class, 'reportIncomePayment']);

    Route::get('/registration-original/{eventId}', [RegistrationClassController::class, 'indexOriginal']);

    Route::get('/racer/select/{userId?}',[RacerController::class, 'select']);
    Route::get('/racers/{userId}', [RacerController::class, 'index']);
    Route::put('/racers/{id}', [RacerController::class, 'update']);
    Route::get('/racers/show/{id}', [RacerController::class, 'show']);
    Route::delete('/racers/{id}', [RacerController::class, 'destroy']);

    Route::get('/regulations', [RegulationController::class, 'index']);
    Route::put('/regulations/{id}', [RegulationController::class, 'update']);
    Route::get('/regulations/show/{id}', [RegulationController::class, 'show']);
    Route::delete('/regulations/{id}', [RegulationController::class, 'destroy']);

});
