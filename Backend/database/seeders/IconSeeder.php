<?php

namespace Database\Seeders;

use App\Models\Icon;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $icons = [
            [
                'name'      => 'Cat',
                'path'      => 'images/profile_icons/cat.png'
            ],

            [
                'name'      => 'Deer',
                'path'      => 'images/profile_icons/deer.png'
            ],

            [
                'name'      => 'Horseshoe',
                'path'      => 'images/profile_icons/horseshoe.png'
            ],

            [
                'name'      => 'Butterfly',
                'path'      => 'images/profile_icons/butterfly.png'
            ],

            [
                'name'      => 'Blackbird',
                'path'      => 'images/profile_icons/blackbird.png'
            ],
        ];

        foreach ($icons as $icon) {
            Icon::create($icon);
        }
    }
}
