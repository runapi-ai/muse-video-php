<?php

declare(strict_types=1);

namespace RunApi\MuseVideo\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/** Generated video file metadata. */
readonly class Video extends BaseModel
{
    /** @param array<string, mixed> $raw */
    public function __construct(public string $url, array $raw = [])
    {
        parent::__construct($raw === [] ? ['url' => $url] : $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        return new self(url: Payload::string($raw, 'url'), raw: $raw);
    }
}
