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

/** Extends the first video from a completed Midjourney image-to-video task. */
readonly class ExtendVideo extends TypedConfiguredResource
{
    private const ENDPOINT = '/api/v1/midjourney/extend_video';
    private const ACTION = 'midjourney/extend-video';
    private const MODEL = 'midjourney-image-to-video';

    /**
     * Submit a first-video extension task and return immediately with a task id.
     *
     * @param array{
     *   source_task_id: string,
     *   callback_url?: string,
     *   prompt?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        $params = $this->compact($params);
        $this->validator->validate(self::ACTION, self::MODEL, ['model' => self::MODEL] + $params);

        return TaskCreateResponse::fromArray($this->http->request('post', self::ENDPOINT, [
            'body' => $params,
            'options' => $options,
        ]));
    }

    /** Fetch the current status of a first-video extension task. */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submit a first-video extension task and poll until it completes.
     *
     * @param array{
     *   source_task_id: string,
     *   callback_url?: string,
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
            self::ENDPOINT,
            self::ACTION,
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::EXTEND_VIDEO_MODELS,
            'extend-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
