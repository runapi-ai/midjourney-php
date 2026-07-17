<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Midjourney\Models\CompletedImageTaskResponse;
use RunApi\Midjourney\Models\ImageTaskResponse;
use RunApi\Midjourney\Types;

/** Edit image operations for Midjourney. */
readonly class EditImage extends TypedConfiguredResource
{
    /**
     * Create a edit image task and return immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   source_image_url: string,
     *   callback_url?: string,
     *   include_split_images?: bool,
     *   mask_url?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a edit image task. */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Create a edit image task and poll until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   source_image_url: string,
     *   callback_url?: string,
     *   include_split_images?: bool,
     *   mask_url?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedImageTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedImageTaskResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/midjourney/edit_image',
            'midjourney/edit-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::EDIT_IMAGE_MODELS,
            'edit-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
