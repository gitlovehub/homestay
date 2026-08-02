<?php

namespace Database\Seeders;

use App\Models\ContactLocation;
use Illuminate\Database\Seeder;

class ContactLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'label' => 'Hà Nội',
                'name' => 'FPT Polytechnic Hà Nội',
                'address' => 'Phố Trịnh Văn Bô, phường Xuân Phương, quận Nam Từ Liêm, TP. Hà Nội',
                'map_query' => 'Tòa nhà FPT Polytechnic, phố Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội',
                'sort_order' => 1,
                'is_active' => true,
            ],

            [
                'label' => 'TP.HCM',
                'name' => 'FPT Polytechnic TP. Hồ Chí Minh',
                'address' => 'Tòa nhà QTSC9, đường Tô Ký, phường Trung Mỹ Tây, TP. Hồ Chí Minh',
                'map_query' => 'Tòa nhà QTSC9, đường Tô Ký, Trung Mỹ Tây, Thành phố Hồ Chí Minh',
                'sort_order' => 2,
                'is_active' => true,
            ],

            [
                'label' => 'Đà Nẵng',
                'name' => 'FPT Polytechnic Đà Nẵng',
                'address' => '137 Nguyễn Thị Thập, phường Thanh Khê, TP. Đà Nẵng',
                'map_query' => 'FPT Polytechnic, 137 Nguyễn Thị Thập, Thanh Khê, Đà Nẵng',
                'sort_order' => 3,
                'is_active' => true,
            ],

            [
                'label' => 'Hải Phòng',
                'name' => 'FPT Polytechnic Hải Phòng',
                'address' => '118 Cát Bi, phường Hải An, TP. Hải Phòng',
                'map_query' => 'FPT Polytechnic, 118 Cát Bi, Hải An, Hải Phòng',
                'sort_order' => 4,
                'is_active' => true,
            ],

            [
                'label' => 'Thái Nguyên',
                'name' => 'FPT Polytechnic Thái Nguyên',
                'address' => 'Tòa nhà FPT Polytechnic, đường đê Mỏ Bạch, tổ 10, phường Quyết Thắng, tỉnh Thái Nguyên',
                'map_query' => 'FPT Polytechnic, đường đê Mỏ Bạch, Quyết Thắng, Thái Nguyên',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($locations as $location) {
            ContactLocation::updateOrCreate(
                [
                    'label' => $location['label'],
                ],
                $location
            );
        }
    }
}