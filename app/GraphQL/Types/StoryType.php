<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Story;
use Carbon\Carbon;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class StoryType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Story',
        'description' => 'A story',
        'model' => Story::class,
    ];

    public function fields(): array
    {
        return [
            "id" => [
                "type" => GraphQL::type("String!"),
                "description" => "The id of the story"
            ],
            "image_url" => [
                "type" => GraphQL::type("String!"),
                "description" => "The url of the story's picture"
            ],
            "user" => [
                "type" => GraphQL::type("User!"),
                "description" => "The user that created the story"
            ],
            "created_at" => [
                "type" => GraphQL::type("String!"),
                "description" => "The time when the post was created",
                "resolve" => function ($root, $args) {
                    return Carbon::parse($root->created_at)->diffForHumans();
                }
            ],
        ];
    }
}
