<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

final class MediaUrlResolver
{
    private const IMAGE_EXTENSIONS = ['jpeg', 'jpg', 'png', 'webp', 'jfif', 'gif', 'svg'];

    public function __construct(private StationContext $stations)
    {
    }

    public function photo(
        mixed $fileName,
        string $directory,
        string $fallback = 'default',
        bool $verifyRemote = true
    ): string {
        $safeDirectory = $this->safeDirectory($directory);
        $safeFileName = $this->safeImageFileName($fileName);

        if ($safeFileName === null) {
            return $this->fallback($fallback);
        }

        $relativePath = 'images/'.$safeDirectory.'/'.$safeFileName;
        if (is_file(public_path($relativePath))) {
            return $this->applicationUrl($relativePath);
        }

        $remoteUrl = $this->originUrl($relativePath);
        if (! $verifyRemote || ! config('media.verify_remote', true)) {
            return $remoteUrl;
        }

        return $this->remoteExists($remoteUrl) ? $remoteUrl : $this->fallback($fallback);
    }

    public function mobileAsset(mixed $fileName, string $fallback = 'default', bool $verifyRemote = true): string
    {
        return $this->photo($fileName, '_assets/mobile', $fallback, $verifyRemote);
    }

    public function audio(mixed $fileName, bool $verifyRemote = false): ?string
    {
        $safeFileName = $this->safeAudioFileName($fileName);
        if ($safeFileName === null) {
            return null;
        }

        $relativePath = 'audios/'.$safeFileName;
        if (is_file(public_path($relativePath))) {
            return $this->applicationUrl($relativePath);
        }

        $remoteUrl = $this->originUrl($relativePath);

        return ! $verifyRemote || $this->remoteExists($remoteUrl) ? $remoteUrl : null;
    }

    public function fallback(string $type = 'default'): string
    {
        $fileName = config('media.fallbacks.'.$type)
            ?? config('media.fallbacks.default', 'default.png');
        $relativePath = 'images/_assets/'.basename((string) $fileName);

        return is_file(public_path($relativePath))
            ? $this->applicationUrl($relativePath)
            : $this->originUrl($relativePath);
    }

    public function remoteExists(string $url): bool
    {
        if (! $this->isAllowedMediaUrl($url)) {
            return false;
        }

        $seconds = max(0, (int) config('media.exists_cache_seconds', 600));
        $resolver = fn (): bool => $this->requestExists($url);

        return $seconds === 0
            ? $resolver()
            : Cache::remember('media-exists:'.sha1($url), $seconds, $resolver);
    }

    private function requestExists(string $url): bool
    {
        try {
            $response = Http::connectTimeout(
                min(2, max(1, (int) config('media.remote_timeout_seconds', 3)))
            )->timeout(
                max(1, (int) config('media.remote_timeout_seconds', 3))
            )->head($url);

            return $response->successful()
                || $response->redirect()
                || $response->status() === 405;
        } catch (Throwable) {
            return false;
        }
    }

    private function originUrl(string $relativePath): string
    {
        $station = $this->stations->current();
        $origin = config('media.origins.'.$station)
            ?: config('media.origins.mnl')
            ?: config('app.url');

        return rtrim((string) $origin, '/').'/'.$this->encodePath($relativePath);
    }

    private function applicationUrl(string $relativePath): string
    {
        $basePath = app()->bound('request')
            ? rtrim((string) request()->getBaseUrl(), '/')
            : rtrim((string) parse_url((string) config('app.url'), PHP_URL_PATH), '/');

        return $basePath.'/'.$this->encodePath($relativePath);
    }

    private function isAllowedMediaUrl(string $url): bool
    {
        $candidate = rtrim(Str::before($url, '?'), '/');

        foreach ((array) config('media.origins', []) as $origin) {
            $allowed = rtrim((string) $origin, '/');
            if ($allowed !== '' && ($candidate === $allowed || Str::startsWith($candidate, $allowed.'/'))) {
                return true;
            }
        }

        return false;
    }

    private function safeDirectory(string $directory): string
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');

        if ($directory === '' || ! preg_match('#^[A-Za-z0-9_-]+(?:/[A-Za-z0-9_-]+)*$#', $directory)) {
            return '_assets';
        }

        return $directory;
    }

    private function safeImageFileName(mixed $fileName): ?string
    {
        if (! is_string($fileName) || trim($fileName) === '') {
            return null;
        }

        $fileName = basename(str_replace('\\', '/', trim($fileName)));
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (
            $fileName === ''
            || ! in_array($extension, self::IMAGE_EXTENSIONS, true)
            || ! preg_match('/^[A-Za-z0-9][A-Za-z0-9_.&() -]*$/', $fileName)
        ) {
            return null;
        }

        return $fileName;
    }

    private function safeAudioFileName(mixed $fileName): ?string
    {
        if (! is_string($fileName) || trim($fileName) === '') {
            return null;
        }

        $fileName = basename(str_replace('\\', '/', trim($fileName)));
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        return in_array($extension, ['aac', 'm4a', 'mp3', 'ogg', 'wav'], true)
            && preg_match('/^[A-Za-z0-9][A-Za-z0-9_.&() -]*$/', $fileName)
                ? $fileName
                : null;
    }

    private function encodePath(string $path): string
    {
        return collect(explode('/', trim($path, '/')))
            ->map(static fn (string $segment): string => rawurlencode($segment))
            ->implode('/');
    }
}
