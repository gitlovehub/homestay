<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Homestay;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount([
                'homestays' => function (Builder $query) {
                    $query->where(
                        'homestays.status',
                        true
                    );
                },
            ])
            ->orderBy('name')
            ->get();

        return view(
            'categories.index',
            compact('categories')
        );
    }

    public function show(
        Request $request,
        Category $category
    ): View {
        $search = trim(
            (string) $request->input('search')
        );

        $location = trim(
            (string) $request->input('location')
        );

        $minPrice = $request->filled('min_price')
            ? max(0, $request->integer('min_price'))
            : null;

        $maxPrice = $request->filled('max_price')
            ? max(0, $request->integer('max_price'))
            : null;

        // Nếu giá từ lớn hơn giá đến thì đổi vị trí hai giá trị
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
            ? max(1, $request->integer('guests'))
            : null;

        $minimumRating = $request->filled('rating')
            ? min(
                5,
                max(1, $request->integer('rating'))
            )
            : null;

        $roomType = trim(
            (string) $request->input('room_type')
        );

        $amenityIds = collect(
            $request->input('amenities', [])
        )
            ->filter(fn($id) => is_numeric($id))
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $sort = (string) $request->input(
            'sort',
            'popular'
        );

        $roomFilter = function (Builder $query) use ($minPrice, $maxPrice, $guests, $roomType) {
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

        $homestaysQuery = Homestay::query()
            ->with([
                'category',
                'amenities:id,name,icon',
            ])
            ->where(
                'homestays.category_id',
                $category->id
            )
            ->where(
                'homestays.status',
                true
            )

            /*
            |--------------------------------------------------------------------------
            | Một phòng phải thỏa mãn đồng thời tất cả điều kiện
            |--------------------------------------------------------------------------
            */

            ->whereHas(
                'rooms',
                $roomFilter
            )

            /*
            |--------------------------------------------------------------------------
            | Giá thấp nhất trong các phòng phù hợp
            |--------------------------------------------------------------------------
            */

            ->withMin([
                'rooms as min_room_price' => $roomFilter,
            ], 'price_per_night')

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
            ->when(
                $location !== '',
                function (Builder $query) use ($location) {
                    $query->whereRaw(
                        'TRIM(homestays.city) = ?',
                        [$location]
                    );
                }
            )
            ->when(
                $minPrice !== null ||
                $maxPrice !== null,
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

        $cities = Homestay::query()
            ->where(
                'category_id',
                $category->id
            )
            ->where(
                'status',
                true
            )
            ->whereNotNull('city')
            ->whereRaw("TRIM(city) <> ''")
            ->whereHas('rooms', function (Builder $query) {
                $query->where(
                    'rooms.status',
                    'available'
                );
            })
            ->selectRaw('TRIM(city) as city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view(
            'categories.show',
            compact(
                'category',
                'homestays',
                'amenities',
                'roomTypes',
                'cities',
                'sort'
            )
        );
    }
}