<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class ToggleFollowMutation extends Mutation
{
    protected $attributes = [
        'name' => 'toggleFollow',
        'description' => 'A mutation to toggle the follow of user'
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
                "description" => "The id of the user to follow"
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (!Str::isUuid($args["id"])) {
            throw new Error('User not found');
        }

        $user = User::find($args["id"]);

        if (!$user) {
            throw new Error('User not found');
        }

        Auth::user()->following()->toggle($args["id"]);

        return "Success";
    }
}
