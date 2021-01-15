<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Post;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateCommentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createComment',
        'description' => 'A mutation to comment on a post'
    ];

    public function authorize($root, array $args, $ctx, ?ResolveInfo $resolveInfo = null, ?Closure $getSelectFields = null): bool
    {
        return !!Auth::user();
    }

    public function type(): Type
    {
        return GraphQL::type("PostComment");
    }

    public function args(): array
    {
        return [
            "post_id" => [
                "type" => Type::string()
            ],
            "content" => [
                "type" => Type::nonNull(Type::string())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $validator = Validator::make($args, [
            "content" => ["required","max:300"]
        ]);

        if($validator->fails()) {
            throw new Error($validator->errors()->first("content"));
        }

        if($args["post_id"]) {
            if (!Str::isUuid($args["post_id"])) {
                throw new Error("Post not found");
            }

            $post = Post::find($args["post_id"]);

            if(!$post) {
                throw new Error("Post not found");
            }

            $comment = $post->comments()->create([
                "id" => Str::orderedUuid()->toString(),
                "user_id" => Auth::user()->id,
                "content" => $args["content"]
            ]);

            return $comment;
        }

        throw new Error("Invalid Credentials");
    }
}
