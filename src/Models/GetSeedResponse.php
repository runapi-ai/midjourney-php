<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/** Response returned by get seed. */
readonly class GetSeedResponse extends BaseModel
{
    /**
     * @param int|null $seed
     * @param string|null $error
     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(public ?int $seed = null, public ?string $error = null, array $raw = [])
    {
        parent::__construct($raw === [] ? ['seed' => $seed, 'error' => $error] : $raw);
    }

    /**
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(seed: self::optionalInt($raw, 'seed'), error: Payload::optionalString($raw, 'error'), raw: $raw);
    }

    /** @param array<string, mixed> $raw */
    private static function optionalInt(array $raw, string $key): ?int
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
