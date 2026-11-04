<?php

declare(strict_types=1);

namespace RunApi\MuseVideo;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\MuseVideo\Resources\ImageToVideo;
use RunApi\MuseVideo\Resources\TextToVideo;

/** Muse Video RunAPI PHP client. */
final class MuseVideoClient extends BaseClient
{
    public readonly TextToVideo $textToVideo;
    public readonly ImageToVideo $imageToVideo;

    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToVideo = TextToVideo::fromHttp($this->http);
        $this->imageToVideo = ImageToVideo::fromHttp($this->http);
    }
}
