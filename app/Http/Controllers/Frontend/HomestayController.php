<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomestayController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        /*
        |--------------------------------------------------------------------------
        | Lấy thông tin Homestay
        |--------------------------------------------------------------------------
        */

        $homestay = Homestay::query()
            ->with([
                'category',
                'owner',
                'amenities',
                'images',

                'rooms' => function ($query) {
                    $query
                        ->where('status', 'available')
                        ->orderBy('price_per_night');
                },
            ])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Đánh giá được phép hiển thị
        |--------------------------------------------------------------------------
        */

        $approvedReviewsQuery = $homestay
            ->reviews()
            ->where('status', 'approved');

        /*
        |--------------------------------------------------------------------------
        | Mức sao đang được chọn
        |--------------------------------------------------------------------------
        */

        $selectedRating = $request->filled('rating')
            ? (int) $request->input('rating')
            : null;

        if (!in_array($selectedRating, [1, 2, 3, 4, 5], true)) {
            $selectedRating = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Tổng số lượt đánh giá
        |--------------------------------------------------------------------------
        */

        $reviewTotal = (clone $approvedReviewsQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | Điểm đánh giá trung bình
        |--------------------------------------------------------------------------
        */

        $averageRating = $reviewTotal > 0
            ? round(
                (float) (clone $approvedReviewsQuery)->avg('rating'),
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Số lượng đánh giá theo từng mức sao
        |--------------------------------------------------------------------------
        */

        $ratingCounts = (clone $approvedReviewsQuery)
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        /*
        |--------------------------------------------------------------------------
        | Phân bố số sao
        |--------------------------------------------------------------------------
        */

        $ratingDistribution = collect(range(5, 1))
            ->mapWithKeys(function (int $star) use ($ratingCounts, $reviewTotal) {
                $count = (int) $ratingCounts->get(
                    $star,
                    0
                );

                $percentage = $reviewTotal > 0
                    ? round(($count / $reviewTotal) * 100)
                    : 0;

                return [
                    $star => [
                        'count' => $count,
                        'percentage' => $percentage,
                    ],
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | Danh sách đánh giá theo bộ lọc sao
        |--------------------------------------------------------------------------
        */

        $reviewsQuery = (clone $approvedReviewsQuery)
            ->with('user')
            ->latest();

        if ($selectedRating !== null) {
            $reviewsQuery->where(
                'rating',
                $selectedRating
            );
        }

        $reviews = $reviewsQuery
            ->paginate(
                5,
                ['*'],
                'reviews_page'
            )
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Tìm Booking đủ điều kiện đánh giá
        |--------------------------------------------------------------------------
        */

        $showReviewForm = false;
        $reviewBooking = null;

        if (auth()->check()) {
            $eligibleBookingQuery = Booking::query()
                ->where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereHas('room', function ($query) use ($homestay) {
                    $query->where(
                        'homestay_id',
                        $homestay->id
                    );
                })
                ->whereDoesntHave('reviews');

            /*
            |--------------------------------------------------------------------------
            | Sau khi đăng nhập hoặc đi từ route reviews.create
            |--------------------------------------------------------------------------
            */

            if ($request->filled('booking')) {
                $reviewBooking = (clone $eligibleBookingQuery)
                    ->whereKey(
                        $request->integer('booking')
                    )
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | Truy cập Homestay bình thường
            |--------------------------------------------------------------------------
            |
            | Tự tìm Booking hoàn thành gần nhất chưa được đánh giá.
            |
            */

            if (!$reviewBooking) {
                $reviewBooking = (clone $eligibleBookingQuery)
                    ->latest('check_out')
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | Chỉ tự mở modal khi quay lại từ luồng đăng nhập hoặc reviews.create
            |--------------------------------------------------------------------------
            */

            $showReviewForm =
                $request->boolean('write_review')
                && $reviewBooking !== null;
        }

        if (
            $request->boolean('write_review')
            && auth()->check()
            && $request->filled('booking')
        ) {
            /*
            |--------------------------------------------------------------------------
            | Không được tin trực tiếp booking_id trên URL
            |--------------------------------------------------------------------------
            |
            | Phải kiểm tra lại Booking trong database để tránh người dùng
            | sửa booking trên thanh địa chỉ.
            |
            */

            $reviewBooking = Booking::query()
                ->with([
                    'room.homestay',
                ])
                ->whereKey(
                    $request->integer('booking')
                )
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->where(
                    'status',
                    'completed'
                )
                ->whereHas(
                    'room',
                    function ($query) use ($homestay) {
                        $query->where(
                            'homestay_id',
                            $homestay->id
                        );
                    }
                )
                ->whereDoesntHave('reviews')
                ->first();

            $showReviewForm = $reviewBooking !== null;
        }

        /*
        |--------------------------------------------------------------------------
        | Trả dữ liệu sang giao diện
        |--------------------------------------------------------------------------
        */

        return view(
            'homestays.show',
            compact(
                'homestay',
                'reviews',
                'reviewTotal',
                'averageRating',
                'ratingDistribution',
                'selectedRating',
                'showReviewForm',
                'reviewBooking'
            )
        );
    }
}