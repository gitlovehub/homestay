<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Booking;
use App\Models\Homestay;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function create(Homestay $homestay): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Tìm Booking đủ điều kiện đánh giá
        |--------------------------------------------------------------------------
        */

        $booking = Booking::query()
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereHas('room', function ($query) use ($homestay) {
                $query->where(
                    'homestay_id',
                    $homestay->id
                );
            })
            ->whereDoesntHave('reviews')
            ->latest('check_out')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Không có Booking đủ điều kiện
        |--------------------------------------------------------------------------
        */

        if (!$booking) {
            return redirect(
                route('homestays.show', [
                    'slug' => $homestay->slug,
                ]) . '#reviews'
            )->with(
                    'error',
                    'Bạn cần hoàn thành chuyến lưu trú trước khi đánh giá Homestay này.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Quay lại Homestay và tự mở modal
        |--------------------------------------------------------------------------
        */

        return redirect(
            route('homestays.show', [
                'slug' => $homestay->slug,
                'write_review' => 1,
                'booking' => $booking->id,
            ]) . '#reviews'
        );
    }

    public function store(
        StoreReviewRequest $request,
        Booking $booking
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Kiểm tra Booking thuộc người đang đăng nhập
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $booking->user_id === (int) auth()->id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Lấy thông tin phòng và Homestay
        |--------------------------------------------------------------------------
        */

        $booking->loadMissing([
            'room.homestay',
        ]);

        $homestay = $booking->room?->homestay;

        if (!$homestay) {
            return redirect()
                ->route('bookings.history')
                ->with(
                    'error',
                    'Không tìm thấy Homestay của đơn đặt phòng.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Booking phải hoàn thành
        |--------------------------------------------------------------------------
        */

        if ($booking->status !== 'completed') {
            return redirect(
                route('homestays.show', [
                    'slug' => $homestay->slug,
                ]) . '#reviews'
            )->with(
                    'error',
                    'Bạn chỉ có thể đánh giá sau khi hoàn thành chuyến lưu trú.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Không cho đánh giá trùng Booking
        |--------------------------------------------------------------------------
        */

        if ($booking->reviews()->exists()) {
            return redirect(
                route('homestays.show', [
                    'slug' => $homestay->slug,
                ]) . '#reviews'
            )->with(
                    'error',
                    'Bạn đã đánh giá cho đơn đặt phòng này.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Lưu đánh giá
        |--------------------------------------------------------------------------
        */

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'homestay_id' => $homestay->id,

            'review_number' => 1,

            'rating' => $request->integer('rating'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),

            /*
            | Đánh giá cần Admin duyệt trước khi xuất hiện.
            */
            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Quay về khu vực đánh giá
        |--------------------------------------------------------------------------
        */

        return redirect(
            route('homestays.show', [
                'slug' => $homestay->slug,
            ]) . '#reviews'
        )->with(
                'success',
                'Gửi đánh giá thành công. Đánh giá đang chờ quản trị viên duyệt.'
            );
    }
}