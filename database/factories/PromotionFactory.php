<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    public function definition(): array
    {
        $discountType = fake()->randomElement([
            'fixed',
            'percent',
        ]);

        $startDate = fake()->dateTimeBetween('-15 days', '+10 days');
        $endDate = fake()->dateTimeBetween($startDate, '+3 months');

        return [
            'code' => strtoupper(fake()->unique()->randomElement([
                'KHAITRUONG',
                'DATSOM',
                'MUAHE',
                'CUOITUAN',
                'NGHILE',
                'VIP',
                'GIAMGIA',
                'TRIAN',
                'THANHVIEN',
                'CHAOMUNG',
            ])) . fake()->numberBetween(10, 99),

            'name' => fake()->randomElement([
                'Ưu đãi khai trương',
                'Giảm giá đặt phòng sớm',
                'Khuyến mãi mùa hè',
                'Ưu đãi cuối tuần',
                'Khuyến mãi ngày lễ',
                'Ưu đãi khách VIP',
                'Chương trình tri ân khách hàng',
                'Ưu đãi thành viên',
            ]),

            'description' => fake()->randomElement([
                'Áp dụng cho khách hàng đặt phòng trong thời gian khuyến mãi.',
                'Giảm trực tiếp trên tổng giá trị đơn đặt phòng.',
                'Số lượng mã có hạn và không áp dụng đồng thời với ưu đãi khác.',
                'Khuyến mãi dành cho khách hàng đặt phòng sớm.',
            ]),

            'discount_type' => $discountType,

            'discount_value' => $discountType === 'percent'
                ? fake()->randomElement([5, 10, 15, 20, 25])
                : fake()->randomElement([
                    50000,
                    100000,
                    150000,
                    200000,
                    300000,
                ]),

            'min_order_value' => fake()->randomElement([
                0,
                500000,
                1000000,
                1500000,
                2000000,
            ]),

            'max_discount' => $discountType === 'percent'
                ? fake()->randomElement([
                    100000,
                    200000,
                    300000,
                    500000,
                ])
                : null,

            'usage_limit' => fake()->randomElement([
                20,
                50,
                100,
                200,
                null,
            ]),

            'used_count' => 0,

            'start_date' => $startDate,
            'end_date' => $endDate,

            'status' => true,
        ];
    }
}