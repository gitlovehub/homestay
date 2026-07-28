import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import './components/navbar';

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
