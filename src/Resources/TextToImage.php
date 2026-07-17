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

/** Text to image operations for Midjourney. */
readonly class TextToImage extends TypedConfiguredResource
{
    /**
     * Create a text to image task and return immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   callback_url?: string,
     *   enable_prompt_translation?: bool,
     *   include_split_images?: bool
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a text to image task. */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Create a text to image task and poll until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   callback_url?: string,
     *   enable_prompt_translation?: bool,
     *   include_split_images?: bool
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
            '/api/v1/midjourney/text_to_image',
            'midjourney/text-to-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::TEXT_TO_IMAGE_MODELS,
            'text-to-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
