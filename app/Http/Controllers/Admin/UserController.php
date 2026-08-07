<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách tài khoản.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Kiểm tra dữ liệu bộ lọc
        |--------------------------------------------------------------------------
        */

        $filters = $request->validate(
            [
                'search' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'booking_activity' => [
                    'nullable',
                    Rule::in([
                        'has_booking',
                        'no_booking',
                    ]),
                ],

                'status' => [
                    'nullable',
                    Rule::in([
                        'active',
                        'inactive',
                    ]),
                ],

                'sort' => [
                    'nullable',
                    Rule::in([
                        'oldest',
                        'name_asc',
                        'name_desc',
                        'bookings_desc',
                        'paid_desc',
                    ]),
                ],
            ],
            [
                'search.string' => 'Từ khóa tìm kiếm không hợp lệ.',
                'search.max' => 'Từ khóa tìm kiếm tối đa 255 ký tự.',

                'booking_activity.in' =>
                    'Trạng thái đặt phòng không hợp lệ.',

                'status.in' =>
                    'Trạng thái tài khoản không hợp lệ.',

                'sort.in' =>
                    'Kiểu sắp xếp không hợp lệ.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Truy vấn danh sách tài khoản
        |--------------------------------------------------------------------------
        */

        $usersQuery = User::query()

            // Đếm dữ liệu liên quan
            ->withCount([
                'bookings',
                'reviews',

                // Số giao dịch thanh toán thành công
                'payments as successful_payments_count' => function ($query) {
                    $query->where(
                        'payments.status',
                        'paid'
                    );
                },
            ])

            // Tổng số tiền đã thanh toán thành công
            ->withSum(
                [
                    'payments as total_paid' => function ($query) {
                        $query->where(
                            'payments.status',
                            'paid'
                        );
                    },
                ],
                'amount'
            )

            /*
             * Tìm kiếm theo tên, email hoặc số điện thoại.
             */
            ->when(
                !empty($filters['search']),
                function ($query) use ($filters) {
                    $search = trim($filters['search']);

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )

            /*
             * Lọc theo hoạt động đặt phòng.
             */
            ->when(
                !empty($filters['booking_activity']),
                function ($query) use ($filters) {
                    if (
                        $filters['booking_activity']
                        === 'has_booking'
                    ) {
                        $query->whereHas('bookings');
                    }

                    if (
                        $filters['booking_activity']
                        === 'no_booking'
                    ) {
                        $query->whereDoesntHave('bookings');
                    }
                }
            )

            /*
             * Lọc theo trạng thái tài khoản.
             */
            ->when(
                !empty($filters['status']),
                function ($query) use ($filters) {
                    $query->where(
                        'status',
                        $filters['status']
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Phân trang
        |--------------------------------------------------------------------------
        */

        $sort = $filters['sort'] ?? null;

        switch ($sort) {
            case 'oldest':
                $usersQuery
                    ->orderBy('created_at')
                    ->orderBy('id');
                break;

            case 'name_asc':
                $usersQuery
                    ->orderBy('name')
                    ->orderBy('id');
                break;

            case 'name_desc':
                $usersQuery
                    ->orderByDesc('name')
                    ->orderByDesc('id');
                break;

            case 'bookings_desc':
                $usersQuery
                    ->orderByDesc('bookings_count')
                    ->orderByDesc('id');
                break;

            case 'paid_desc':
                $usersQuery
                    ->orderByDesc('total_paid')
                    ->orderByDesc('id');
                break;

            default:
                $usersQuery
                    ->orderByDesc('created_at')
                    ->orderByDesc('id');
                break;
        }

        $users = $usersQuery
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Thống kê tài khoản
        |--------------------------------------------------------------------------
        */

        $statistics = [
            'total' => User::query()
                ->count(),

            'new_this_month' => User::query()
                ->whereBetween(
                    'created_at',
                    [
                        now()->startOfMonth(),
                        now()->endOfMonth(),
                    ]
                )
                ->count(),

            'active' => User::query()
                ->where('status', 'active')
                ->count(),

            'inactive' => User::query()
                ->where('status', 'inactive')
                ->count(),
        ];

        return view(
            'admin.users.index',
            compact(
                'users',
                'statistics'
            )
        );
    }

    /**
     * Hiển thị chi tiết tài khoản,
     * lịch sử Booking và thanh toán.
     */
    public function show(User $user): View
    {
        /*
         * Đếm các dữ liệu liên quan của tài khoản.
         */
        $user->loadCount([
            'homestays',
            'bookings',
            'reviews',

            'payments as successful_payments_count' => function ($query) {
                $query->where(
                    'payments.status',
                    'paid'
                );
            },
        ]);

        /*
         * Danh sách Booking của tài khoản.
         */
        $bookings = $user->bookings()
            ->with([
                'room.homestay',
                'promotion',
                'payment',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(
                5,
                ['*'],
                'bookings_page'
            )
            ->withQueryString();

        /*
         * Thống kê thanh toán của tài khoản.
         */
        $paymentStatistics = [
            'total_transactions' => $user
                ->payments()
                ->count(),

            'successful_transactions' => $user
                ->payments()
                ->where('payments.status', 'paid')
                ->count(),

            'pending_transactions' => $user
                ->payments()
                ->where('payments.status', 'pending')
                ->count(),

            'failed_transactions' => $user
                ->payments()
                ->where('payments.status', 'failed')
                ->count(),

            'refunded_transactions' => $user
                ->payments()
                ->where('payments.status', 'refunded')
                ->count(),

            'total_paid' => (int) $user
                ->payments()
                ->where('payments.status', 'paid')
                ->sum('amount'),

            'total_refunded' => (int) $user
                ->payments()
                ->where('payments.status', 'refunded')
                ->sum('amount'),
        ];

        /*
         * Giao dịch gần nhất.
         */
        $latestPayment = $user
            ->payments()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();

        return view(
            'admin.users.show',
            compact(
                'user',
                'bookings',
                'paymentStatistics',
                'latestPayment'
            )
        );
    }

    /**
     * Khóa hoặc mở khóa tài khoản.
     */
    public function updateStatus(
        Request $request,
        User $user
    ): RedirectResponse {
        /*
         * Kiểm tra trạng thái được gửi lên.
         */
        $data = $request->validate(
            [
                'status' => [
                    'required',
                    Rule::in([
                        'active',
                        'inactive',
                    ]),
                ],
            ],
            [
                'status.required' =>
                    'Vui lòng chọn trạng thái tài khoản.',

                'status.in' =>
                    'Trạng thái tài khoản không hợp lệ.',
            ]
        );

        /*
         * Không cho Admin tự khóa tài khoản
         * đang đăng nhập.
         */
        if (
            (int) $request->user()->id
            === (int) $user->id
            && $data['status'] === 'inactive'
        ) {
            return back()->with(
                'error',
                'Bạn không thể tự khóa tài khoản của chính mình.'
            );
        }

        /*
         * Không cập nhật nếu trạng thái không thay đổi.
         */
        if ($user->status === $data['status']) {
            return back()->with(
                'error',
                'Tài khoản đã ở trạng thái này.'
            );
        }

        /*
         * Cập nhật trạng thái.
         */
        $user->update([
            'status' => $data['status'],
        ]);

        $message = $data['status'] === 'active'
            ? "Đã mở khóa tài khoản {$user->name}."
            : "Đã khóa tài khoản {$user->name}.";

        return back()->with(
            'success',
            $message
        );
    }
}