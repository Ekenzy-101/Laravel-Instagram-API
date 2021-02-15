<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\ReplyComment;
use App\Models\Story;
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
            "name" => "Tyler Smith",
            "username" => "ekene1",
            "email" => "ekeneonyekaba1@gmail.com"
        ]);
        User::factory()->create([
            "name" => "John Doe",
            "username" => "johndoe__",
            "email" => "ekeneonyekaba2@gmail.com"
        ]);
        User::factory()->create([
            "name" => "Bruce Lee",
            "username" => "_brucelee_",
            "email" => "ekeneonyekaba3@gmail.com"
        ]);
        User::factory()->create([
            "name" => "Tom Cruise",
            "username" => "tom_cruise",
            "email" => "ekeneonyekaba4@gmail.com"
        ]);
        User::factory()->create([
            "name" => "Cristiano Ronaldo",
            "username" => "cristiano",
            "email" => "ekeneonyekaba5@gmail.com"
        ]);
        User::factory()->create([
            "name" => "Leo Messi",
            "username" => "leomessi",
            "email" => "ekeneonyekaba6@gmail.com"
        ]);
        User::factory()->create([
            "name" => "Kylian Mbappe",
            "username" => "kylian_mbappe",
            "email" => "ekeneonyekaba7@gmail.com"
        ]);
        User::factory()->create([
            "email" => "ekeneonyekaba8@gmail.com"
        ]);
        User::factory()->create([
            "email" => "ekeneonyekaba9@gmail.com"
        ]);
        User::factory()->create([
            "email" => "ekeneonyekaba10@gmail.com"
        ]);
        User::factory()->create([
            "email" => "ekeneonyekaba11@gmail.com"
        ]);
    }
}
