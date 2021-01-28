<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\ReplyComment;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Str;


class ToggleReplyLikeMutation extends Mutation
{
    protected $attributes = [
        'name' => 'toggleReplyLike',
        'description' => 'A mutation to toggle like of a reply'
    ];

    public function authorize($root, array $args, $ctx, ?ResolveInfo $resolveInfo = null, ?Closure $getSelectFields = null): bool
    {
        return !!Auth::user();
    }

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array
    {
        return [
            "id" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The id of the reply to to like"
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (!Str::isUuid($args["id"])) {
            throw new Error('Comment not found');
        }

        $reply = ReplyComment::find($args["id"]);

        if (!$reply) {
            throw new Error('Comment not found');
        }

        Auth::user()->likedReplies()->toggle($args["id"]);

        return "Success";
    }
}
