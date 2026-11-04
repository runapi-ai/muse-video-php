<?php

declare(strict_types=1);

namespace RunApi\MuseVideo\Models;

use RunApi\Core\Support\Payload;

/** Completed video task response returned by run(). */
readonly class CompletedVideoTaskResponse extends VideoTaskResponse
{
    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), videos: self::videos($raw, required: true), raw: $raw);
    }

    public static function fromResponse(VideoTaskResponse $response): self
    {
        return self::fromArray($response->toArray());
    }
}
