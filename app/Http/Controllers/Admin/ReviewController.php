<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReviewStatusRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reviewsQuery = Review::query()
            ->with([
                'user',
                'homestay',
                'booking',
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")

                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })

                        ->orWhereHas('homestay', function ($homestayQuery) use ($search) {
                            $homestayQuery
                                ->where('name', 'like', "%{$search}%");
                        })

                        ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                            $bookingQuery
                                ->where('booking_code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('rating'), function ($query) use ($request) {
                $query->where('rating', $request->rating);
            });

        $sort = (string) $request->input('sort', 'latest');

        $sortedReviewsQuery = match ($sort) {
            'oldest' => (clone $reviewsQuery)
                ->orderBy('created_at')
                ->orderBy('id'),

            'rating_desc' => (clone $reviewsQuery)
                ->orderByDesc('rating')
                ->orderByDesc('id'),

            'rating_asc' => (clone $reviewsQuery)
                ->orderBy('rating')
                ->orderBy('id'),

            default => (clone $reviewsQuery)
                ->orderByDesc('created_at')
                ->orderByDesc('id'),
        };

        $reviews = $sortedReviewsQuery
            ->paginate(10)
            ->withQueryString();

        $statistics = [
            'total' => Review::count(),

            'average_rating' => round(
                (float) Review::avg('rating'),
                1
            ),

            'pending' => Review::where('status', 'pending')->count(),

            'hidden' => Review::where('status', 'hidden')->count(),
        ];

        return view('admin.reviews.index', compact(
            'reviews',
            'statistics'
        ));
    }

    public function updateStatus(
        UpdateReviewStatusRequest $request,
        Review $review
    ) {
        $newStatus = $request->validated('status');

        $allowedTransitions = [
            'pending' => ['approved', 'hidden'],
            'approved' => ['hidden'],
            'hidden' => ['approved'],
        ];

        $currentStatus = $review->status;

        if (
            !in_array(
                $newStatus,
                $allowedTransitions[$currentStatus] ?? [],
                true
            )
        ) {
            return back()->with(
                'error',
                'Không thể chuyển đánh giá sang trạng thái này.'
            );
        }

        $review->update([
            'status' => $newStatus,
        ]);

        $messages = [
            'approved' => 'Đã duyệt đánh giá thành công.',
            'hidden' => 'Đã ẩn đánh giá thành công.',
        ];

        return back()->with(
            'success',
            $messages[$newStatus] ?? 'Cập nhật trạng thái thành công.'
        );
    }

    public function show(Review $review)
    {
        $review->load([
            'user',
            'booking.room.homestay',
            'homestay',
        ]);

        return view(
            'admin.reviews.show',
            compact('review')
        );
    }
}