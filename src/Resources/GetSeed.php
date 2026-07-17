<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\Midjourney\Models\GetSeedResponse;

/** Get seed operations for Midjourney. */
readonly class GetSeed extends SyncResource
{
    /**
     * Run get seed and return its response.
     *
     * @param array{
     *   image_id: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): GetSeedResponse
    {
        $response = parent::run($params, $options);

        /** @var GetSeedResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/midjourney/get_seed',
            'midjourney/get-seed',
            GetSeedResponse::class,
        );
    }
}
