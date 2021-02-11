<?php

namespace App\Providers;

use App\GraphQL\Types\PostCommentType;
use App\GraphQL\Types\PostType;
use App\GraphQL\Types\ReplyCommentType;
use App\GraphQL\Types\StoryType;
use App\GraphQL\Types\UserType;
use Illuminate\Support\ServiceProvider;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        GraphQL::addType(ReplyCommentType::class,  "ReplyComment");
        GraphQL::addType(PostCommentType::class,  "PostComment");
        GraphQL::addType(PostType::class,  "Post");
        GraphQL::addType(UserType::class,  "User");
        GraphQL::addType(StoryType::class,  "Story");
    }
}
