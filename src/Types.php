<?php

declare(strict_types=1);

namespace RunApi\Midjourney;

final class Types
{
    /**
     * Allowed model slugs for text to image requests.
     *
     * @var list<string>
     */
    public const TEXT_TO_IMAGE_MODELS = ['midjourney-v8.1'];

    /**
     * Allowed model slugs for image to video requests.
     *
     * @var list<string>
     */
    public const IMAGE_TO_VIDEO_MODELS = ['midjourney-image-to-video'];

    /**
     * Model used to validate first-video extension requests.
     *
     * @var list<string>
     */
    public const EXTEND_VIDEO_MODELS = ['midjourney-image-to-video'];

    /**
     * Allowed model slugs for edit image requests.
     *
     * @var list<string>
     */
    public const EDIT_IMAGE_MODELS = ['midjourney-edit-image'];

    /**
     * Allowed model slugs for get seed requests.
     *
     * @var list<string>
     */
    public const GET_SEED_MODELS = [];

    /**
     * Allowed model slugs for image to prompt requests.
     *
     * @var list<string>
     */
    public const IMAGE_TO_PROMPT_MODELS = [];

    /**
     * Allowed model slugs for prompt shortening requests.
     *
     * @var list<string>
     */
    public const SHORTEN_PROMPT_MODELS = [];

    private function __construct()
    {
    }
}
