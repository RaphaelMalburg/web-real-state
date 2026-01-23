<?php

use App\Http\Controllers\AdminAuthController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PropertyController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/gallery', [PropertyController::class, 'gallery'])->name('gallery');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Admin Routes (Simple for now, can add auth later)
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::post('/', [AdminController::class, 'store'])->name('store');
    Route::post('/ai/description', [AdminController::class, 'aiGenerateDescription'])->name('ai.description');
    Route::post('/ai/description-improve', [AdminController::class, 'aiImproveDescription'])->name('ai.description_improve');
    Route::post('/ai/image', [AdminController::class, 'aiGenerateImage'])->name('ai.image');
    Route::post('/ai/gallery', [AdminController::class, 'aiGenerateGallery'])->name('ai.gallery');
    Route::get('/{property}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/{property}', [AdminController::class, 'update'])->name('update');
    Route::delete('/{property}', [AdminController::class, 'destroy'])->name('destroy');
    Route::get('/{property}/delete', [AdminController::class, 'confirmDelete'])->name('delete');
});
