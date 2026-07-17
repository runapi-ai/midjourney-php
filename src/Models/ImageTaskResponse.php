<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Models;

use RunApi\Core\Models\TaskResponse;
use RunApi\Core\Support\Payload;

/** Async image task response with lifecycle status and output images. */
readonly class ImageTaskResponse extends TaskResponse
{
    /**
     * @param list<Image> $images Generated image files when the task has completed.
     * @param string|null $imageId
     * @param list<string> $actions
     * @param int|null $progress
     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(?string $id, string $status, ?string $error = null, public array $images = [], public ?string $imageId = null, public array $actions = [], public ?int $progress = null, array $raw = [])
    {
        parent::__construct(id: $id, status: $status, error: $error, raw: $raw === [] ? ['id' => $id, 'status' => $status, 'error' => $error, 'images' => array_map(static fn (Image $image): array => $image->toArray(), $images), 'image_id' => $imageId, 'actions' => $actions, 'progress' => $progress] : $raw);
    }

    /**
     * Hydrate a task status response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), images: self::images($raw), imageId: Payload::optionalString($raw, 'image_id'), actions: self::stringList($raw, 'actions'), progress: self::optionalInt($raw, 'progress'), raw: $raw);
    }

    /**
     * @param array<string, mixed> $raw
     *
     * @return list<Image>
     */
    protected static function images(array $raw, bool $required = false): array
    {
        return Payload::listOf($raw, 'images', Image::fromArray(...), $required);
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

    /**
 * @param array<string, mixed> $raw
 * @return list<string>
 */
    protected static function stringList(array $raw, string $key): array
    {
        $values = $raw[$key] ?? null;
        if ($values === null) {
            return [];
        }
        if (!is_array($values)) {
            throw new \RunApi\Core\Errors\ValidationException($key . ' must be an array');
        }
        $result = [];
        foreach ($values as $index => $value) {
            if (!is_string($value)) {
                throw new \RunApi\Core\Errors\ValidationException($key . '[' . $index . '] must be a string');
            }
            $result[] = $value;
        }

        return $result;
    }
}
