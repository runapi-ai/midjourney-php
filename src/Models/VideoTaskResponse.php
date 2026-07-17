<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Models;

use RunApi\Core\Models\TaskResponse;
use RunApi\Core\Support\Payload;

/** Async video task response with lifecycle status and output videos. */
readonly class VideoTaskResponse extends TaskResponse
{
    /**
     * @param list<Video> $videos Generated video files when the task has completed.
     * @param string|null $videoId
     * @param int|null $progress
     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(?string $id, string $status, ?string $error = null, public array $videos = [], public ?string $videoId = null, public ?int $progress = null, array $raw = [])
    {
        parent::__construct(id: $id, status: $status, error: $error, raw: $raw === [] ? ['id' => $id, 'status' => $status, 'error' => $error, 'videos' => array_map(static fn (Video $video): array => $video->toArray(), $videos), 'video_id' => $videoId, 'progress' => $progress] : $raw);
    }

    /**
     * Hydrate a task status response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), videos: self::videos($raw), videoId: Payload::optionalString($raw, 'video_id'), progress: self::optionalInt($raw, 'progress'), raw: $raw);
    }

    /**
     * @param array<string, mixed> $raw
     *
     * @return list<Video>
     */
    protected static function videos(array $raw, bool $required = false): array
    {
        return Payload::listOf($raw, 'videos', Video::fromArray(...), $required);
    }

    /** @param array<string, mixed> $raw */
    protected static function optionalInt(array $raw, string $key): ?int
    {
        $value = $raw[$key] ?? null;
        if ($value === null) {
            return null;
        }
        if (!is_int($value)) {
            throw new \RunApi\Core\Errors\ValidationException($key . ' must be an integer');
        }

        return $value;
    }
}
