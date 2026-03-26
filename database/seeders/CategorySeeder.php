<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Desktop / PC', 'type' => 'hardware', 'icon' => 'bi-pc-display', 'color' => 'primary'],
            ['name' => 'Laptop / Notebook', 'type' => 'hardware', 'icon' => 'bi-laptop', 'color' => 'info'],
            ['name' => 'Printer & Scanner', 'type' => 'hardware', 'icon' => 'bi-printer', 'color' => 'success'],
            ['name' => 'Server & NAS', 'type' => 'hardware', 'icon' => 'bi-server', 'color' => 'danger'],
            ['name' => 'Networking', 'type' => 'hardware', 'icon' => 'bi-router', 'color' => 'warning'],
            ['name' => 'Monitor & Display', 'type' => 'hardware', 'icon' => 'bi-display', 'color' => 'secondary'],
            ['name' => 'UPS & Power', 'type' => 'hardware', 'icon' => 'bi-battery-charging', 'color' => 'dark'],
            ['name' => 'Peripheral', 'type' => 'hardware', 'icon' => 'bi-keyboard', 'color' => 'info'],
            ['name' => 'Office Suite', 'type' => 'software', 'icon' => 'bi-file-earmark-text', 'color' => 'primary'],
            ['name' => 'Operating System', 'type' => 'software', 'icon' => 'bi-windows', 'color' => 'info'],
            ['name' => 'Antivirus & Security', 'type' => 'software', 'icon' => 'bi-shield-check', 'color' => 'success'],
            ['name' => 'Design & Multimedia', 'type' => 'software', 'icon' => 'bi-brush', 'color' => 'warning'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
