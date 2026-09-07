<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

final class ResourceImageStorage
{
    /**
     * @param  array<string, mixed>  $definition
     * @return array{name: string, path: string}
     */
    public function store(UploadedFile $image, array $definition): array
    {
        $relativeDirectory = trim((string) ($definition['directory'] ?? ''), '/\\');
        if ($relativeDirectory === '' || ! preg_match('#^[A-Za-z0-9_/-]+$#', $relativeDirectory)) {
            throw new RuntimeException('The image upload directory is not configured safely.');
        }

        $directory = public_path('images/'.$relativeDirectory);
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException('The image upload directory could not be created.');
        }

        $extension = match ($image->getMimeType()) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new RuntimeException('The uploaded image type is not supported.'),
        };
        $name = now()->format('Ymd-His').'-'.Str::lower(Str::random(12)).'.'.$extension;
        $image->move($directory, $name);

        $stored = ['name' => $name, 'path' => $directory.DIRECTORY_SEPARATOR.$name, 'paths' => []];
        $stored['paths'][] = $stored['path'];

        try {
            $root = config('media.server_root');
            if (is_string($root) && $root !== '') {
                if (! is_dir($root) || ! preg_match('#^(?:/|[A-Za-z]:[/\\\\])#', $root)) {
                    throw new RuntimeException('The shared media server root must be an existing absolute directory.');
                }
                $stations = in_array($relativeDirectory, config('media.shared_directories', []), true)
                    ? array_keys(config('media.station_folders'))
                    : [app(StationContext::class)->current()];
                foreach ($stations as $station) {
                    foreach (config('media.station_folders.'.$station, []) as $folder) {
                        $target = rtrim($root, '/\\').($folder === '' ? '' : '/'.$folder).'/images/'.$relativeDirectory;
                        if (! is_dir($target) && ! @mkdir($target, 0755, true) && ! is_dir($target)) {
                            throw new RuntimeException('A required media destination could not be created.');
                        }
                        if (realpath($target) === realpath($directory)) {
                            continue;
                        }
                        $path = $target.'/'.$name;
                        $stored['paths'][] = $path;
                        if (! @copy($stored['path'], $path)) {
                            throw new RuntimeException('The image could not be published to all required media destinations.');
                        }
                    }
                }
            }
        } catch (Throwable $exception) {
            $this->discard([$stored]);
            throw $exception;
        }

        return $stored;
    }

    /** @param array<int, array{name: string, path: string}> $images */
    public function discard(array $images): void
    {
        foreach ($images as $image) {
            foreach ($image['paths'] ?? [$image['path']] as $path) {
                if (is_file($path)) {
                    @unlink($path);
                }
            }
        }
    }
}
