<?php

declare(strict_types=1);

use App\GraphQL\Mutations\CreateCommentMutation;
use App\GraphQL\Mutations\CreatePostMutation;
use App\GraphQL\Mutations\CreateReplyMutation;
use App\GraphQL\Mutations\CreateStoryMutation;
use App\GraphQL\Mutations\DeleteCommentMutation;
use App\GraphQL\Mutations\DeletePostMutation;
use App\GraphQL\Mutations\DeleteProfilePictureMutation;
use App\GraphQL\Mutations\DeleteReplyMutation;
use App\GraphQL\Mutations\ResendCodeMutation;
use App\GraphQL\Mutations\ToggleCommentLikeMutation;
use App\GraphQL\Mutations\ToggleFollowMutation;
use App\GraphQL\Mutations\TogglePostLikeMutation;
use App\GraphQL\Mutations\TogglePostSaveMutation;
use App\GraphQL\Mutations\ToggleReplyLikeMutation;
use App\GraphQL\Mutations\UpdatePasswordMutation;
use App\GraphQL\Mutations\UpdateProfileMutation;
use App\GraphQL\Mutations\UpdateProfilePictureMutation;
use App\GraphQL\Queries\PostCommentsQuery;
use App\GraphQL\Queries\PostQuery;
use App\GraphQL\Queries\PostsQuery;
use App\GraphQL\Queries\ProfileQuery;
use App\GraphQL\Queries\ReplyCommentsQuery;
use App\GraphQL\Queries\UserQuery;

return [
    'prefix' => 'graphql',

    'routes' => '{graphql_schema?}',

    'controllers' => \Rebing\GraphQL\GraphQLController::class.'@query',

    'middleware' => [],

    'route_group_attributes' => [],

    'default_schema' => 'default',

    'schemas' => [
        'default' => [
            'query' => [
                PostQuery::class,
                PostsQuery::class,
                PostCommentsQuery::class,
                ProfileQuery::class,
                ReplyCommentsQuery::class,
                UserQuery::class,
            ],
            'mutation' => [

                CreatePostMutation::class,
                CreateCommentMutation::class,
                CreateReplyMutation::class,
                CreateStoryMutation::class,
                DeletePostMutation::class,
                DeleteProfilePictureMutation::class,
                DeleteReplyMutation::class,
                DeleteCommentMutation::class,
                ResendCodeMutation::class,
                TogglePostLikeMutation::class,
                TogglePostSaveMutation::class,
                ToggleCommentLikeMutation::class,
                ToggleReplyLikeMutation::class,
                ToggleFollowMutation::class,
                UpdatePasswordMutation::class,
                UpdateProfileMutation::class,
                UpdateProfilePictureMutation::class
            ],
            'method' => ['get', 'post'],
        ],
    ],

    'types' => [],

    'lazyload_types' => false,

    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    'params_key' => 'variables',

    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => null,
        'disable_introspection' => false,
    ],

    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,

    'graphiql' => [
        'prefix' => '/graphiql',
        'controller' => \Rebing\GraphQL\GraphQLController::class.'@graphiql',
        'middleware' => [],
        'view' => 'graphql::graphiql',
        'display' => env('ENABLE_GRAPHIQL', true),
    ],

    'defaultFieldResolver' => null,

    'headers' => [],

    /*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
    'json_encoding_options' => 0,
];
