<?php

namespace App\Traits;

use App\Support\MediaUrlResolver;

trait MediaProcessors
{
    public function verifyAudio($fileName): ?string
    {
        return app(MediaUrlResolver::class)->audio($fileName);
    }

    public function verifyMobileAsset($fileName, $longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false): string
    {
        return app(MediaUrlResolver::class)->mobileAsset(
            $fileName,
            $this->fallbackType($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper)
        );
    }

    public function verifyPhoto($fileName, $directory, $longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false): string
    {
        return app(MediaUrlResolver::class)->photo(
            $fileName,
            (string) $directory,
            $this->fallbackType($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper)
        );
    }

    public function getFileName($longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false): string
    {
        return app(MediaUrlResolver::class)->fallback(
            $this->fallbackType($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper)
        );
    }

    public function doesFileExistsInServer($fileUrl): bool
    {
        return is_string($fileUrl)
            && app(MediaUrlResolver::class)->remoteExists($fileUrl);
    }

    private function fallbackType($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper): string
    {
        return $longPhoto
            ? 'long'
            : ($banner
                ? 'banner'
                : ($banner500
                    ? 'banner-small'
                    : ($mobileWallpaper
                        ? 'mobile-wallpaper'
                        : ($desktopWallpaper ? 'desktop-wallpaper' : 'default'))));
    }
}
