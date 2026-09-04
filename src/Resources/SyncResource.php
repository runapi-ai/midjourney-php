<?php

declare(strict_types=1);

namespace RunApi\Midjourney\Resources;

use RunApi\Core\Resources\HybridResource;

/** Shared synchronous request boundary for Midjourney helper resources. */
abstract readonly class SyncResource extends HybridResource
{
}
