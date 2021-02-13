<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Story;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Str;

class DeleteStoryMutation extends Mutation
{
    protected $attributes = [
        "name" => "deleteStory",
        "description" => "A mutation to delete a story"
    ];

    public function authorize($root, array $args, $ctx, ?ResolveInfo $resolveInfo = null, ?Closure $getSelectFields = null): bool
    {
        return !!Auth::id();
    }

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array
    {
        return [
            "id" => [
                "type" => Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (!Str::isUuid($args["id"])) {
            throw new Error('Story not found');
        }

        $story = Story::find($args["id"]);

        if (!$story) {
            throw new Error('Story not found');
        }

        $bucket_name = getenv("AWS_BUCKET");
        $region = getenv("AWS_DEFAULT_REGION");

        $s3Client = new S3Client([
            "region" => $region,
            "version" => '2006-03-01',
            "signature_version" => 'v4'
        ]);

        $key =  "stories/{$story->id}/.jpg";

        try {
            $s3Client->deleteObject([
                'Bucket' => $bucket_name,
                'Key' => $key
            ]);
        } catch (S3Exception $e) {
            throw new Error($e->getMessage());
        }

        if ($story->user->id !== Auth::id()) {
            throw new Error('You are not authorized to delete this story');
        }

        $story->delete();

        return "Success";
    }
}
