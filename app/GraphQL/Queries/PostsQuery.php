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
            ],
            "post_id" => [
                "type" => Type::string(),
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {

        if($args["random"]) {
            // Return random posts
            return Post::all()->shuffle();
        }

        if($args["post_id"]) {
            // Return other posts created by this user
            $post =  Post::find($args["post_id"]);
            if(!$post) {
                return [];
            }

            return $post->user->posts->except($post->id);
        }

        if(Auth::user()) {
            // Return both his posts and following posts
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
