<?php

namespace Database\Seeders;

use App\Constants\ConstTypes;
use App\Models\ReportCategory;
use Illuminate\Database\Seeder;

class ReportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReportCategory::factory()->createMany([
            [
                'name' => 'Inappropriate',
                'type' => ConstTypes::CONVERSATION,
            ],
            [
                'name' => 'Irrelevant',
                'type' => ConstTypes::CONVERSATION,
            ],
        ]);
    }
}
