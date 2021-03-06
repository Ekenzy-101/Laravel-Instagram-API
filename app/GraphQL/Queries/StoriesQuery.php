<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class StoriesQuery extends Query
{
    protected $attributes = [
        'name' => 'stories',
        'description' => 'A query to return list of stories'
    ];

    public function type(): Type
    {
        return GraphQL::type("[Story!]");
    }

    public function args(): array
    {
        return [
            "username" => [
                'name' => "username",
                'type' => GraphQL::type("String")
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if($args["username"]) {
            $user = User::firstWhere("username", $args["username"]);

            if(!$user) {
                throw new Error('User not found');
            }
            return $user->stories;
        }

        if(Auth::user()) {
            // Return both his first story and following's first story
            $stories = [];
            $following_users = Auth::user()->following;
            foreach ($following_users as $user) {
                if($user->stories->first()) {
                    array_push($stories, $user->stories[0]);
                }
            }
            if(Auth::user()->stories->first()) {
                array_push($stories, Auth::user()->stories[0]);
            }
            return collect($stories)->sortByDesc("created_at");
        }

        return [];
    }
}
