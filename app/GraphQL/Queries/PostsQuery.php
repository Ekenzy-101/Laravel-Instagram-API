<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Post;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class PostsQuery extends Query
{
    protected $attributes = [
        'name' => 'posts',
        'description' => 'A query to get all posts'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type("Post"));
    }

    // public function args(): array
    // {
    //     return [

    //     ];
    // }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();
        return Post::with('user')->get();
    }
}
