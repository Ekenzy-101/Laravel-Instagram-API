<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class UpdateProfileMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateProfile',
        'description' => "A mutation to update the authenticated user's profile"
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
            "name" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The name of the user"
            ],
            "username" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The username of the user"
            ],
            "email" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The email of the user"
            ],
            "gender" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The gender of the user"
            ],
            "bio" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "Little summary about the user"
            ],
            "phone_no" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The user's phone number"
            ],
            "website" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The user's website"
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $validator = Validator::make($args, [
            'website' => ['url'],
            'email' => ['email', 'required'],
            'phone_no' => ['phone:AUTO,NG'],
            'username' => ['required', 'between:6,30', 'regex:/^[a-z0-9._]+$/'],
        ]);

        if ($validator->errors()->has('website')) {
            throw new Error('Enter a valid website');
        }

        if ($validator->errors()->has('username')) {
            throw new Error('Enter a valid username');
        }

        if ($validator->errors()->has('email')) {
            throw new Error('You need an email or confirmed phone number');
        }

        if ($validator->errors()->has('phone_no')) {
            throw new Error('Looks like your phone number may be incorrect. Please try entering your full number, including the country code');
        }

        $user = User::select('email', 'id')->firstWhere('email', strtolower($args['email']));
        if ($user && $user->id !== Auth::id()) {
            throw new Error("Another account is using {$args['email']}");
        }

        $user = User::select('username', 'id')->firstWhere('username', $args['username']);
        if ($user && $user->id !== Auth::id()) {
            throw new Error("The username isn't available. Please try another");
        }

        User::find(Auth::id())->update([
            'username' => $args['username'],
            'name' => $args['name'],
            'email' => strtolower($args['email']),
            'bio' => $args['bio'],
            'phone_no' => $args['phone_no'],
        ]);

        return "Success";
    }
}
