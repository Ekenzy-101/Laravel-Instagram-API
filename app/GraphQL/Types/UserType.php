<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
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
            "image_url" => [
                "type" => Type::nonNull(Type::string()),
                "description" => "The url of the profile picture of the user"
            ],
            "posts" => [
                "type" => Type::listOf(GraphQL::type("Post")),
                "description" => "The posts created by this user"
            ],
            "stories" => [
                "type" => GraphQL::type("[Story!]"),
                "description" => "The stories created by this user"
            ],
            "likedPosts" => [
                "type" => Type::listOf(GraphQL::type("Post")),
                "description" => "The posts the user has liked"
            ],
            "likedComments" => [
                "type" => Type::listOf(GraphQL::type("PostComment")),
                "description" => "The comments the user has liked"
            ],
            "likedReplies" => [
                "type" => Type::listOf(GraphQL::type("ReplyComment")),
                "description" => "The replies the user has liked"
            ],
            "savedPosts" => [
                "type" => Type::listOf(GraphQL::type("Post")),
                "description" => "The posts the user has saved",
                "resolve" => function ($root, $args) {
                    if(Auth::id() !== $root->id) {
                        return [];
                    }
                    return $root->savedPosts;
                }
            ],
            "followers" => [
                "type" => Type::listOf(GraphQL::type("User")),
                "description" => "The people that are following the user"
            ],
            "following" => [
                "type" => Type::listOf(GraphQL::type("User")),
                "description" => "The people the user is following"
            ],
            "followingCount" => [
                "type" => Type::nonNull(Type::int()),
                "description" => "The no of people the user is following",
                "resolve" => function ($root, $args) {
                    return $root->following->count();
                }
            ],
            "followersCount" => [
                "type" => Type::nonNull(Type::int()),
                "description" => "The no of people that are following the user",
                "resolve" => function ($root, $args) {
                    return $root->followers->count();
                }
            ],
        ];
    }
}
