import Alpine from 'alpinejs';

window.Alpine = Alpine;

/*
|--------------------------------------------------------------------------
| Bộ chọn ngày nhận phòng và ngày trả phòng
|--------------------------------------------------------------------------
*/

Alpine.data('dateRangePicker', (config = {}) => ({
    // Lịch đang mở: checkIn, checkOut hoặc null
    activePicker: null,

    // Giá trị ngày được truyền từ Blade
    checkIn: config.checkIn || '',
    checkOut: config.checkOut || '',

    // Ngày nhỏ nhất được phép chọn
    minDate: config.minDate || '',

    // Tháng đang hiển thị trên lịch
    visibleMonth: new Date(),

    // Trạng thái lỗi
    checkInError: false,
    checkOutError: false,

    /*
    |--------------------------------------------------------------------------
    | Khởi tạo component
    |--------------------------------------------------------------------------
    */

    init() {
        this.validateInitialDates();

        const firstVisibleDate =
            this.checkIn ||
            this.minDate ||
            this.formatInputDate(new Date());

        this.setVisibleMonth(firstVisibleDate);
    },

    /*
    |--------------------------------------------------------------------------
    | Chuyển YYYY-MM-DD thành đối tượng Date
    |--------------------------------------------------------------------------
    */

    parseDate(value) {
        if (!value) {
            return new Date();
        }

        const [year, month, day] = value
            .split('-')
            .map(Number);

        return new Date(year, month - 1, day);
    },

    /*
    |--------------------------------------------------------------------------
    | Chuyển Date thành YYYY-MM-DD
    |--------------------------------------------------------------------------
    */

    formatInputDate(date) {
        const year = date.getFullYear();

        const month = String(
            date.getMonth() + 1
        ).padStart(2, '0');

        const day = String(
            date.getDate()
        ).padStart(2, '0');

        return `${year}-${month}-${day}`;
    },

    /*
    |--------------------------------------------------------------------------
    | Hiển thị ngày theo định dạng dd/mm/yyyy
    |--------------------------------------------------------------------------
    */

    formatDisplayDate(value) {
        if (!value) {
            return '';
        }

        const [year, month, day] = value.split('-');

        return `${day}/${month}/${year}`;
    },

    /*
    |--------------------------------------------------------------------------
    | Đặt tháng cần hiển thị
    |--------------------------------------------------------------------------
    */

    setVisibleMonth(value) {
        const date = this.parseDate(value);

        this.visibleMonth = new Date(
            date.getFullYear(),
            date.getMonth(),
            1
        );
    },

    /*
    |--------------------------------------------------------------------------
    | Mở hoặc đóng lịch
    |--------------------------------------------------------------------------
    */

    toggleDatePicker(type) {
        if (this.activePicker === type) {
            this.closeDatePicker();
            return;
        }

        this.openDatePicker(type);
    },

    openDatePicker(type) {
        // Không cho chọn ngày trả phòng trước ngày nhận phòng
        if (type === 'checkOut' && !this.checkIn) {
            this.checkInError = true;
            return;
        }

        this.activePicker = type;

        const visibleDate =
            type === 'checkIn'
                ? this.checkIn || this.minDate
                : this.checkOut || this.nextDay(this.checkIn);

        this.setVisibleMonth(visibleDate);
    },

    closeDatePicker() {
        this.activePicker = null;
    },

    /*
    |--------------------------------------------------------------------------
    | Tên tháng đang hiển thị
    |--------------------------------------------------------------------------
    */

    get monthLabel() {
        return this.visibleMonth.toLocaleDateString('vi-VN', {
            month: 'long',
            year: 'numeric',
        });
    },

    /*
    |--------------------------------------------------------------------------
    | Chuyển tháng
    |--------------------------------------------------------------------------
    */

    previousMonth() {
        if (!this.canGoPreviousMonth()) {
            return;
        }

        this.visibleMonth = new Date(
            this.visibleMonth.getFullYear(),
            this.visibleMonth.getMonth() - 1,
            1
        );
    },

    nextMonth() {
        this.visibleMonth = new Date(
            this.visibleMonth.getFullYear(),
            this.visibleMonth.getMonth() + 1,
            1
        );
    },

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra có được quay lại tháng trước không
    |--------------------------------------------------------------------------
    */

    canGoPreviousMonth() {
        const minimumDate =
            this.activePicker === 'checkOut'
                ? this.nextDay(this.checkIn)
                : this.minDate;

        const minimumMonth = this.parseDate(minimumDate);

        const firstAllowedMonth = new Date(
            minimumMonth.getFullYear(),
            minimumMonth.getMonth(),
            1
        );

        return this.visibleMonth > firstAllowedMonth;
    },

    /*
    |--------------------------------------------------------------------------
    | Số ô trống trước ngày đầu tiên của tháng
    |--------------------------------------------------------------------------
    |
    | Lịch bắt đầu từ thứ Hai.
    |
    */

    leadingBlankDays() {
        const firstDay = new Date(
            this.visibleMonth.getFullYear(),
            this.visibleMonth.getMonth(),
            1
        ).getDay();

        const mondayBasedOffset = (firstDay + 6) % 7;

        return Array.from(
            { length: mondayBasedOffset },
            (_, index) => index
        );
    },

    /*
    |--------------------------------------------------------------------------
    | Danh sách ngày trong tháng
    |--------------------------------------------------------------------------
    */

    monthDays() {
        const year = this.visibleMonth.getFullYear();
        const month = this.visibleMonth.getMonth();

        const totalDays = new Date(
            year,
            month + 1,
            0
        ).getDate();

        return Array.from(
            { length: totalDays },
            (_, index) => {
                return this.formatInputDate(
                    new Date(year, month, index + 1)
                );
            }
        );
    },

    /*
    |--------------------------------------------------------------------------
    | Lấy số ngày
    |--------------------------------------------------------------------------
    */

    dayNumber(value) {
        return this.parseDate(value).getDate();
    },

    /*
    |--------------------------------------------------------------------------
    | Lấy ngày tiếp theo
    |--------------------------------------------------------------------------
    */

    nextDay(value) {
        const date = this.parseDate(
            value || this.minDate
        );

        date.setDate(date.getDate() + 1);

        return this.formatInputDate(date);
    },

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra ngày bị vô hiệu hóa
    |--------------------------------------------------------------------------
    */

    isDateDisabled(value) {
        const minimumDate =
            this.activePicker === 'checkOut'
                ? this.nextDay(this.checkIn)
                : this.minDate;

        return value < minimumDate;
    },

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra ngày đang được chọn
    |--------------------------------------------------------------------------
    */

    isSelected(value) {
        if (this.activePicker === 'checkIn') {
            return this.checkIn === value;
        }

        return this.checkOut === value;
    },

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra ngày hôm nay
    |--------------------------------------------------------------------------
    */

    isToday(value) {
        return value === this.formatInputDate(new Date());
    },

    /*
    |--------------------------------------------------------------------------
    | Class hiển thị cho từng ngày
    |--------------------------------------------------------------------------
    */

    dateButtonClasses(value) {
        if (this.isSelected(value)) {
            return 'bg-blue-600 font-bold text-white shadow-sm';
        }

        if (this.isDateDisabled(value)) {
            return 'cursor-not-allowed text-slate-300';
        }

        if (this.isToday(value)) {
            return 'bg-blue-50 font-bold text-blue-700 hover:bg-blue-100';
        }

        return 'cursor-pointer text-slate-700 hover:bg-slate-100';
    },

    /*
    |--------------------------------------------------------------------------
    | Chọn ngày
    |--------------------------------------------------------------------------
    */

    selectDate(value) {
        if (this.isDateDisabled(value)) {
            return;
        }

        // Chọn ngày nhận phòng
        if (this.activePicker === 'checkIn') {
            this.checkIn = value;
            this.checkInError = false;

            // Nếu ngày trả không còn hợp lệ thì xóa
            if (
                this.checkOut &&
                this.checkOut < this.nextDay(this.checkIn)
            ) {
                this.checkOut = '';
            }

            this.closeDatePicker();

            return;
        }

        // Chọn ngày trả phòng
        if (this.activePicker === 'checkOut') {
            this.checkOut = value;
            this.checkOutError = false;

            this.closeDatePicker();
        }
    },

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra dữ liệu ngày ban đầu
    |--------------------------------------------------------------------------
    */

    validateInitialDates() {
        // Ngày nhận nhỏ hơn ngày hiện tại
        if (
            this.checkIn &&
            this.minDate &&
            this.checkIn < this.minDate
        ) {
            this.checkIn = '';
        }

        // Ngày trả không hợp lệ
        if (
            this.checkOut &&
            (
                !this.checkIn ||
                this.checkOut < this.nextDay(this.checkIn)
            )
        ) {
            this.checkOut = '';
        }
    },

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra trước khi gửi form
    |--------------------------------------------------------------------------
    */

    validateBeforeSubmit(event) {
        this.checkInError = !this.checkIn;
        this.checkOutError = !this.checkOut;

        // Đã chọn đủ hai ngày thì cho phép gửi form
        if (!this.checkInError && !this.checkOutError) {
            return;
        }

        event.preventDefault();

        // Thiếu ngày nhận thì mở lịch ngày nhận
        if (this.checkInError) {
            this.openDatePicker('checkIn');
            return;
        }

        // Thiếu ngày trả thì mở lịch ngày trả
        this.openDatePicker('checkOut');
    },
}));

