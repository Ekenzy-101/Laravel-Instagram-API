<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\PostComment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PostCommentType extends GraphQLType
{
    protected $attributes = [
        'name' => 'PostComment',
        'description' => 'A post comment',
        "model" => PostComment::class
    ];

    public function fields(): array
    {
        return [
            "id" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The id of a post comment"
            ],
            "content" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The content of the post comment"
            ],
            "post" => [
                "type" => Type::nonNull(GraphQL::type("Post")),
                "description" => "The pictures of the post comment"
            ],
            "user" => [
                "type" => Type::nonNull(GraphQL::type("User")),
                "description" => "The owner of the post comment"
            ],
            "replies" => [
                "type" => Type::listOf(Type::nonNull(GraphQL::type("ReplyComment"))),
                "description" => "The replies of the post comment"
            ],
        ];
    }
}
