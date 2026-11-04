# Muse Video PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/muse-video)](https://packagist.org/packages/runapi-ai/muse-video)
[![License](https://img.shields.io/github/license/runapi-ai/muse-video-php)](https://github.com/runapi-ai/muse-video-php/blob/main/LICENSE)

The Muse Video PHP SDK is the language-specific package for Muse Video on RunAPI. Use it to create tasks from a text prompt or one source image and retrieve their status.

```bash
composer require runapi-ai/muse-video
```

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\MuseVideo\MuseVideoClient;

$client = new MuseVideoClient();
$task = $client->textToVideo->create([
    'model' => 'muse-video-v1',
    'prompt' => 'A city at dawn',
]);
$status = $client->textToVideo->get($task->id);
```

Use `create()` and `get()` for asynchronous workflows, or `run()` to poll until completion. Generated file URLs are temporary; store needed results in durable storage during their retention window.

## Links

- [Model reference](https://runapi.ai/models/muse-video)
- [API reference](https://runapi.ai/docs/api/muse-video/text-to-video)
- [SDK docs](https://runapi.ai/docs/resources/sdks)
- [GitHub repository](https://github.com/runapi-ai/muse-video-php)

Licensed under the Apache License, Version 2.0.
