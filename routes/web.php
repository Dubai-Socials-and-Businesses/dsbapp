<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/',[FrontController::class,'homePage'])->name('homepage');
Route::get('/gallery', [FrontController::class,'gallery'])->name('gallery');
Route::get('/events', [FrontController::class,'events'])->name('events');
Route::get('/about', [FrontController::class,'about'])->name('about');
Route::get('/contact', [FrontController::class,'contact'])->name('contact');
Route::get('/blogs', [FrontController::class,'blogs'])->name('blogs');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('/admin')->group(function(){
    Route::get('/dashboard',[HomeController::class,'adminDashboard'])->name('adminDashboard');
    Route::get('/users',[HomeController::class,'adminUsers'])->name('adminUsers');
    Route::get('/galleries',[HomeController::class,'adminGalleries'])->name('adminGalleries');
    Route::post('/gallery/add',[HomeController::class,'addGallery'])->name('addGallery');
    Route::get('/events',[HomeController::class,'adminEvents'])->name('adminEvents');
    Route::post('/event/add',[HomeController::class,'addEvent'])->name('addEvent');
    Route::get('/blogs',[HomeController::class,'adminBlogs'])->name('adminBlogs');
    Route::get('/reviews',[HomeController::class,'adminReviews'])->name('adminReviews');
    Route::post('/review/update',[HomeController::class,'updateReview'])->name('updateReview');
    Route::get('/partners',[HomeController::class,'adminPartners'])->name('adminPartners');
    Route::post('/partner/update',[HomeController::class,'updatePartner'])->name('updatePartner');
});

Route::get('/{any}', function () {
    return view('sadmin');
})->where('any', '.*$');

//Route::get('/{any}', function () {
//    return view('welcome');
//})->where('any', '^(?!api/|login$|register$|auth/|home$).*');
//
//Route::get('/{any}', function () {
//    return view('sadmin');
//})->where('any', '^(?!api|auth|home).*$');
