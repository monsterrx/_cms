<?php

namespace Tests\Feature;

use App\Support\ResourceImageStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MediaDistributionTest extends TestCase
{
    public function test_distribution_and_rollback(): void
    {
        $root = sys_get_temp_dir().'/cms-media-'.bin2hex(random_bytes(8));
        mkdir($root);
        mkdir($root.'/cms');
        $this->app->usePublicPath($root.'/cms');
        config(['media.server_root' => $root, 'workspace.station_code' => 'mnl']);
        $storage = app(ResourceImageStorage::class);
        try {
            foreach (['podcasts', 'jocks'] as $directory) {
                $image = UploadedFile::fake()->createWithContent('cover.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII='));
                $stored = $storage->store($image, ['directory' => $directory]);
                $folders = $directory === 'podcasts' ? ['', 'rxcms'] : ['', 'rxcms', 'monstercebu', 'monstercebu/cebucms', 'monsterdavao', 'monsterdavao/davaocms'];
                foreach ($folders as $folder) {
                    $this->assertFileExists($root.'/'.$folder.'/images/'.$directory.'/'.$stored['name']);
                }
                if ($directory === 'podcasts') {
                    $this->assertDirectoryDoesNotExist($root.'/monstercebu');
                }
                $storage->discard([$stored]);
                foreach ($stored['paths'] as $path) {
                    $this->assertFileDoesNotExist($path);
                }
            }
            file_put_contents($root.'/blocked', 'blocked');
            config(['media.station_folders.mnl' => ['', 'blocked']]);
            try {
                $image = UploadedFile::fake()->createWithContent('cover.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII='));
                $storage->store($image, ['directory' => 'podcasts']);
                $this->fail('Expected distribution failure.');
            } catch (\RuntimeException) {
                $this->assertSame([], glob($root.'/images/podcasts/*'));
                $this->assertSame([], glob($root.'/cms/images/podcasts/*'));
            }
        } finally {
            File::deleteDirectory($root);
        }
    }
}
