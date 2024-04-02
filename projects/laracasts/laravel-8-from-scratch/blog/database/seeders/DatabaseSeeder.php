<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Post::factory(3)->create([
            'category_id' => Category::factory()->create()->id
        ]);

        Post::factory(3)->create([
            'user_id' => User::factory()->create()->id
        ]);

        Post::factory(2)->create([
            'user_id' => User::factory()->create()->id
        ]);

        Post::factory(10)->create([
            'category_id' => 1
        ]);
    }
}
