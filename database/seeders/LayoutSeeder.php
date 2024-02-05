<?php

namespace Database\Seeders;

use App\Models\Layout;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layouts = [
            [
                'name' => 'Layout 1',
                'narration' => 'For layout 1. Has one tile.'
            ],
            [
                'name' => 'Layout 2',
                'narration' => 'For layout 2. Has two tiles.'
            ],
            [
                'name' => 'Layout 3',
                'narration' => 'For layout 3. Has three tiles.'
            ],
        ];

        foreach ($layouts as $layout) {
            Layout::query()->create($layout);
        }
    }
}
