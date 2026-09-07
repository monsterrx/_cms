<?php

return [
    // The deployed rxcms folder is directly inside the shared document root.
    // Other layouts (including Docker) may supply an absolute mounted path.
    'server_root' => env('MEDIA_SERVER_ROOT') ?: (
        basename(base_path()) === 'rxcms' ? dirname(base_path()) : null
    ),
    'station_folders' => [
        'mnl' => ['', 'rxcms'],
        'cbu' => ['monstercebu', 'monstercebu/cebucms'],
        'dav' => ['monsterdavao', 'monsterdavao/davaocms'],
    ],
    'shared_directories' => ['jocks', 'shows', 'artists', 'albums', 'indie'],

    /*
    |--------------------------------------------------------------------------
    | Station media origins
    |--------------------------------------------------------------------------
    |
    | The imported database stores filenames only. Legacy images remain on the
    | public station sites, while newly uploaded images may exist locally.
    |
    */
    'origins' => [
        'mnl' => env('MEDIA_URL_MNL', 'https://rx931.com'),
        'cbu' => env('MEDIA_URL_CBU', 'https://monstercebu.com'),
        'dav' => env('MEDIA_URL_DAV', 'https://monsterdavao.com'),
    ],

    'verify_remote' => env('MEDIA_VERIFY_REMOTE', true),
    'remote_timeout_seconds' => (int) env('MEDIA_REMOTE_TIMEOUT', 3),
    'exists_cache_seconds' => (int) env('MEDIA_EXISTS_CACHE_SECONDS', 600),

    'fallbacks' => [
        'default' => 'default.png',
        'long' => 'default-long.png',
        'banner' => 'default-banner.png',
        'banner-small' => 'default-banner-sm.png',
        'mobile-wallpaper' => 'mobile-wallpaper-missing.png',
        'desktop-wallpaper' => 'desktop-wallpaper-missing.png',
    ],
];
