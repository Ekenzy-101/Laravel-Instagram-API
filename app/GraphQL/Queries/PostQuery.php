<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Post;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class PostQuery extends Query
{
    protected $attributes = [
        'name' => 'post',
        'description' => 'A query to get a particular post'
    ];

    public function type(): Type
    {
        return GraphQL::type("Post");
    }

    public function args(): array
    {
        return [
            "id" => [
                "name" => "id",
                "type" => Type::nonNull(Type::string()),
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if(!Str::isUuid($args["id"])) {
            return null;
        }

        return Post::select($select)->with($with)->firstWhere('id', $args["id"]);
    }
}
