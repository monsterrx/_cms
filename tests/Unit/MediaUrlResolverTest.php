<?php

namespace Tests\Unit;

use App\Support\MediaUrlResolver;
use App\Traits\MediaProcessors;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MediaUrlResolverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'workspace.station_code' => 'mnl',
            'media.origins.mnl' => 'https://media.example.test',
            'media.verify_remote' => true,
            'media.exists_cache_seconds' => 600,
            'media.fallbacks.default' => 'default.png',
        ]);
        Cache::flush();
    }

    public function test_it_uses_the_station_media_origin_for_imported_images(): void
    {
        Http::fake();

        $url = app(MediaUrlResolver::class)->photo(
            '20250113-1298600460.png',
            'jocks',
            'default',
            false
        );

        $this->assertSame(
            'https://media.example.test/images/jocks/20250113-1298600460.png',
            $url
        );
        Http::assertNothingSent();
    }

    public function test_it_uses_and_caches_remote_file_checks_for_legacy_controllers(): void
    {
        Http::fake([
            'https://media.example.test/images/jocks/available.jpg' => Http::response('', 200),
        ]);
        $resolver = app(MediaUrlResolver::class);

        $first = $resolver->photo('available.jpg', 'jocks');
        $second = $resolver->photo('available.jpg', 'jocks');

        $this->assertSame('https://media.example.test/images/jocks/available.jpg', $first);
        $this->assertSame($first, $second);
        Http::assertSentCount(1);
    }

    public function test_it_returns_the_station_fallback_for_missing_or_unsafe_files(): void
    {
        Http::fake([
            '*' => Http::response('', 404),
        ]);
        $resolver = app(MediaUrlResolver::class);
        $fallback = 'https://media.example.test/images/_assets/default.png';

        $this->assertSame($fallback, $resolver->photo('missing.jpg', 'jocks'));
        $this->assertSame($fallback, $resolver->photo('../../.env', 'jocks'));
        $this->assertSame($fallback, $resolver->photo(null, 'jocks'));
    }

    public function test_local_new_uploads_take_priority_over_remote_media(): void
    {
        $directory = public_path('images/test-media');
        $path = $directory.DIRECTORY_SEPARATOR.'local.jpg';
        File::ensureDirectoryExists($directory);
        File::put($path, 'test-image');

        try {
            Http::fake();

            $this->assertSame(
                '/images/test-media/local.jpg',
                app(MediaUrlResolver::class)->photo('local.jpg', 'test-media')
            );
            Http::assertNothingSent();
        } finally {
            File::deleteDirectory($directory);
        }
    }

    public function test_legacy_media_trait_uses_the_shared_resolver(): void
    {
        Http::fake([
            'https://media.example.test/images/articles/article.jpg' => Http::response('', 200),
        ]);
        $consumer = new class
        {
            use MediaProcessors;
        };

        $this->assertSame(
            'https://media.example.test/images/articles/article.jpg',
            $consumer->verifyPhoto('article.jpg', 'articles')
        );
    }
}
