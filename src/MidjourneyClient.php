<?php

declare(strict_types=1);

namespace RunApi\Midjourney;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Midjourney\Resources\EditImage;
use RunApi\Midjourney\Resources\ExtendVideo;
use RunApi\Midjourney\Resources\GetSeed;
use RunApi\Midjourney\Resources\ImageToPrompt;
use RunApi\Midjourney\Resources\ImageToVideo;
use RunApi\Midjourney\Resources\ShortenPrompt;
use RunApi\Midjourney\Resources\TextToImage;

/**
 * Midjourney RunAPI PHP client.
 *
 * The client exposes typed model resources plus the universal `files` and
 * `account` resources.
 */
final class MidjourneyClient extends BaseClient
{
    /** Text to image operations for Midjourney. */
    public readonly TextToImage $textToImage;
    /** Image to video operations for Midjourney. */
    public readonly ImageToVideo $imageToVideo;
    /** Edit image operations for Midjourney. */
    public readonly EditImage $editImage;
    /** First-video extension operations for Midjourney. */
    public readonly ExtendVideo $extendVideo;
    /** Get seed operations for Midjourney. */
    public readonly GetSeed $getSeed;
    /** Image to prompt operations for Midjourney. */
    public readonly ImageToPrompt $imageToPrompt;
    /** Prompt shortening operations for Midjourney. */
    public readonly ShortenPrompt $shortenPrompt;

    /** Create a Midjourney client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToImage = TextToImage::fromHttp($this->http);
        $this->imageToVideo = ImageToVideo::fromHttp($this->http);
        $this->editImage = EditImage::fromHttp($this->http);
        $this->extendVideo = ExtendVideo::fromHttp($this->http);
        $this->getSeed = GetSeed::fromHttp($this->http);
        $this->imageToPrompt = ImageToPrompt::fromHttp($this->http);
        $this->shortenPrompt = ShortenPrompt::fromHttp($this->http);
    }
}
