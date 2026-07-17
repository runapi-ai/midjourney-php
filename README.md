# Midjourney PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/midjourney)](https://packagist.org/packages/runapi-ai/midjourney)
[![License](https://img.shields.io/github/license/runapi-ai/midjourney-php)](https://github.com/runapi-ai/midjourney-php/blob/main/LICENSE)

The Midjourney PHP SDK is the language-specific package for Midjourney
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `midjourney-php` split
repository. For model details, use https://runapi.ai/models/midjourney; for API
reference, use https://runapi.ai/docs#midjourney; for SDK docs, use
https://runapi.ai/docs#sdk-midjourney.

## Install

```bash
composer require runapi-ai/midjourney
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Midjourney\MidjourneyClient;

$client = new MidjourneyClient(); // reads RUNAPI_API_KEY

$task = $client->textToImage->create([
    'model' => 'midjourney-v8.1',
    'enable_prompt_translation' => true,
    'include_split_images' => true,
    'prompt' => 'A precise product render on white marble',
]);

$status = $client->textToImage->get($task->id);

$result = $client->textToImage->run([
    'model' => 'midjourney-v8.1',
    'enable_prompt_translation' => true,
    'include_split_images' => true,
    'prompt' => 'A serene mountain lake at dawn',
]);

echo $result->images[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.

RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `textToImage`, `imageToVideo`, `editImage`, `getSeed`, `imageToPrompt`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/midjourney
- SDK docs: https://runapi.ai/docs#sdk-midjourney
- Product docs: https://runapi.ai/docs#midjourney
- Pricing and rate limits: https://runapi.ai/models/midjourney/v8.1
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/midjourney-php
- Multi-language SDK repository: https://github.com/runapi-ai/midjourney-sdk

## License

Licensed under the Apache License, Version 2.0.
