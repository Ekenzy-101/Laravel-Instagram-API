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

class UpdateProfilePictureMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateProfilePicture',
        'description' => "A mutation to update the authenticated user's profile picture"
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
        $bucket_name = getenv("AWS_BUCKET");
        $region = getenv("AWS_DEFAULT_REGION");

        $s3Client = new S3Client([
            'region' => $region,
            'version' => '2006-03-01',
            'signature_version' => 'v4'
        ]);

        $id =  Str::orderedUuid()->toString();
        $key = "users/{$id}.jpg";
        $image_url = "https://{$bucket_name}.s3.amazonaws.com/{$key}";

        try {
            if(Auth::user()->image_url) {
                $s3Client->deleteObject([
                'Bucket' => $bucket_name,
                'Key' => Auth::user()->object_key
                ]);
            }
            $cmd = $s3Client->getCommand('PutObject', [
                'Bucket' => $bucket_name,
                'Key' => $key,
                'ContentType' => 'image/jpeg',
                'ACL' => 'public-read'
            ]);

            $request = $s3Client->createPresignedRequest($cmd, '+40 minutes');
            $presignedUrl= (string)$request->getUri();
        } catch (S3Exception $e) {
            throw new Error($e->getMessage());
        }

        Auth::user()->update(["image_url" => $image_url, "object_key" => $key]);

        return $presignedUrl;
    }
}
