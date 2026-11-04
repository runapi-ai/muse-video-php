<?php

declare(strict_types=1);

namespace RunApi\MuseVideo\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\MuseVideo\Models\CompletedVideoTaskResponse;
use RunApi\MuseVideo\Models\VideoTaskResponse;

/** Text to video operations. */
readonly class TextToVideo extends TypedConfiguredResource
{
    /** @param array{model: string, prompt: string, aspect_ratio?: string, callback_url?: string|null} $params */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        /** @var VideoTaskResponse */
        return parent::get($id, $options);
    }

    /** @param array{model: string, prompt: string, aspect_ratio?: string, callback_url?: string|null} $params */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        /** @var CompletedVideoTaskResponse */
        return parent::run($params, $options);
    }

    public static function fromHttp(HttpClient $http): self
    {
        return new self($http, '/api/v1/muse_video/text_to_video', VideoTaskResponse::class, CompletedVideoTaskResponse::class, 'text-to-video', VideoTaskResponse::class, CompletedVideoTaskResponse::class);
    }
}
