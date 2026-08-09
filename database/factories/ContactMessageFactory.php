<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        $status = fake()->randomElement([
            'unread',
            'unread',
            'read',
            'read',
            'replied',
        ]);

        $createdAt = fake()->dateTimeBetween('-3 months', 'now');

        $readAt = null;
        $repliedAt = null;

        if (in_array($status, ['read', 'replied'])) {
            $readAt = fake()->dateTimeBetween(
                $createdAt,
                'now'
            );
        }

        if ($status === 'replied') {
            $repliedAt = fake()->dateTimeBetween(
                $readAt,
                'now'
            );
        }

        $names = [
            'Nguyễn Minh Anh',
            'Trần Quốc Bảo',
            'Lê Hoàng Nam',
            'Phạm Thu Trang',
            'Hoàng Gia Huy',
            'Vũ Ngọc Mai',
            'Đặng Quang Minh',
            'Bùi Thanh Tùng',
            'Đỗ Khánh Linh',
            'Nguyễn Đức Anh',
            'Trần Minh Thư',
            'Lê Hải Đăng',
            'Phạm Ngọc Anh',
            'Hoàng Tuấn Kiệt',
            'Vũ Minh Châu',
            'Đặng Thanh Hà',
            'Bùi Quốc Khánh',
            'Đỗ Mai Anh',
            'Nguyễn Thảo Vy',
            'Trần Hoàng Sơn',
        ];

        $subjects = [
            'Hỏi về tình trạng phòng',
            'Tư vấn đặt phòng',
            'Thắc mắc về giá phòng',
            'Hỏi về thời gian nhận phòng',
            'Hỏi về thời gian trả phòng',
            'Yêu cầu hỗ trợ đặt phòng',
            'Thắc mắc về chính sách hủy phòng',
            'Hỏi về tiện ích Homestay',
            'Tư vấn phòng cho gia đình',
            'Hỏi về phương thức thanh toán',
            'Không nhận được xác nhận đặt phòng',
            'Thay đổi ngày nhận phòng',
            'Thay đổi số lượng khách',
            'Hỏi về vị trí Homestay',
            'Tư vấn Homestay phù hợp',
        ];

        $messages = [
            'Tôi muốn hỏi hiện tại Homestay còn phòng trống vào cuối tuần này không?',
            'Tôi đang có kế hoạch đi du lịch cùng gia đình, nhờ HomeStayGo tư vấn phòng phù hợp.',
            'Cho tôi hỏi giá phòng hiển thị trên website đã bao gồm các loại phí chưa?',
            'Tôi muốn biết thời gian nhận phòng và trả phòng của Homestay là mấy giờ?',
            'Tôi cần hỗ trợ đặt phòng cho nhóm bạn, mong quản trị viên tư vấn giúp.',
            'Nếu tôi hủy phòng trước ngày nhận phòng thì chính sách hoàn tiền như thế nào?',
            'Cho tôi hỏi Homestay có WiFi, điều hòa và chỗ đỗ xe không?',
            'Gia đình tôi có trẻ nhỏ, xin tư vấn loại phòng phù hợp và tiện nghi.',
            'Tôi có thể thanh toán đặt phòng bằng VNPay được không?',
            'Tôi đã đặt phòng nhưng chưa thấy thông báo xác nhận, nhờ kiểm tra giúp.',
            'Tôi muốn thay đổi ngày nhận phòng của đơn đã đặt thì phải làm thế nào?',
            'Tôi cần tăng số lượng khách trong đơn đặt phòng, mong được hỗ trợ.',
            'Cho tôi xin thêm thông tin về địa chỉ và cách di chuyển đến Homestay.',
            'Tôi muốn tìm Homestay yên tĩnh cho chuyến nghỉ dưỡng, nhờ tư vấn giúp.',
            'Homestay có hỗ trợ nhận phòng sớm hơn thời gian quy định không?',
            'Tôi muốn hỏi phòng có cho phép mang theo thú cưng không?',
            'Nhờ kiểm tra giúp tôi tình trạng thanh toán của đơn đặt phòng.',
            'Tôi muốn đặt nhiều phòng cùng lúc cho đoàn khách thì có được không?',
            'Cho tôi hỏi có chương trình ưu đãi nào đang áp dụng cho khách đặt phòng không?',
            'Tôi cần thêm thông tin trước khi quyết định đặt phòng, mong được phản hồi sớm.',
        ];

        $name = fake()->randomElement($names);

        return [
            // Có thể có hoặc không có tài khoản
            'user_id' => fake()->boolean(70)
                ? User::query()->inRandomOrder()->value('id')
                : null,

            'name' => $name,

            'email' => fake()->unique()->safeEmail(),

            'phone' => '0'
                . fake()->randomElement([
                    '32', '33', '34', '35', '36', '37', '38', '39',
                    '70', '76', '77', '78', '79',
                    '81', '82', '83', '84', '85', '86', '88',
                    '89', '90', '91', '93', '94', '96', '97', '98',
                ])
                . fake()->numerify('#######'),

            'subject' => fake()->randomElement($subjects),

            'message' => fake()->randomElement($messages),

            'status' => $status,

            'read_at' => $readAt,

            'replied_at' => $repliedAt,

            'created_at' => $createdAt,

            'updated_at' => $repliedAt
                ?? $readAt
                ?? $createdAt,
        ];
    }
}