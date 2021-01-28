<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\ReplyComment;
use Closure;
use Illuminate\Support\Str;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ReplyCommentsQuery extends Query
{
    protected $attributes = [
        'name' => 'replies',
        'description' => 'A query to get all replies for a certain post comment'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type("ReplyComment"));
    }

    public function args(): array
    {
        return [
            "comment_id" => [
                "name" => "comment_id",
                "type" => Type::string(),
                "rules" => ["required"]
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if(!Str::isUuid($args["comment_id"])) {
            return null;
        }

        return ReplyComment::select($select)->with($with)->where('comment_id', $args["comment_id"])->get();
    }
}
