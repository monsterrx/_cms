<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

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

        return ['name' => $name, 'path' => $directory.DIRECTORY_SEPARATOR.$name];
    }

    /** @param array<int, array{name: string, path: string}> $images */
    public function discard(array $images): void
    {
        foreach ($images as $image) {
            if (is_file($image['path'])) {
                @unlink($image['path']);
            }
        }
    }
}
