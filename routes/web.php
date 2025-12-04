<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\StripeWebhookController;

Route::get('/', function () {
    return view('auth.login');
});

//Route::get('/',[FrontController::class,'homePage'])->name('homepage');
//Route::get('/gallery', [FrontController::class,'gallery'])->name('gallery');
//Route::get('/events', [FrontController::class,'events'])->name('events');
//Route::get('/about', [FrontController::class,'about'])->name('about');
//Route::get('/contact', [FrontController::class,'contact'])->name('contact');
//Route::get('/blogs', [FrontController::class,'blogs'])->name('blogs');

Route::post('/stripe/webhook', [StripeWebhookController::class,'webhook']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('/admin')->middleware('auth')->group(function(){
    Route::get('/dashboard',[HomeController::class,'adminDashboard'])->name('adminDashboard');
    Route::get('/users',[HomeController::class,'adminUsers'])->name('adminUsers');
    Route::get('/galleries',[HomeController::class,'adminGalleries'])->name('adminGalleries');
    Route::post('/gallery/add',[HomeController::class,'addGallery'])->name('addGallery');
    Route::get('/gallery/edit/{id}',[HomeController::class,'getAdminGalleryByid'])->name('getAdminGalleryByid');
    Route::post('/gallery/edit',[HomeController::class,'editGallery'])->name('editGallery');
    Route::get('/events',[HomeController::class,'adminEvents'])->name('adminEvents');
    Route::post('/event/add',[HomeController::class,'addEvent'])->name('addEvent');
    Route::get('/event/edit/{id}',[HomeController::class,'getAdminEventById'])->name('getAdminEventById');
    Route::post('/event/edit',[HomeController::class,'editEvent'])->name('editEvent');
    Route::get('/blogs',[HomeController::class,'adminBlogs'])->name('adminBlogs');
    Route::post('/blog/add',[HomeController::class,'addBlog'])->name('addBlog');
    Route::get('/blog/edit/{id}',[HomeController::class,'getAdminBlogById'])->name('getAdminBlogById');
    Route::post('/blog/edit',[HomeController::class,'editBlog'])->name('editBlog');
    Route::get('/reviews',[HomeController::class,'adminReviews'])->name('adminReviews');
    Route::post('/review/update',[HomeController::class,'updateReview'])->name('updateReview');
    Route::get('/partners',[HomeController::class,'adminPartners'])->name('adminPartners');
    Route::post('/partner/update',[HomeController::class,'updatePartner'])->name('updatePartner');
    Route::get('/packages',[HomeController::class,'adminPackages'])->name('adminPackages');
    Route::post('/package/update',[HomeController::class,'updatePackage'])->name('updatePackage');
});

Route::post('/admin/logout', [HomeController::class, 'adminLogout'])->name('admin.logout');

Route::get('/{any}', function () {
    return view('sadmin');
})->where('any', '.*$')->middleware('auth');

//Route::get('/{any}', function () {
//    return view('welcome');
//})->where('any', '^(?!api/|login$|register$|auth/|home$).*');
//
//Route::get('/{any}', function () {
//    return view('sadmin');
//})->where('any', '^(?!api|auth|home).*$');
