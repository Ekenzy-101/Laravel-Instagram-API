<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Rebing\GraphQL\Support\Mutation;

class UpdatePasswordMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updatePassword',
        'description' => "A mutation to update the authenticated user's password"
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
            "password" => [
                "type" => Type::string(),
                "description" => "The old password of the user"
            ],
            "new_password" => [
                "type" => Type::string(),
                "description" => "The new password of the user"
            ],
            "new_password_confirmation" => [
                "type" => Type::string(),
                "description" => "The confirmation of the new password of the user"
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $validator = Validator::make($args, [
            'new_password' => ['required', 'min:6', 'confirmed'],
        ], [
            "new_password.min" => "Create a password at least 6 characters long",
            "new_password.required" => "Create a password at least 6 characters long",
            "new_password.confirmed" => "Please make sure both passwords match",
        ]);

        if ($validator->errors()->has('new_password')) {
            throw new Error($validator->errors()->first("new_password"));
        }

        if ($args["new_password"] === "password") {
            throw new Error("The password is too easy to guess. Please create a new one");
        }

        if(!Hash::check($args["password"], Auth::user()->password)) {
            throw new Error("You old password was entered incorrectly. Please enter it again");
        }

        if(Hash::check($args["new_password"], Auth::user()->password)) {
            throw new Error("Create a new password that isn't your current password");
        }

        Auth::user()->update(["password" => Hash::make($args["new_password"])]);

        return "Success";
    }
}
