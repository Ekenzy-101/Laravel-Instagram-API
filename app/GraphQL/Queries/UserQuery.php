<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
class UserQuery extends Query
{
    protected $attributes = [
        'name' => 'user',
        'description' => 'A query to get a certain user'
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            "username" => [
                'type' => GraphQL::type("String")
            ],
            "token" => [
                'type' => GraphQL::type("String")
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if($args['username']) {
            return User::with('posts')->firstWhere('username', $args['username']);
        }

        if($args["token"]) {
            $rows = DB::select("select * from password_resets");
            $user = [];
            foreach ($rows as $row) {
                if(Hash::check($args["token"], $row->token)) {
                    $user[] = User::firstWhere("email", $row->email);
                }
            }
            return count($user) ? $user[0] : null;
        }

        return null;
    }
}
