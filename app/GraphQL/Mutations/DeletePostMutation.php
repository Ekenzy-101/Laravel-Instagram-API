<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Post;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Str;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class DeletePostMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deletePost',
        'description' => 'A mutation to delete a post'
    ];

    public function authorize($root, array $args, $ctx, ?ResolveInfo $resolveInfo = null, ?Closure $getSelectFields = null): bool
    {
        return !!Auth::user();
    }

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array
    {
        return [
            "id" => [
                "name" => "id",
                "type" => Type::nonNull(Type::string()),
                "description" => "The id of the post to delete"
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (!Str::isUuid($args["id"])) {
            throw new Error('Post not found');
        }

        $post = Post::find($args["id"]);

        if (!$post) {
            throw new Error('Post not found');
        }

        if ($post->user->id !== Auth::id()) {
            throw new Error('You are not authorized to delete this post');
        }
        
        $s3Client = new S3Client([
            'endpoint' => getenv("AWS_ENDPOINT"),
            'region' => getenv("AWS_DEFAULT_REGION"),
            'use_path_style_endpoint' => true,
            'version' => '2006-03-01',
            'signature_version' => 'v4'
        ]);
        
        $bucket_name = getenv("AWS_BUCKET");
        try {
            foreach ($post->keys as $key) {
                $s3Client->deleteObject([
                    'Bucket' => $bucket_name,
                    'Key' => $key
                ]);
            }
        } catch (S3Exception $e) {
            throw new Error($e->getMessage());
        }

        $post->delete();

        return "Success";
    }
}
