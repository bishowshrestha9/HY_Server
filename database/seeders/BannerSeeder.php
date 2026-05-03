<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Banner::create([
            'banner_text' => 'Banner Text',
            'banner_link' => 'Banner Link',
            'is_active' => 'active',
            'background_color' => 'Background Color',
            'text_color' => 'Text Color',
            
        ]);
    }
}
