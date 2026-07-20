<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\Midjourney\Models\ShortenPromptResponse;

/** Prompt shortening operations for Midjourney. */
readonly class ShortenPrompt extends SyncResource
{
    /** @param array{prompt: string} $params */
    public function run(array $params, ?RequestOptions $options = null): ShortenPromptResponse
    {
        $response = parent::run($params, $options);

        /** @var ShortenPromptResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/midjourney/shorten_prompt',
            'midjourney/shorten-prompt',
            ShortenPromptResponse::class,
        );
    }
}
