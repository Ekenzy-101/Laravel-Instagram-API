<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Mutation;

class CreateStoryMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createStory',
        'description' => 'A mutation to create a story'
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

        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $s3Client = new S3Client([
            'endpoint' => getenv("AWS_ENDPOINT"),
            'region' => getenv("AWS_DEFAULT_REGION"),
            'use_path_style_endpoint' => true,
            'version' => '2006-03-01',
            'signature_version' => 'v4',
        ]);
        
        $story_id = Str::orderedUuid()->toString();
        $bucket_name = getenv("AWS_BUCKET");
        $url = getenv("AWS_URL");
        $key = "stories/{$story_id}.jpg";
        $image_url = "{$url}/{$bucket_name}/{$key}";

        try {
            $cmd = $s3Client->getCommand('PutObject', [
                'Bucket' => $bucket_name,
                'Key' => $key,
                'ContentType' => 'image/jpeg',
            ]);

            $request = $s3Client->createPresignedRequest($cmd, '+5 minutes');
            $presignedUrl= (string)$request->getUri();
        } catch (S3Exception $e) {
            throw new Error($e->getMessage());
        }

        Auth::user()->stories()->create([
            'id' => $story_id,
            'image_url' => $image_url,
        ]);

        return $presignedUrl;
    }
}
