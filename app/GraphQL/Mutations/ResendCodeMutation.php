<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Mail\Verification;
use App\Models\User;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Mail;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class ResendCodeMutation extends Mutation
{
    protected $attributes = [
        'name' => 'resendCode',
        'description' => 'A mutation for resending the verification code'
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array
    {
        return [
            "email" => [
                "name" => "email",
                "type" => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $user = User::firstWhere('email', $args['email']);

        if (!$user) {
            throw new Error('User not found');
        }

        $user->sendEmailVerificationNotification();

        return "Success";
    }
}
