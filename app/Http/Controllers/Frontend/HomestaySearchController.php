<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchAvailableHomestayRequest;
use App\Models\Amenity;
use App\Models\Homestay;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class HomestaySearchController extends Controller
{
    public function index(
        SearchAvailableHomestayRequest $request
    ): View {
        /*
        |--------------------------------------------------------------------------
        | Điều kiện tìm kiếm chính
        |--------------------------------------------------------------------------
        */

        $validated = $request->validated();

        $selectedLocation = trim(
            (string) ($validated['location'] ?? '')
        );

        $checkIn = $validated['check_in'];
        $checkOut = $validated['check_out'];

        /*
        |--------------------------------------------------------------------------
        | Điều kiện lọc phụ
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) ($validated['search'] ?? '')
        );

        $minPrice = isset($validated['min_price'])
            ? max(0, (int) $validated['min_price'])
            : null;

        $maxPrice = isset($validated['max_price'])
            ? max(0, (int) $validated['max_price'])
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

        $guests = isset($validated['guests'])
            ? max(1, (int) $validated['guests'])
            : null;

        $roomType = trim(
            (string) ($validated['room_type'] ?? '')
        );

        $minimumRating = isset($validated['rating'])
            ? min(5, max(1, (int) $validated['rating']))
            : null;

        $amenityIds = collect(
            $validated['amenities'] ?? []
        )
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $sort = (string) (
            $validated['sort'] ?? 'popular'
        );

        /*
        |--------------------------------------------------------------------------
        | Trạng thái Booking đang giữ phòng
        |--------------------------------------------------------------------------
        |
        | Booking cancelled và completed không chặn phòng.
        |
        */

        $blockingBookingStatuses = [
            'pending',
            'confirmed',
            'checked_in',
        ];

        /*
        |--------------------------------------------------------------------------
        | Phòng phải thỏa mãn đồng thời tất cả điều kiện
        |--------------------------------------------------------------------------
        */

        $availableRoomFilter = function (Builder $query) use ($checkIn, $checkOut, $blockingBookingStatuses, $minPrice, $maxPrice, $guests, $roomType) {
            $query->where(
                'rooms.status',
                'available'
            );

            /*
            |--------------------------------------------------------------------------
            | Loại phòng có Booking giao nhau với khoảng ngày mới
            |--------------------------------------------------------------------------
            |
            | Booking cũ bắt đầu trước ngày trả mới
            | và Booking cũ kết thúc sau ngày nhận mới.
            |
            */

            $query->whereDoesntHave(
                'bookings',
                function (Builder $bookingQuery) use ($checkIn, $checkOut, $blockingBookingStatuses) {
                    $bookingQuery
                        ->whereIn(
                            'bookings.status',
                            $blockingBookingStatuses
                        )
                        ->where(
                            'bookings.check_in',
                            '<',
                            $checkOut
                        )
                        ->where(
                            'bookings.check_out',
                            '>',
                            $checkIn
                        );
                }
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

            if ($guests !== null) {
                $query->where(
                    'rooms.capacity',
                    '>=',
                    $guests
                );
            }

            if ($roomType !== '') {
                $query->where(
                    'rooms.room_type',
                    $roomType
                );
            }
        };

        /*
        |--------------------------------------------------------------------------
        | Truy vấn Homestay
        |--------------------------------------------------------------------------
        */

        $homestaysQuery = Homestay::query()
            ->with([
                'category',
                'amenities:id,name,icon',
            ])
            ->where(
                'homestays.status',
                true
            )

            /*
            |--------------------------------------------------------------------------
            | Lọc thành phố
            |--------------------------------------------------------------------------
            */

            ->when(
                $selectedLocation !== '',
                function (Builder $query) use ($selectedLocation) {
                    $query->whereRaw(
                        'TRIM(homestays.city) = ?',
                        [$selectedLocation]
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Tìm theo tên hoặc địa chỉ
            |--------------------------------------------------------------------------
            */

            ->when(
                $search !== '',
                function (Builder $query) use ($search) {
                    $query->where(
                        function (Builder $subQuery) use ($search) {
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
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Homestay phải còn ít nhất một phòng phù hợp
            |--------------------------------------------------------------------------
            */

            ->whereHas(
                'rooms',
                $availableRoomFilter
            )

            /*
            |--------------------------------------------------------------------------
            | Giá thấp nhất của chính phòng còn trống và phù hợp
            |--------------------------------------------------------------------------
            */

            ->withMin([
                'rooms as min_room_price' => $availableRoomFilter,
            ], 'price_per_night')

            /*
            |--------------------------------------------------------------------------
            | Thống kê
            |--------------------------------------------------------------------------
            */

            ->withCount([
                /*
                |--------------------------------------------------------------------------
                | Số phòng còn trống và phù hợp
                |--------------------------------------------------------------------------
                |
                | Sử dụng cùng điều kiện với whereHas và withMin để kết quả
                | đếm chính xác theo ngày, giá, số khách và loại phòng.
                |
                */

                'rooms as available_rooms_count' => $availableRoomFilter,

                /*
                |--------------------------------------------------------------------------
                | Số lượt đặt hợp lệ
                |--------------------------------------------------------------------------
                */

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

                /*
                |--------------------------------------------------------------------------
                | Số đánh giá đã duyệt
                |--------------------------------------------------------------------------
                */

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
            | Lọc tiện ích
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
            | Lọc đánh giá
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
                    ->orderByDesc(
                        'approved_reviews_count'
                    );
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
                    ->orderByDesc(
                        'approved_reviews_count'
                    );
                break;
        }

        $homestays = $homestaysQuery
            ->orderByDesc('homestays.id')
            ->paginate(9)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dữ liệu cho bộ lọc
        |--------------------------------------------------------------------------
        */

        $locations = Homestay::query()
            ->where(
                'homestays.status',
                true
            )
            ->whereNotNull('homestays.city')
            ->whereRaw(
                "TRIM(homestays.city) <> ''"
            )
            ->selectRaw(
                'TRIM(homestays.city) as city'
            )
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $amenities = Amenity::query()
            ->where('status', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'icon',
            ]);

        $roomTypes = Room::query()
            ->where(
                'status',
                'available'
            )
            ->whereNotNull('room_type')
            ->where(
                'room_type',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('room_type')
            ->pluck('room_type');

        return view(
            'search.index',
            compact(
                'homestays',
                'locations',
                'amenities',
                'roomTypes',
                'selectedLocation',
                'checkIn',
                'checkOut',
                'sort'
            )
        );
    }
}