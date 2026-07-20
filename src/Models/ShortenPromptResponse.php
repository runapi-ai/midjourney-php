<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Models;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Models\BaseModel;

/** Response returned by prompt shortening. */
readonly class ShortenPromptResponse extends BaseModel
{
    /**
     * @param list<string> $prompts
     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(public array $prompts, array $raw = [])
    {
        parent::__construct($raw === [] ? ['prompts' => $prompts] : $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        $values = $raw['prompts'] ?? null;
        if (!is_array($values)) {
            throw new ValidationException('prompts must be an array');
        }
        $result = [];
        foreach ($values as $index => $value) {
            if (!is_string($value) || trim($value) === '') {
                throw new ValidationException('prompts[' . $index . '] must be a non-empty string');
            }
            $result[] = $value;
        }

        return new self(prompts: $result, raw: $raw);
    }
}
