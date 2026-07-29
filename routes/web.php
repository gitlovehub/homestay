<?php

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomestayController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\HomestayController as FrontendHomestayController;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use Illuminate\Support\Facades\Route;

// ROUTE CÔNG KHAI (Public Routes)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get(
    '/homestays/{slug}',
    [FrontendHomestayController::class, 'show']
)->name('homestays.show');

// ROUTE CẦN ĐĂNG NHẬP (Authenticated Routes)
Route::middleware('auth')->group(function () {

    // Profile (Thông tin cá nhân)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bookings (Đặt phòng của người dùng)
    Route::prefix('bookings')->name('bookings.')->group(function () {
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

// ROUTE DÀNH CHO ADMIN (Admin Routes)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // --- Dashboard Admin ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Quản lý tài khoản ---
    Route::get(
        '/users',
        [UserController::class, 'index']
    )->name('users.index');

    Route::get(
        '/users/{user}',
        [UserController::class, 'show']
    )->name('users.show');

    Route::patch(
        '/users/{user}/status',
        [UserController::class, 'updateStatus']
    )->name('users.update-status');

    // --- Quản lý Category ---
    Route::resource('categories', CategoryController::class);

    // --- Quản lý Amenity ---
    Route::resource('amenities', AmenityController::class);

    // --- Quản lý Homestay ---
    Route::resource('homestays', HomestayController::class);
    
    // --- Quản lý Room ---
    Route::resource('rooms', RoomController::class);

    // --- Quản lý Booking ---
    Route::get(
        '/bookings',
        [BookingController::class, 'index']
    )->name('bookings.index');

    Route::get(
        '/bookings/{booking}',
        [BookingController::class, 'show']
    )->name('bookings.show');

    Route::patch(
        '/bookings/{booking}/status',
        [BookingController::class, 'updateStatus']
    )->name('bookings.update-status');

    // --- Quản lý Review ---
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    
    Route::patch('/reviews/{review}/status', [ReviewController::class, 'updateStatus']
    )->name('reviews.update-status');

    Route::get('/reviews/{review}', [ReviewController::class, 'show']
    )->name('reviews.show');

});

require __DIR__ . '/auth.php';