<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A user',
        'model' => User::class
    ];

    public function fields(): array
    {
        return [
            "id" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The id of the user"
            ],
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
                "description" => "Litte summary about the user"
            ],
            "image_url" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The url of the profile picture of the user"
            ],
            "posts" => [
                "type" => Type::listOf(GraphQL::type("Post")),
                "description" => "The posts created by this user"
            ],
        ];
    }
}
