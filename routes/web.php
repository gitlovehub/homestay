<?php

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomestayController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Frontend\HomestaySearchController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\HomestayController as FrontendHomestayController;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use App\Http\Controllers\Frontend\ReviewController as FrontendReviewController;
use Illuminate\Support\Facades\Route;

// ROUTE CÔNG KHAI (Public Routes)

// --- Trang chủ ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// Trang giới thiệu
Route::view('/about', 'about')->name('about');

// Ai cũng xem được trang liên hệ
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// --- Tìm Homestay còn phòng ---
Route::get(
    '/search',
    [HomestaySearchController::class, 'index']
)->name('homestays.search');

// --- Danh mục ---
Route::get('/categories', [FrontendCategoryController::class, 'index'])
    ->name('categories.index');
Route::get(
    '/categories/{category:slug}',
    [FrontendCategoryController::class, 'show']
)->name('categories.show');

// --- Homestays ---
Route::get(
    '/homestays/{slug}',
    [FrontendHomestayController::class, 'show']
)->name('homestays.show');
Route::get(
    '/homestays',
    [FrontendHomestayController::class, 'index']
)->name('homestays.index');

// ROUTE CẦN ĐĂNG NHẬP (Authenticated Routes)
Route::middleware('auth')->group(function () {

    // Profile (Client)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bookings (Client)
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

    // Reviews (Client)
    Route::get(
        '/homestays/{homestay:slug}/reviews/create',
        [FrontendReviewController::class, 'create']
    )->name('reviews.create');

    Route::post(
        '/bookings/{booking}/reviews',
        [FrontendReviewController::class, 'store']
    )->name('reviews.store');

    // Chỉ người đã đăng nhập mới gửi được
    Route::post('/contact', [ContactController::class, 'store'])
        ->name('contact.store');

});

// ROUTE DÀNH CHO ADMIN (Admin Routes)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // --- Dashboard ---
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

    // --- Quản lý liên hệ ---
    Route::get(
        '/contact-messages',
        [ContactMessageController::class, 'index']
    )->name('contact-messages.index');

    Route::get(
        '/contact-messages/{contactMessage}',
        [ContactMessageController::class, 'show']
    )->name('contact-messages.show');

});

require __DIR__ . '/auth.php';