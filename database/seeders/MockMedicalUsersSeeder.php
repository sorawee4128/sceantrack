<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MockMedicalUsersSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = 'password123';

        $doctors = [
            [
                'full_name' => 'นาวาอากาศโทวรสิทธิ์ ดุลอำนวย',
                'position' => 'แพทย์',
            ],
            [
                'full_name' => 'นาวาอากาศโทวุฒิพันธ์ นภาพงษ์',
                'position' => 'แพทย์',
            ],
            [
                'full_name' => 'นาวาอากาศตรีนิจชา รุทธพิชัยรักษ์',
                'position' => 'แพทย์',
            ],
        ];

        $assistants = [
            [
                'full_name' => 'ร้อยตรี ธิติพล แผ้วพลสง',
                'position' => 'ผู้ช่วย',
            ],
            [
                'full_name' => 'พันจ่าอากาศเอก วสุ มุมวงศ์',
                'position' => 'ผู้ช่วย',
            ],
            [
                'full_name' => 'พันจ่าอากาศเอก เอกลักษณ์ ช่างเหล็ก',
                'position' => 'ผู้ช่วย',
            ],
            [
                'full_name' => 'นาย ธนะเทพ ประสงค์',
                'position' => 'ผู้ช่วย',
            ],
            [
                'full_name' => 'นายสันติ ยอดอิ่ม',
                'position' => 'ผู้ช่วย',
            ],
        ];

        foreach ($doctors as $index => $item) {
            $username = 'doctor' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $email = $username . '@example.com';

            $user = User::updateOrCreate(
                ['username' => $username],
                [
                    'name' => $username,
                    'username' => $username,
                    'email' => $email,
                    'full_name' => $item['full_name'],
                    'phone' => '08' . str_pad((string) ($index + 1), 8, '0', STR_PAD_LEFT),
                    'position' => $item['position'],
                    'is_active' => true,
                    'password' => bcrypt($defaultPassword),
                ]
            );

            $user->syncRoles(['doctor']);
        }

        foreach ($assistants as $index => $item) {
            $username = 'assistant' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $email = $username . '@example.com';

            $user = User::updateOrCreate(
                ['username' => $username],
                [
                    'name' => $username,
                    'username' => $username,
                    'email' => $email,
                    'full_name' => $item['full_name'],
                    'phone' => '09' . str_pad((string) ($index + 1), 8, '0', STR_PAD_LEFT),
                    'position' => $item['position'],
                    'is_active' => true,
                    'password' => bcrypt($defaultPassword),
                ]
            );

            $user->syncRoles(['assistant']);
        }
    }
}