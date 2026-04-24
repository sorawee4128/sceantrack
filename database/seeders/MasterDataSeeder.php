<?php

namespace Database\Seeders;

use App\Models\BodyHandling;
use App\Models\Gender;
use App\Models\NotificationType;
use App\Models\PoliceStation;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['code' => 'BH001', 'name' => 'มอบให้ญาติ'],
            ['code' => 'BH002', 'name' => 'ส่งตรวจเป็นเคส F'],
            ['code' => 'BH003', 'name' => 'ส่งตรวจเป็นเคส O'],
            ['code' => 'BH004', 'name' => 'ส่งตรวจเป็นเคส I'],
        ] as $item) {
            BodyHandling::firstOrCreate(['name' => $item['name']], $item + ['is_active' => true]);
        }

        foreach ([
            ['code' => 'NT001', 'name' => 'ยังมิปรากฏเหตุ'],
            ['code' => 'NT002', 'name' => 'อุบัติเหตุจราจร'],
            ['code' => 'NT003', 'name' => 'ฆ่าตัวตาย'],
        ] as $item) {
            NotificationType::firstOrCreate(['name' => $item['name']], $item + ['is_active' => true]);
        }

        foreach ([
            ['code' => 'M', 'name' => 'ชาย'],
            ['code' => 'F', 'name' => 'หญิง'],
            ['code' => 'U', 'name' => 'ไม่ระบุ'],
        ] as $item) {
            Gender::firstOrCreate(['name' => $item['name']], $item + ['is_active' => true]);
        }

        foreach ([
            ['code' => 'PS001', 'name' => 'สถานีตำรวจตัวอย่าง 1', 'description' => 'แก้ไขได้ภายหลัง'],
            ['code' => 'PS002', 'name' => 'สถานีตำรวจตัวอย่าง 2', 'description' => 'แก้ไขได้ภายหลัง'],
        ] as $item) {
            PoliceStation::firstOrCreate(['name' => $item['name']], $item + ['is_active' => true]);
        }
    }
}
