<?php

declare(strict_types=1);

namespace RunApi\MuseVideo\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\MuseVideo\Models\CompletedVideoTaskResponse;
use RunApi\MuseVideo\MuseVideoClient;
use RunApi\MuseVideo\Resources\ImageToVideo;
use RunApi\MuseVideo\Resources\TextToVideo;
use RunApi\MuseVideo\Types;

final class MuseVideoClientTest extends TestCase
{
    public function testExposesTypedResourcesAndMuseModelConstant(): void
    {
        $client = new MuseVideoClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToVideo::class, $client->textToVideo);
        self::assertInstanceOf(ImageToVideo::class, $client->imageToVideo);
        self::assertSame('muse-video-v1', Types::MODEL_MUSE_VIDEO_V1);
    }

    public function testTextToVideoCreateUsesDedicatedEndpointAndCompactedFields(): void
    {
        $transport = new QueueHttpClient([new Response(200, [], '{"id":"text_1"}')]);
        $client = new MuseVideoClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        self::assertSame('text_1', $client->textToVideo->create([
            'model' => Types::MODEL_MUSE_VIDEO_V1,
            'prompt' => 'A city at dawn',
            'aspect_ratio' => '16:9',
            'callback_url' => null])->id);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('/api/v1/muse_video/text_to_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('muse-video-v1', $body['model']);
        self::assertSame('16:9', $body['aspect_ratio']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('image_url', $body);
    }

    public function testImageToVideoCreateUsesImageEndpointAndRequiredImageUrl(): void
    {
        $transport = new QueueHttpClient([new Response(200, [], '{"id":"image_1"}')]);
        $client = new MuseVideoClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->imageToVideo->create([
            'model' => Types::MODEL_MUSE_VIDEO_V1,
            'prompt' => 'Animate this scene',
            'image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg']);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('/api/v1/muse_video/image_to_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('https://cdn.runapi.ai/public/samples/image.jpg', $body['image_url']);
    }

    public function testRunReturnsTypedCompletedVideoAndRequiresResults(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://file.runapi.ai/video.mp4"}]}')]);
        $client = new MuseVideoClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToVideo->run([
            'model' => Types::MODEL_MUSE_VIDEO_V1,
            'prompt' => 'A city at dawn']);

        self::assertInstanceOf(CompletedVideoTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/video.mp4', $result->videos[0]->url);

        $missingResultClient = new MuseVideoClient(new ClientOptions(
            apiKey: 'k',
            httpClient: new QueueHttpClient([
                new Response(200, [], '{"id":"task_2"}'),
                new Response(200, [], '{"id":"task_2","status":"completed"}')]),
            maxRetries: 0
        ));
        $this->expectException(ValidationException::class);
        $missingResultClient->textToVideo->run(['model' => Types::MODEL_MUSE_VIDEO_V1, 'prompt' => 'A city']);
    }
}
