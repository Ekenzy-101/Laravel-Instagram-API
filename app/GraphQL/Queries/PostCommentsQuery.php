<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\PostComment;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class PostCommentsQuery extends Query
{
    protected $attributes = [
        'name' => 'comments',
        'description' => 'A query to get all comments for a certain post'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type("PostComment"));
    }

    public function args(): array
    {
        return [
            "post_id" => [
                "type" => Type::nonNull(Type::string()),
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if(!Str::isUuid($args["post_id"])) {
            return null;
        }

        return PostComment::select($select)->with($with)->where('post_id', $args["post_id"])->get() ;
    }
}
