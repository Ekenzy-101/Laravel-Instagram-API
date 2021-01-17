<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\PostComment;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Str;


class ToggleCommentLikeMutation extends Mutation
{
    protected $attributes = [
        'name' => 'toggleCommentLike',
        'description' => 'A mutation to toggle like of a comment'
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
                "name" => "id",
                "type" => Type::nonNull(Type::string()),
                "description" => "The id of the comment to to like"
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (!Str::isUuid($args["id"])) {
            throw new Error('Comment not found');
        }

        $comment = PostComment::find($args["id"]);

        if (!$comment) {
            throw new Error('Comment not found');
        }

        Auth::user()->likedComments()->toggle($args["id"]);

        return "Success";
    }
}
