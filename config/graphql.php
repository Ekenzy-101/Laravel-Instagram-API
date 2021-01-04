<?php

declare(strict_types=1);

use App\GraphQL\Mutations\ResendCodeMutation;
use App\GraphQL\Mutations\UpdateProfileMutation;
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
                UserQuery::class,
                PostQuery::class,
                PostsQuery::class,
                PostCommentsQuery::class,
                ReplyCommentsQuery::class,
                ProfileQuery::class,
            ],
            'mutation' => [
                ResendCodeMutation::class,
                UpdateProfileMutation::class,
            ],
            'method' => ['get', 'post'],
        ],
    ],

    'types' => [],

    'lazyload_types' => false,

    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key' => 'variables',

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://webonyx.github.io/graphql-php/security
     * for details. Disabled by default.
     */
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

    /*
     * Any headers that will be added to the response returned by the default controller
     */
    'headers' => [],

    /*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
    'json_encoding_options' => 0,
];
