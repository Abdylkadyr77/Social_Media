<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\User;
use App\Models\Post;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing users and posts
        $users = User::all();
        $posts = Post::all();

        // For each post, randomly assign 1 to 5 users as "likers"
        foreach ($posts as $post) {
            // Select a random number of users (between 1 and 5) to like this post
            $likers = $users->random(rand(1, 5));

            // Create a 'Like' record for each selected user on the current post
            foreach ($likers as $user) {
                Like::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                ]);
            }
        }
    }
}