<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Post;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Mutation;
class TogglePostLikeMutation extends Mutation
{
    protected $attributes = [
        'name' => 'togglePostLike',
        'description' => 'A mutation to toggle like of a post'
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
                "description" => "The id of the post to like"
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (!Str::isUuid($args["id"])) {
            throw new Error('Post not found');
        }

        $post = Post::find($args["id"]);

        if (!$post) {
            throw new Error('Post not found');
        }

        Auth::user()->likedPosts()->toggle($args["id"]);

        return "Success";
    }
}
