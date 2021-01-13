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
        User::factory()->create([
            'username' => 'emmanuel1',
            'name' => 'Onyekaba Emmanuel',
            'email' => 'emmanuelonyekaba1@gmail.com'
        ]);

        User::factory()->create([
            'username' => 'ekene1',
            'name' => 'John Smith',
            'email' => 'ekeneonyekaba1@gmail.com'
        ]);

        User::factory()->create([
            'username' => 'ekene2',
            'name' => 'Wilson Tyler',
            'email' => 'ekeneonyekaba2@gmail.com'
        ]);

        User::factory()->create([
            'username' => 'ekene3',
            'name' => 'Anthony Joshua',
            'email' => 'ekeneonyekaba3@gmail.com'
        ]);
    }
}
