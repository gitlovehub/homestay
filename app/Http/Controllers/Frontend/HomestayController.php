<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Homestay;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
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

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $location = trim((string) $request->input('location'));

        $minPrice = $request->filled('min_price')
            ? max(0, (int) $request->input('min_price'))
            : null;

        $maxPrice = $request->filled('max_price')
            ? max(0, (int) $request->input('max_price'))
            : null;

        if (
            $minPrice !== null
            && $maxPrice !== null
            && $minPrice > $maxPrice
        ) {
            [$minPrice, $maxPrice] = [
                $maxPrice,
                $minPrice,
            ];
        }

        $guests = $request->filled('guests')
            ? max(1, (int) $request->input('guests'))
            : null;

        $minimumRating = $request->filled('rating')
            ? min(5, max(1, (int) $request->input('rating')))
            : null;

        $roomType = trim((string) $request->input('room_type'));

        $amenityIds = collect(
            $request->input('amenities', [])
        )
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $sort = (string) $request->input(
            'sort',
            'popular'
        );

        $homestaysQuery = Homestay::query()
            ->with([
                'category',
                'amenities:id,name,icon',
            ])
            ->where('homestays.status', true)

            /*
            |--------------------------------------------------------------------------
            | Chỉ lấy Homestay còn phòng hoạt động
            |--------------------------------------------------------------------------
            */

            ->whereHas('rooms', function (Builder $query) {
                $query->where(
                    'rooms.status',
                    'available'
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Giá phòng thấp nhất
            |--------------------------------------------------------------------------
            */

            ->withMin([
                'rooms as min_room_price' => function (
                    Builder $query
                ) use (
                    $minPrice,
                    $maxPrice
                ) {
                    $query->where(
                        'rooms.status',
                        'available'
                    );

                    if ($minPrice !== null) {
                        $query->where(
                            'rooms.price_per_night',
                            '>=',
                            $minPrice
                        );
                    }

                    if ($maxPrice !== null) {
                        $query->where(
                            'rooms.price_per_night',
                            '<=',
                            $maxPrice
                        );
                    }
                },
            ], 'price_per_night')

            /*
            |--------------------------------------------------------------------------
            | Số lượt đặt và đánh giá
            |--------------------------------------------------------------------------
            */

            ->withCount([
                'bookings as bookings_count' => function (Builder $query) {
                    $query->whereIn(
                        'bookings.status',
                        [
                            'confirmed',
                            'checked_in',
                            'completed',
                        ]
                    );
                },

                'reviews as approved_reviews_count' => function (Builder $query) {
                    $query->where(
                        'reviews.status',
                        'approved'
                    );
                },
            ])

            ->withAvg([
                'reviews as average_rating' => function (Builder $query) {
                    $query->where(
                        'reviews.status',
                        'approved'
                    );
                },
            ], 'rating')

            /*
            |--------------------------------------------------------------------------
            | Tìm theo tên
            |--------------------------------------------------------------------------
            */

            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery
                        ->where(
                            'homestays.name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'homestays.address',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'homestays.city',
                            'like',
                            "%{$search}%"
                        );
                });
            })

            /*
            |--------------------------------------------------------------------------
            | Tìm theo địa điểm
            |--------------------------------------------------------------------------
            */

            ->when($location !== '', function (Builder $query) use ($location) {
                $query->where(function (Builder $subQuery) use ($location) {
                    $subQuery
                        ->where(
                            'homestays.city',
                            'like',
                            "%{$location}%"
                        )
                        ->orWhere(
                            'homestays.address',
                            'like',
                            "%{$location}%"
                        );
                });
            })

            /*
            |--------------------------------------------------------------------------
            | Lọc theo khoảng giá phòng
            |--------------------------------------------------------------------------
            */

            ->when(
                $minPrice !== null || $maxPrice !== null,
                function (Builder $query) use ($minPrice, $maxPrice) {
                    $query->whereHas(
                        'rooms',
                        function (Builder $roomQuery) use ($minPrice, $maxPrice) {
                            $roomQuery->where(
                                'rooms.status',
                                'available'
                            );

                            if ($minPrice !== null) {
                                $roomQuery->where(
                                    'rooms.price_per_night',
                                    '>=',
                                    $minPrice
                                );
                            }

                            if ($maxPrice !== null) {
                                $roomQuery->where(
                                    'rooms.price_per_night',
                                    '<=',
                                    $maxPrice
                                );
                            }
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Lọc theo số khách
            |--------------------------------------------------------------------------
            */

            ->when($guests !== null, function (Builder $query) use ($guests) {
                $query->whereHas(
                    'rooms',
                    function (Builder $roomQuery) use ($guests) {
                        $roomQuery
                            ->where(
                                'rooms.status',
                                'available'
                            )
                            ->where(
                                'rooms.capacity',
                                '>=',
                                $guests
                            );
                    }
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Lọc theo loại phòng
            |--------------------------------------------------------------------------
            */

            ->when($roomType !== '', function (Builder $query) use ($roomType) {
                $query->whereHas(
                    'rooms',
                    function (Builder $roomQuery) use ($roomType) {
                        $roomQuery
                            ->where(
                                'rooms.status',
                                'available'
                            )
                            ->where(
                                'rooms.room_type',
                                $roomType
                            );
                    }
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Lọc theo tiện ích
            |--------------------------------------------------------------------------
            */

            ->when(
                !empty($amenityIds),
                function (Builder $query) use ($amenityIds) {
                    $query->whereHas(
                        'amenities',
                        function (Builder $amenityQuery) use ($amenityIds) {
                            $amenityQuery->whereIn(
                                'amenities.id',
                                $amenityIds
                            );
                        },
                        '>=',
                        count($amenityIds)
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Lọc theo điểm đánh giá
            |--------------------------------------------------------------------------
            */

            ->when(
                $minimumRating !== null,
                function (Builder $query) use ($minimumRating) {
                    $query->having(
                        'average_rating',
                        '>=',
                        $minimumRating
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Sắp xếp
        |--------------------------------------------------------------------------
        */

        switch ($sort) {
            case 'bookings_desc':
                $homestaysQuery
                    ->orderByDesc('bookings_count')
                    ->orderByDesc('average_rating');
                break;

            case 'rating_desc':
                $homestaysQuery
                    ->orderByDesc('average_rating')
                    ->orderByDesc('approved_reviews_count');
                break;

            case 'price_asc':
                $homestaysQuery
                    ->orderBy('min_room_price')
                    ->orderByDesc('average_rating');
                break;

            case 'price_desc':
                $homestaysQuery
                    ->orderByDesc('min_room_price')
                    ->orderByDesc('average_rating');
                break;

            case 'latest':
                $homestaysQuery->latest(
                    'homestays.id'
                );
                break;

            default:
                $sort = 'popular';

                $homestaysQuery
                    ->orderByDesc('bookings_count')
                    ->orderByDesc('average_rating')
                    ->orderByDesc('approved_reviews_count');
                break;
        }

        $homestays = $homestaysQuery
            ->orderByDesc('homestays.id')
            ->paginate(9)
            ->withQueryString();

        $amenities = Amenity::query()
            ->where('status', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'icon',
            ]);

        $roomTypes = Room::query()
            ->where('status', 'available')
            ->whereNotNull('room_type')
            ->where('room_type', '!=', '')
            ->distinct()
            ->orderBy('room_type')
            ->pluck('room_type');

        return view(
            'homestays.index',
            compact(
                'homestays',
                'amenities',
                'roomTypes',
                'sort'
            )
        );
    }
}