Alpine.start();

import './components/navbar';
import './admin-action-menu';

import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const bookingOverviewCanvas = document.getElementById(
        'bookingOverviewChart'
    );

    const bookingStatusCanvas = document.getElementById(
        'bookingStatusChart'
    );

    if (bookingOverviewCanvas) {
        const labels = JSON.parse(
            bookingOverviewCanvas.dataset.labels
        );

        const bookings = JSON.parse(
            bookingOverviewCanvas.dataset.bookings
        );

        const revenue = JSON.parse(
            bookingOverviewCanvas.dataset.revenue
        );

        new Chart(bookingOverviewCanvas, {
            type: 'line',

            data: {
                labels,

                datasets: [
                    {
                        label: 'Đơn đặt phòng',
                        data: bookings,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.08)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        yAxisID: 'yBookings',
                    },
                    {
                        label: 'Doanh thu',
                        data: revenue,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.06)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: false,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        yAxisID: 'yRevenue',
                    },
                ],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label(context) {
                                if (context.dataset.yAxisID === 'yRevenue') {
                                    return `${context.dataset.label}: ${new Intl.NumberFormat('vi-VN').format(context.raw)}₫`;
                                }

                                return `${context.dataset.label}: ${context.raw}`;
                            },
                        },
                    },
                },
                scales: {
                    yBookings: {
                        beginAtZero: true,
                        position: 'left',
                        ticks: {
                            precision: 0,
                        },
                        grid: {
                            color: '#e2e8f0',
                        },
                    },
                    yRevenue: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            callback(value) {
                                return new Intl.NumberFormat('vi-VN', {
                                    notation: 'compact',
                                    maximumFractionDigits: 1,
                                }).format(value);
                            },
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
            },
        });
    }

    if (bookingStatusCanvas) {
        const statuses = JSON.parse(
            bookingStatusCanvas.dataset.statuses
        );

        new Chart(bookingStatusCanvas, {
            type: 'doughnut',

            data: {
                labels: [
                    'Chờ xác nhận',
                    'Đã xác nhận',
                    'Đang nhận phòng',
                    'Hoàn thành',
                    'Đã hủy',
                ],

                datasets: [
                    {
                        data: statuses,
                        backgroundColor: [
                            '#fbbf24',
                            '#3b82f6',
                            '#8b5cf6',
                            '#10b981',
                            '#ef4444',
                        ],
                        borderWidth: 0,
                        hoverOffset: 5,
                    },
                ],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }
});