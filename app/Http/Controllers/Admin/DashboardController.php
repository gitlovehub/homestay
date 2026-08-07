<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use App\Models\Review;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Thống kê tổng quan
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::where('role', 'user')->count();

        $activeUsers = User::where('role', 'user')
            ->where('status', 'active')
            ->count();

        $totalHomestays = Homestay::count();

        $activeHomestays = Homestay::where('status', true)->count();

        $totalBookings = Booking::count();

        $pendingBookings = Booking::where('status', 'pending')->count();

        $totalReviews = Review::count();

        $pendingReviews = Review::where('status', 'pending')->count();

        $averageRating = (float) (
            Review::where('status', 'approved')->avg('rating') ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Doanh thu
        |--------------------------------------------------------------------------
        | Chỉ tính các đơn đã hoàn thành.
        */

        $totalRevenue = Booking::where('status', 'completed')
            ->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | Số lượng phát sinh trong tháng hiện tại
        |--------------------------------------------------------------------------
        */

        $newUsersThisMonth = User::where('role', 'user')
            ->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->count();

        $newHomestaysThisMonth = Homestay::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->count();

        $newBookingsThisMonth = Booking::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->count();

        $newReviewsThisMonth = Review::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Thống kê trạng thái Booking
        |--------------------------------------------------------------------------
        */

        $bookingStatusCounts = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'checked_in' => Booking::where('status', 'checked_in')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Dữ liệu biểu đồ Booking 7 ngày gần nhất
        |--------------------------------------------------------------------------
        */

        $sevenDays = collect(range(6, 0))
            ->map(
                fn (int $day) => CarbonImmutable::today()->subDays($day)
            );

        $bookingsByDate = Booking::query()
            ->selectRaw('DATE(created_at) as booking_date, COUNT(*) as total')
            ->whereDate('created_at', '>=', $sevenDays->first())
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'booking_date');

        $bookingChartLabels = $sevenDays
            ->map(fn (CarbonImmutable $date) => $date->format('d/m'))
            ->values();

        $bookingChartData = $sevenDays
            ->map(function (CarbonImmutable $date) use ($bookingsByDate) {
                return (int) ($bookingsByDate[$date->format('Y-m-d')] ?? 0);
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Dữ liệu biểu đồ doanh thu 7 ngày gần nhất
        |--------------------------------------------------------------------------
        | Chỉ tính đơn đã hoàn thành.
        */

        $revenueByDate = Booking::query()
            ->selectRaw(
                'DATE(created_at) as booking_date, SUM(total_price) as total'
            )
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $sevenDays->first())
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'booking_date');

        $revenueChartData = $sevenDays
            ->map(function (CarbonImmutable $date) use ($revenueByDate) {
                return (int) ($revenueByDate[$date->format('Y-m-d')] ?? 0);
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Dữ liệu đường biểu diễn nhỏ trên các card
        |--------------------------------------------------------------------------
        */

        $userSparkline = $this->getDailyCounts(
            User::where('role', 'user'),
            $sevenDays
        );

        $homestaySparkline = $this->getDailyCounts(
            Homestay::query(),
            $sevenDays
        );

        $bookingSparkline = $bookingChartData;

        $reviewSparkline = $this->getDailyCounts(
            Review::query(),
            $sevenDays
        );

        /*
        |--------------------------------------------------------------------------
        | Booking và Review mới nhất
        |--------------------------------------------------------------------------
        */

        $latestBookings = Booking::with([
                'user',
                'room.homestay',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $latestReviews = Review::with([
                'user',
                'homestay',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'totalHomestays',
            'activeHomestays',
            'totalBookings',
            'pendingBookings',
            'totalReviews',
            'pendingReviews',
            'averageRating',
            'totalRevenue',

            'newUsersThisMonth',
            'newHomestaysThisMonth',
            'newBookingsThisMonth',
            'newReviewsThisMonth',

            'bookingStatusCounts',

            'bookingChartLabels',
            'bookingChartData',
            'revenueChartData',

            'userSparkline',
            'homestaySparkline',
            'bookingSparkline',
            'reviewSparkline',

            'latestBookings',
            'latestReviews',
        ));
    }

    /**
     * Lấy tổng số bản ghi được tạo trong từng ngày của 7 ngày gần nhất.
     */
    private function getDailyCounts(
        $query,
        Collection $sevenDays
    ): Collection {
        $countsByDate = $query
            ->selectRaw('DATE(created_at) as created_date, COUNT(*) as total')
            ->whereDate('created_at', '>=', $sevenDays->first())
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'created_date');

        return $sevenDays
            ->map(function (CarbonImmutable $date) use ($countsByDate) {
                return (int) ($countsByDate[$date->format('Y-m-d')] ?? 0);
            })
            ->values();
    }
}