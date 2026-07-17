<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\Midjourney\Models\ImageToPromptResponse;

/** Image to prompt operations for Midjourney. */
readonly class ImageToPrompt extends SyncResource
{
    /**
     * Run image to prompt and return its response.
     *
     * @param array{
     *   source_image_url: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): ImageToPromptResponse
    {
        $response = parent::run($params, $options);

        /** @var ImageToPromptResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/midjourney/image_to_prompt',
            'midjourney/image-to-prompt',
            ImageToPromptResponse::class,
        );
    }
}
