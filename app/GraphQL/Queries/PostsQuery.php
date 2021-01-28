<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Post;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class PostsQuery extends Query
{
    protected $attributes = [
        'name' => 'posts',
        'description' => 'A query to get posts by category'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type("Post"));
    }

    public function args(): array
    {
        return [
            "random" => [
                "type" => Type::boolean(),
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {

        if($args["random"]) {
            return Post::all()->shuffle();
        }

        if(Auth::user()) {
            $all_posts = [];
            $following_users = Auth::user()->following;
            foreach ($following_users as $user) {
                foreach ($user->posts as $post) {
                    array_push($all_posts, $post);
                }
            }
            foreach (Auth::user()->posts as $user_post) {
                array_push($all_posts, $user_post);
            };
            return collect($all_posts)->sortByDesc("created_at");
        }

        return [];
    }
}
