<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing users from the database
        $users = User::all();

        // For each user, create 3 posts
        foreach ($users as $user) {
            Post::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}

