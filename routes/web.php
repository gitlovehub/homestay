<?php

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomestayController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\VnpayController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\HomestayController as FrontendHomestayController;
use App\Http\Controllers\Frontend\HomestaySearchController;
use App\Http\Controllers\Frontend\ReviewController as FrontendReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROUTE CÔNG KHAI
|--------------------------------------------------------------------------
*/

// --- Trang chủ ---
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// --- Trang giới thiệu ---
Route::view('/about', 'about')
    ->name('about');

// --- Trang liên hệ ---
Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');

// --- Tìm Homestay còn phòng ---
Route::get(
    '/search',
    [HomestaySearchController::class, 'index']
)->name('homestays.search');

// --- Danh mục ---
Route::get(
    '/categories',
    [FrontendCategoryController::class, 'index']
)->name('categories.index');

Route::get(
    '/categories/{category:slug}',
    [FrontendCategoryController::class, 'show']
)->name('categories.show');

// --- Homestay ---
Route::get(
    '/homestays',
    [FrontendHomestayController::class, 'index']
)->name('homestays.index');

Route::get(
    '/homestays/{slug}',
    [FrontendHomestayController::class, 'show']
)->name('homestays.show');

/*
|--------------------------------------------------------------------------
| VNPAY TRẢ KẾT QUẢ VỀ WEBSITE
|--------------------------------------------------------------------------
|
| Route này phải nằm ngoài middleware auth vì VNPAY sẽ chuyển người dùng
| quay lại đường dẫn này sau khi hoàn thành thanh toán.
|
*/

Route::get(
    '/payments/vnpay/return',
    [PaymentController::class, 'vnpayReturn']
)->name('payments.vnpay.return');

Route::get(
    '/payments/vnpay/return',
    [VnpayController::class, 'returnUrl']
)->name('payments.vnpay.return');

Route::get(
    '/payments/vnpay/ipn',
    [VnpayController::class, 'ipn']
)->name('payments.vnpay.ipn');

/*
|--------------------------------------------------------------------------
| ROUTE CẦN ĐĂNG NHẬP
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // --- Profile ---
    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

    // --- Booking của khách hàng ---
    Route::prefix('bookings')
        ->name('bookings.')
        ->group(function () {

            // Lịch sử đặt phòng
            Route::get(
                '/history',
                [FrontendBookingController::class, 'history']
            )->name('history');

            // Trang tạo đơn đặt phòng
            Route::get(
                '/room/{room}/create',
                [FrontendBookingController::class, 'create']
            )
                ->whereNumber('room')
                ->name('create');

            // Lưu đơn đặt phòng
            Route::post(
                '/',
                [FrontendBookingController::class, 'store']
            )->name('store');

            // Chi tiết đơn đặt phòng
            Route::get(
                '/{booking}',
                [FrontendBookingController::class, 'show']
            )
                ->whereNumber('booking')
                ->name('show');
        });

    // --- Thanh toán VNPAY ---

    // Hiển thị trang thanh toán
    Route::get(
        '/bookings/{booking}/payment',
        [PaymentController::class, 'checkout']
    )
        ->whereNumber('booking')
        ->name('payments.checkout');

    // Tạo giao dịch và chuyển sang VNPAY
    Route::post(
        '/bookings/{booking}/payment/vnpay',
        [PaymentController::class, 'createVnpayPayment']
    )
        ->whereNumber('booking')
        ->name('payments.vnpay.create');

    // --- Review của khách hàng ---

    Route::get(
        '/homestays/{homestay:slug}/reviews/create',
        [FrontendReviewController::class, 'create']
    )->name('reviews.create');

    Route::post(
        '/bookings/{booking}/reviews',
        [FrontendReviewController::class, 'store']
    )
        ->whereNumber('booking')
        ->name('reviews.store');

    // --- Gửi liên hệ ---
    // Chỉ người đã đăng nhập mới gửi được liên hệ
    Route::post(
        '/contact',
        [ContactController::class, 'store']
    )->name('contact.store');
});

/*
|--------------------------------------------------------------------------
| ROUTE DÀNH CHO ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // --- Dashboard ---
        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        // --- Quản lý tài khoản ---
        Route::get(
            '/users',
            [UserController::class, 'index']
        )->name('users.index');

        Route::get(
            '/users/{user}',
            [UserController::class, 'show']
        )
            ->whereNumber('user')
            ->name('users.show');

        Route::patch(
            '/users/{user}/status',
            [UserController::class, 'updateStatus']
        )
            ->whereNumber('user')
            ->name('users.update-status');

        // --- Quản lý Category ---
        Route::resource(
            'categories',
            CategoryController::class
        );

        // --- Quản lý Amenity ---
        Route::resource(
            'amenities',
            AmenityController::class
        );

        // --- Quản lý Homestay ---
        Route::resource(
            'homestays',
            HomestayController::class
        );

        // --- Quản lý Room ---
        Route::resource(
            'rooms',
            RoomController::class
        );

        // --- Quản lý Booking ---
        Route::get(
            '/bookings',
            [BookingController::class, 'index']
        )->name('bookings.index');

        Route::get(
            '/bookings/{booking}',
            [BookingController::class, 'show']
        )
            ->whereNumber('booking')
            ->name('bookings.show');

        Route::patch(
            '/bookings/{booking}/status',
            [BookingController::class, 'updateStatus']
        )
            ->whereNumber('booking')
            ->name('bookings.update-status');

        // --- Quản lý Review ---
        Route::get(
            '/reviews',
            [ReviewController::class, 'index']
        )->name('reviews.index');

        Route::get(
            '/reviews/{review}',
            [ReviewController::class, 'show']
        )
            ->whereNumber('review')
            ->name('reviews.show');

        Route::patch(
            '/reviews/{review}/status',
            [ReviewController::class, 'updateStatus']
        )
            ->whereNumber('review')
            ->name('reviews.update-status');
    });

require __DIR__ . '/auth.php';