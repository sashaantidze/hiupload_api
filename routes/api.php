<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileLinkController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\StripeIntentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserPlanAvailabilityController;
use App\Http\Controllers\UserUsageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;



Route::get('/user', UserController::class);
Route::get('/user/usage', UserUsageController::class);
Route::get('/files', [FileController::class, 'index']);
Route::get('/plans', PlanController::class);
Route::get('/subscriptions/intent', StripeIntentController::class);
Route::get('/user/plan_availability', UserPlanAvailabilityController::class);
Route::get('/files/{file:uuid}/links', [FileLinkController::class, 'show']);


Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class);
Route::post('/files', [FileController::class, 'store']);
Route::post('/files/signed', [FileController::class, 'signed']);
Route::post('/subscriptions', [SubscriptionController::class, 'store']);
Route::post('/files/{file:uuid}/links', [FileLinkController::class, 'store']);


Route::patch('/subscriptions', [SubscriptionController::class, 'update']);


Route::delete('/files/{file:uuid}', [FileController::class, 'destroy']);

