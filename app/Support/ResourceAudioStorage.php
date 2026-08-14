<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

final class ResourceAudioStorage
{
    /** @return array{name: string, path: string} */
    public function store(UploadedFile $audio): array
    {
        $extension = match ($audio->getMimeType()) {
            'audio/mpeg', 'audio/mp3' => 'mp3',
            'audio/mp4', 'audio/x-m4a' => 'm4a',
            'audio/ogg', 'application/ogg' => 'ogg',
            'audio/wav', 'audio/x-wav', 'audio/wave' => 'wav',
            default => throw new RuntimeException('The uploaded audio type is not supported.'),
        };
        $directory = public_path('audios');
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException('The audio upload directory could not be created.');
        }

        $name = now()->format('Ymd-His').'-'.Str::lower(Str::random(12)).'.'.$extension;
        $audio->move($directory, $name);

        return ['name' => $name, 'path' => $directory.DIRECTORY_SEPARATOR.$name];
    }

    public function discard(?array $audio): void
    {
        if ($audio && is_file($audio['path'] ?? null)) {
            @unlink($audio['path']);
        }
    }
}
