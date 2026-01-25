<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateCommentsStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Comment::where('approved', true)->update(['status' => 'approved']);
        \App\Models\Comment::where('approved', false)->update(['status' => 'pending']);
    }
}
