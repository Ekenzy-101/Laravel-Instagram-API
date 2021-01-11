<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Post;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class CreatePostMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createPost',
        'description' => 'A mutation to create a post'
    ];

    public function authorize($root, array $args, $ctx, ?ResolveInfo $resolveInfo = null, ?Closure $getSelectFields = null): bool
    {
        return !!Auth::user();
    }

    public function type(): Type
    {
        return Type::listOf(Type::string());
    }

    public function args(): array
    {
        return [
            "caption" => [
                "name" => "caption",
                "type" => Type::string()
            ],
            "location" => [
                "name" => "location",
                "type" => Type::string()
            ],
            "count" => [
                "name" => "count",
                "type" => Type::nonNull(Type::int())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $ids = range(1, $args["count"]);
        $post_id = Str::orderedUuid()->toString();

        $bucket_name = getenv("AWS_BUCKET");
        $region = getenv("AWS_DEFAULT_REGION");

        $s3Client = new S3Client([
            'region' => $region,
            'version' => '2006-03-01',
            'signature_version' => 'v4'
        ]);

        $image_urls = [];
        $object_keys = [];

        foreach ($ids as $id) {
            $object_key = "posts/{$post_id}/{$id}.jpg";
            $full_url = "https://{$bucket_name}.s3.amazonaws.com/{$object_key}";

            array_push($image_urls, $full_url);
            array_push($object_keys, $object_key);
        }

        $presignedUrls = [];
        try {
            foreach ($object_keys as $key) {
                $cmd = $s3Client->getCommand('PutObject', [
                    'Bucket' => $bucket_name,
                    'Key' => $key,
                ]);

                $request = $s3Client->createPresignedRequest($cmd, '+40 minutes');
                $presignedUrl= (string)$request->getUri();
                array_push($presignedUrls, $presignedUrl) ;
            }
        } catch (S3Exception $e) {
            throw new Error($e->getMessage());
        }

        Auth::user()->posts()->create([
            'id' => $post_id,
            'caption' => $args['caption'] ? $args['caption'] : "",
            'location' => $args['location'] ? $args['location']: "",
            'image_urls' => $image_urls,
            'keys' => $object_keys
        ]);

        return $presignedUrls;
    }
}
