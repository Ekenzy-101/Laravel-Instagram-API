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

class DeleteReplyMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteReply',
        'description' => 'A mutation to delete a reply'
    ];

    public function authorize($root, array $args, $ctx, ?ResolveInfo $resolveInfo = null, ?Closure $getSelectFields = null): bool
    {
        return !!Auth::id();
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
            ],
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

        if ($reply->user->id !== Auth::id()) {
            throw new Error('You are not authorized to delete this comment');
        }

        $reply->delete();

        return "Success";
    }
}
