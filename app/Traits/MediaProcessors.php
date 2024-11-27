<?php

namespace App\Traits;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait MediaProcessors {
    public function verifyAudio($fileName) {
        return ($this->getAppEnvironment() === 'dev' ? 'http://127.0.0.2' : 'https://rx931.com') . '/audios/'.$fileName;
    }

    public function verifyMobileAsset($fileName, $longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false) {
        $photoDirectory = $this->getAppUrl() . '/images/_assets/mobile/'.$fileName;

        if($this->doesFileExistsInServer($photoDirectory)) {
            return $this->getAppUrl() . '/images/_assets/mobile/'.$fileName;
        } else {
            if($fileName === null || $fileName === "" || !preg_match('/^[\w&.\-]+\.+[jpeg|jpg|png|webp|jfif|PNG|JPEG|JPG|WEBP|JFIF]+$/', $fileName)) {
                return $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
            }

            return $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
        }
    }

    public function verifyPhoto($fileName, $directory, $longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false): string
    {
        $photoDirectory = $this->getAppUrl() . '/images/'.$directory.'/'.$fileName;

        if($this->doesFileExistsInServer($photoDirectory)) {
            return $this->getAppUrl() . '/images/'.$directory.'/'.$fileName;
        } else {
            if($fileName === null || $fileName === "" || !preg_match('/^[\w&.\-]+\.+[jpeg|jpg|png|webp|jfif|PNG|JPEG|JPG|WEBP|JFIF]+$/', $fileName)) {
                return $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
            }

            return $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
        }
    }

    public function getFileName(string $longPhoto, string $banner, string $banner500, string $mobileWallpaper, string $desktopWallpaper): string
    {
        $fileName = 'default.png';

        if ($longPhoto) {
            $fileName = 'default-long.png';
        } elseif ($banner) {
            $fileName = 'default-banner.png';
        } elseif ($banner500) {
            $fileName = 'default-banner-sm.png';
        } elseif ($mobileWallpaper) {
            $fileName = 'mobile-wallpaper-missing.png';
        } elseif ($desktopWallpaper) {
            $fileName = 'desktop-wallpaper-missing.png';
        }

        return $this->getAppUrl() . '/images/_assets/' . $fileName;
    }

    public function doesFileExistsInServer($fileUrl) {
        $response = Http::head($fileUrl);

        return $response->status() === 200;
    }
}
