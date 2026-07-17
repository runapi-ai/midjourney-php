<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Midjourney\Models\CompletedVideoTaskResponse;
use RunApi\Midjourney\Models\VideoTaskResponse;
use RunApi\Midjourney\Types;

/** Image to video operations for Midjourney. */
readonly class ImageToVideo extends TypedConfiguredResource
{
    /**
     * Create a image to video task and return immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   source_image_url: string,
     *   callback_url?: string,
     *   enable_loop?: bool,
     *   last_frame_image_url?: string,
     *   output_resolution?: string,
     *   prompt?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a image to video task. */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Create a image to video task and poll until it completes.
     *
     * @param array{
     *   model: string,
     *   source_image_url: string,
     *   callback_url?: string,
     *   enable_loop?: bool,
     *   last_frame_image_url?: string,
     *   output_resolution?: string,
     *   prompt?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/midjourney/image_to_video',
            'midjourney/image-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::IMAGE_TO_VIDEO_MODELS,
            'image-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
