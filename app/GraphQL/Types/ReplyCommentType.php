<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\ReplyComment;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ReplyCommentType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ReplyComment',
        'description' => 'A reply comment',
        'model' => ReplyComment::class,
    ];

    public function fields(): array
    {
        return [
            "id" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The id of a reply comment"
            ],
            "content" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The content of the reply comment"
            ],
            "comment" => [
                "type" => Type::nonNull(GraphQL::type("PostComment")),
                "description" => "The comment of the reply comment"
            ],
            "likes" => [
                "type" => Type::listOf(Type::nonNull(GraphQL::type("User"))),
                "description" => "The likes of the reply comment"
            ],
            "user" => [
                "type" => Type::nonNull(GraphQL::type("User")),
                "description" => "The owner of the reply comment"
            ],
            "post" => [
                "type" => Type::nonNull(GraphQL::type("Post")),
                "description" => "The post of the reply comment"
            ],
            "created_at" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The time when the post was created",
                "resolve" => function ($root, $args) {
                    return Carbon::parse($root->created_at)->diffForHumans();
                }
            ],
        ];
    }
}
