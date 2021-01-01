<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Post;
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
            "user" => [
                "type" => Type::nonNull(GraphQL::type("User")),
                "description" => "The owner of the post"
            ],
            "comments" => [
                "type" => Type::listOf(Type::nonNull(GraphQL::type("PostComment"))),
                "description" => "The owner of the post"
            ],
        ];
    }
}
