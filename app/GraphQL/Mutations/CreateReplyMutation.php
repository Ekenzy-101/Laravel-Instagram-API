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
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateReplyMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createReply',
        'description' => 'A mutation to reply a post comment'
    ];

    public function authorize($root, array $args, $ctx, ?ResolveInfo $resolveInfo = null, ?Closure $getSelectFields = null): bool
    {
        return !!Auth::user();
    }

    public function type(): Type
    {
        return GraphQL::type("ReplyComment");
    }

    public function args(): array
    {
        return [
            "comment_id" => [
                "type" => Type::string()
            ],
            "content" => [
                "type" => Type::nonNull(Type::string())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if($args["comment_id"]) {
            if (!Str::isUuid($args["comment_id"])) {
                throw new Error("Comment not found");
            }

            $comment = PostComment::find($args["comment_id"]);

            if(!$comment) {
                throw new Error("Comment not found");
            }

            $reply = $comment->replies()->create([
                "id" => Str::orderedUuid()->toString(),
                "user_id" => Auth::user()->id,
                "post_id" => $comment->post->id,
                "content" => $args["content"]
            ]);

            return $reply;
        }

        throw new Error("Invalid Credentials");
    }
}
