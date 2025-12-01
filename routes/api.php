<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AuthController;
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

Route::get('/homeapis',[FrontController::class,'homeApis']);
Route::get('/blogs',[FrontController::class,'blogsApi']);
Route::get('/blog/{slug}',[FrontController::class,'getBlogBySlug']);
Route::get('/events',[FrontController::class,'eventsApi']);
Route::get('/event/{slug}',[FrontController::class,'getEventBySlug']);
Route::get('/galleries',[FrontController::class,'galleryApi']);
Route::get('/partners',[FrontController::class,'partnersApi']);
Route::get('/partner/{id}',[FrontController::class,'getPartnerById']);

Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
