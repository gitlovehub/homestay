<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomestayController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\HomestayController as FrontendHomestayController;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get(
    '/homestays/{slug}',
    [FrontendHomestayController::class, 'show']
)->name('homestays.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::prefix('bookings')
    ->name('bookings.')
    ->group(function () {
        Route::get(
            '/history',
            [FrontendBookingController::class, 'history']
        )->name('history');

        Route::get(
            '/room/{room}/create',
            [FrontendBookingController::class, 'create']
        )->name('create');

        Route::post(
            '/',
            [FrontendBookingController::class, 'store']
        )->name('store');

        Route::get(
            '/{booking}',
            [FrontendBookingController::class, 'show']
        )->name('show');
    });
    
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('homestays', HomestayController::class);
        Route::resource('rooms', RoomController::class);
    });

require __DIR__.'/auth.php';