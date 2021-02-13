<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Closure;
use Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;

class DeleteProfilePictureMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteProfilePicture',
        'description' => "A mutation to reset the authenticated user's profile picture"
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
            "region" => $region,
            "version" => '2006-03-01',
            "signature_version" => 'v4'
        ]);

        $key =  Auth::user()->object_key;

        if($key) {
            try {
                $s3Client->deleteObject([
                    'Bucket' => $bucket_name,
                    'Key' => $key
                ]);
            } catch (S3Exception $e) {
                throw new Error($e->getMessage());
            }
        }

        Auth::user()->update(["image_url" => "", "object_key" => ""]);

        return "Success";
    }
}
