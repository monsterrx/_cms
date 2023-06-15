<?php

namespace App\Traits;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait MediaProcessors {
    public function verifyAudio($fileName) {
        return ($this->getAppEnvironment() === 'dev' ? 'http://127.0.0.6' : 'https://rx931.com') . '/songs/'.$fileName;
    }

    public function verifyMobileAsset($fileName, $longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false) {
        if($fileName === null || $fileName === "" || !preg_match('/^[\w&.\-]+\.+[jpeg|jpg|png|webp|jfif|PNG|JPEG|JPG|WEBP|JFIF]+$/', $fileName)) {
            $fileName = $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
        } else {
            $photoDirectory = $this->getAppUrl() . '/images/_assets/mobile/'.$fileName;

            if(!File::exists($photoDirectory)) {
                return $this->getAppUrl() . '/images/_assets/mobile/'.$fileName;
            } else {
                $fileName = $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
            }
        }

        return $fileName;
    }

    public function verifyPhoto($fileName, $directory, $longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false): string
    {
        if($fileName === null || $fileName === "" || !preg_match('/^[\w&.\-]+\.+[jpeg|jpg|png|webp|jfif|PNG|JPEG|JPG|WEBP|JFIF]+$/', $fileName)) {
            $fileName = $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
        } else {
            $photoDirectory = $this->getAppUrl() . '/images/'.$directory.'/'.$fileName;

            if(!File::exists($photoDirectory)) {
                return $this->getAppUrl() . '/images/'.$directory.'/'.$fileName;
            } else {
                $fileName = $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
            }
        }

        return $fileName;
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
}
