<?php

namespace Database\Seeders;

use App\Models\Tile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiles = [
            [
                'layout_id' => 1,
                'x' => 0,
                'y' => 0,
                'width' => '500px',
                'height' => '500px'
            ],
            [
                'layout_id' => 2,
                'x' => 0,
                'y' => 0,
                'width' => '250px',
                'height' => '500px'
            ],
            [
                'layout_id' => 2,
                'x' => 250,
                'y' => 0,
                'width' => '250px',
                'height' => '500px'
            ],
            [
                'layout_id' => 3,
                'x' => 0,
                'y' => 0,
                'width' => '250px',
                'height' => '500px'
            ],
            [
                'layout_id' => 3,
                'x' => 250,
                'y' => 0,
                'width' => '250px',
                'height' => '250px'
            ],
            [
                'layout_id' => 3,
                'x' => 250,
                'y' => 250,
                'width' => '250px',
                'height' => '250px'
            ],
        ];

        foreach ($tiles as $tile) {
            Tile::query()->create($tile);
        }
    }
}
