<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Post;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PostType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Post',
        'description' => 'A post',
        "model" => Post::class
    ];

    public function fields(): array
    {
        return [
            "id" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The id of a post"
            ],
            "caption" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The title of the post"
            ],
            "image_urls" => [
                "type" => Type::listOf(Type::nonNull(Type::string())),
                "description" => "The pictures of the post"
            ],
            "location" => [
                "type" => Type::string(),
                "description" => "The location of the creation of post"
            ],
            "created_at" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The time when the post was created",
                "resolve" => function ($root, $args) {
                    return Carbon::parse($root->created_at)->diffForHumans();
                }
            ],
            "user" => [
                "type" => Type::nonNull(GraphQL::type("User")),
                "description" => "The owner of the post"
            ],
            "comments" => [
                "args" =>   [
                    "count" => [
                        "type" => Type::int(),
                    ]
                ],
                "type" => Type::listOf(Type::nonNull(GraphQL::type("PostComment"))),
                "description" => "The owner of the post",
                "resolve" => function ($root, $args) {
                    if(count($root->comments) <= 2 || !$args["count"]) {
                        return $root->comments()->latest()->get();
                    }
                    return collect($root->comments()->latest()->get())->slice(0, $args["count"]);
                }
            ],
            "commentsCount" => [
                "type" => Type::nonNull(Type::int()),
                "description" => "The no of comments that the post has",
                "resolve" => function ($root, $args) {
                    return $root->comments->count();
                }
            ],
            "likes" => [
                "type" => Type::listOf(Type::nonNull(GraphQL::type("User"))),
                "description" => "The list of users that liked the post"
            ],
            "likesCount" => [
                "type" => Type::nonNull(Type::int()),
                "description" => "The no of likes that the post has",
                "resolve" => function ($root, $args) {
                    return $root->likes->count();
                }
            ],
            "saves" => [
                "type" => Type::listOf(Type::nonNull(GraphQL::type("User"))),
                "description" => "The list of users that saved the post"
            ],
        ];
    }
}
