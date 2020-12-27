<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\ReplyComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'username' => 'kenzy'
        ]);

        $post = Post::factory()->for($user)->create();

        $comment = PostComment::factory()->for($post)->create([
            'user_id' => $user->id
        ]);

        ReplyComment::factory()->count(3)->for($comment, 'comment')->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }
}
