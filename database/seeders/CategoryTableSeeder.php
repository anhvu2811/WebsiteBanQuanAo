<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_category')->insert([
            [
                'category_name' => 'Điện thoại',
                'category_description' => 'Các mẫu điện thoại',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_name' => 'Xe máy 110cc',
                'category_description' => 'Các mẫu xe máy chỉ có 110cc',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
