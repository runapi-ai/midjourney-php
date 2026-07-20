<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Midjourney\MidjourneyClient;
use RunApi\Midjourney\Models\CompletedImageTaskResponse;
use RunApi\Midjourney\Models\GetSeedResponse;
use RunApi\Midjourney\Models\ImageToPromptResponse;
use RunApi\Midjourney\Models\ShortenPromptResponse;
use RunApi\Midjourney\Resources\EditImage;
use RunApi\Midjourney\Resources\GetSeed;
use RunApi\Midjourney\Resources\ImageToPrompt;
use RunApi\Midjourney\Resources\ImageToVideo;
use RunApi\Midjourney\Resources\ShortenPrompt;
use RunApi\Midjourney\Resources\TextToImage;

final class MidjourneyClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToImage::class, $client->textToImage);
        self::assertInstanceOf(ImageToVideo::class, $client->imageToVideo);
        self::assertInstanceOf(EditImage::class, $client->editImage);
        self::assertInstanceOf(GetSeed::class, $client->getSeed);
        self::assertInstanceOf(ImageToPrompt::class, $client->imageToPrompt);
        self::assertInstanceOf(ShortenPrompt::class, $client->shortenPrompt);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToImage->create([
            'model' => 'midjourney-v8.1',
            'enable_prompt_translation' => true,
            'include_split_images' => true,
            'prompt' => 'A product render',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/midjourney/text_to_image', $transport->requests[0]->getUri()->getPath());
        self::assertSame('midjourney-v8.1', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","images":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToImage->run([
            'model' => 'midjourney-v8.1',
            'enable_prompt_translation' => true,
            'include_split_images' => true,
            'prompt' => 'A product render',
        ]);

        self::assertInstanceOf(CompletedImageTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->images[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/midjourney/text_to_image/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('images is required');

        $client->textToImage->run([
            'model' => 'midjourney-v8.1',
            'enable_prompt_translation' => true,
            'include_split_images' => true,
            'prompt' => 'A product render',
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('output_resolution must be one of the allowed values');

        $client->imageToVideo->create([
        'model' => 'midjourney-image-to-video',
        'enable_loop' => true,
        'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        'prompt' => 'A product render',
        'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        'output_resolution' => 'not-valid',
        ]);
    }
    public function testGetSeedRunsSynchronously(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"seed":8675309}'),
        ]);
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->getSeed->run([
            'image_id' => 'sample',
        ]);

        self::assertInstanceOf(GetSeedResponse::class, $result);
        self::assertSame(8675309, $result->seed);
        self::assertSame('/api/v1/midjourney/get_seed', $transport->requests[0]->getUri()->getPath());
    }

    public function testGetSeedPreservesErrorResponseWithoutSeed(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"error":"Seed lookup failed"}'),
        ]);
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->getSeed->run([
            'image_id' => 'sample',
        ]);

        self::assertInstanceOf(GetSeedResponse::class, $result);
        self::assertNull($result->seed);
        self::assertSame('Seed lookup failed', $result->error);
    }

    public function testImageToPromptRunsSynchronously(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"prompts":["one","two","three","four"]}'),
        ]);
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->imageToPrompt->run([
        'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        ]);

        self::assertInstanceOf(ImageToPromptResponse::class, $result);
        self::assertSame('one', $result->prompts[0]);
        self::assertSame('/api/v1/midjourney/image_to_prompt', $transport->requests[0]->getUri()->getPath());
    }

    public function testShortenPromptRunsSynchronously(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"prompts":["Concise mountain landscape"]}'),
        ]);
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->shortenPrompt->run([
            'prompt' => 'A detailed cinematic mountain landscape',
        ]);
        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertInstanceOf(ShortenPromptResponse::class, $result);
        self::assertSame(['Concise mountain landscape'], $result->prompts);
        self::assertSame('/api/v1/midjourney/shorten_prompt', $transport->requests[0]->getUri()->getPath());
        self::assertSame(['prompt' => 'A detailed cinematic mountain landscape'], $body);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new MidjourneyClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->imageToVideo->create([
            'model' => 'midjourney-image-to-video',
            'enable_loop' => true,
            'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'output_resolution' => '480p',
            'prompt' => 'A product render',
            'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        ]);

        self::assertSame('/api/v1/midjourney/image_to_video', $transport->requests[0]->getUri()->getPath());
    }
}
