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
        abort_unless(
            (int) $booking->user_id === (int) auth()->id(),
            403
        );

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

        if ($booking->status !== 'completed') {
            return redirect()
                ->route('bookings.history')
                ->with(
                    'error',
                    'Bạn chỉ có thể đánh giá sau khi hoàn thành chuyến lưu trú.'
                );
        }

        if ($booking->reviews()->exists()) {
            return redirect()
                ->route('bookings.history')
                ->with(
                    'error',
                    'Bạn đã đánh giá cho đơn đặt phòng này.'
                );
        }

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'homestay_id' => $homestay->id,

            'review_number' => 1,

            'rating' => $request->integer('rating'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),

            'status' => 'approved',
            'edited_at' => null,
        ]);

        return redirect()
            ->route('bookings.history')
            ->with(
                'success',
                'Gửi đánh giá thành công.'
            );
    }

    public function update(
        StoreReviewRequest $request,
        Review $review
    ): RedirectResponse {
        abort_unless(
            (int) $review->user_id === (int) auth()->id(),
            403
        );

        if ((int) $review->review_number >= 2) {
            return redirect()
                ->route('bookings.history')
                ->with(
                    'error',
                    'Bạn đã sử dụng hết số lần sửa đánh giá.'
                );
        }

        $review->update([
            'review_number' =>
                (int) $review->review_number + 1,

            'rating' => $request->integer('rating'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),

            'status' => 'approved',
            'edited_at' => now(),
        ]);

        return redirect()
            ->route('bookings.history')
            ->with(
                'success',
                'Sửa đánh giá thành công.'
            );
    }

}