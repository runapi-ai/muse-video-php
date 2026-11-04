<?php

declare(strict_types=1);

namespace RunApi\MuseVideo;

/** Constants for Muse Video model slugs. */
final class Types
{
    public const MODEL_MUSE_VIDEO_V1 = 'muse-video-v1';
    public const ASPECT_RATIOS = ['16:9', '9:16', '1:1', '4:3', '3:4'];

    private function __construct()
    {
    }
}
