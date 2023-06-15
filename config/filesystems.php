<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

        /*
            Author: Ben Zarmaynine E. Obra, Sean Philip P. Cruz
            Date: 05/30/2017, 08/28/2019
            Added: array item for default value of file storing, custom path
        */

        // JOCKS
        'jocks' => [
            'driver' => 'local',
            'root'   => '../images/jocks',
        ],

        'mnl_jocks' => [
            'driver' => 'local',
            'root'   => '../../images/jocks',
        ],

        'mnl_jocks_cms' => [
            'driver' => 'local',
            'root'   => '../../rxcms/images/jocks',
        ],

        'cbu_jocks' => [
            'driver' => 'local',
            'root'   => '../monstercebu/images/jocks',
        ],

        'cbu_jocks_cms' => [
            'driver' => 'local',
            'root'   => '../monstercebu/cebucms/images/jocks',
        ],

        'dav_jocks' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/images/jocks',
        ],

        'dav_jocks_cms' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/davaocms/images/jocks',
        ],
        // END

        'articles' => [
            'driver' => 'local',
            'root'   => '../images/articles',
        ],


        // SHOWS
        'shows' => [
            'driver' => 'local',
            'root'   => '../images/shows',
        ],

        'mnl_shows' => [
            'driver' => 'local',
            'root'   => '../../images/shows',
        ],

        'mnl_shows_cms' => [
            'driver' => 'local',
            'root'   => '../../rxcms/images/shows',
        ],

        'cbu_shows' => [
            'driver' => 'local',
            'root'   => '../monstercebu/images/shows',
        ],

        'dav_shows' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/images/shows',
        ],

        'cbu_shows_cms' => [
            'driver' => 'local',
            'root'   => '../monstercebu/cebucms/images/shows',
        ],

        'dav_shows_cms' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/davaocms/images/shows',
        ],
        // END


        // ARTIST
        'artists' => [
            'driver' => 'local',
            'root'   => '../images/artists',
        ],

        'mnl_artists' => [
            'driver' => 'local',
            'root'   => '../../images/artists',
        ],

        'mnl_artists_cms' => [
            'driver' => 'local',
            'root' => '../../rxcms/images/artists'
        ],

        'cbu_artists' => [
            'driver' => 'local',
            'root'   => '../monstercebu/images/artists'
        ],

        'cbu_artists_cms' => [
            'driver' => 'local',
            'root' => '../monstercebu/cebucms/images/artists'
        ],

        'dav_artists' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/images/artists'
        ],

        'dav_artists_cms' => [
            'driver' => 'local',
            'root' => '../monsterdavao/davaocms/images/artists'
        ],
        // END

        // ALBUM
        'albums' => [
            'driver' => 'local',
            'root'   => '../images/albums',
        ],

        'mnl_albums' => [
            'driver' => 'local',
            'root'   => '../../images/albums'
        ],

        'mnl_albums_cms' => [
            'driver' => 'local',
            'root' => '../../rxcms/images/albums'
        ],

        'cbu_albums' => [
            'driver' => 'local',
            'root'   => '../monstercebu/images/albums'
        ],

        'cbu_albums_cms' => [
            'driver' => 'local',
            'root' => '../monstercebu/cebucms/images/albums'
        ],

        'dav_albums' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/images/albums'
        ],

        'dav_albums_cms' => [
            'driver' => 'local',
            'root' => '../monsterdavao/davaocms/images/albums'
        ],
        // END

        'schools' => [
            'driver' => 'local',
            'root'   => '../images/schools',
        ],

        'giveaways' => [
            'driver' => 'local',
            'root'   => '../images/giveaways',
        ],

        'headers' => [
            'driver' => 'local',
            'root'   => '../images/headers',
        ],

        // HEADERS
        'mnl_headers' => [
            'driver' => 'local',
            'root'   => '../../images/headers',
        ],

        'mnl_headers_cms' => [
            'driver' => 'local',
            'root'   => '../../rxcms/images/headers',
        ],

        'cbu_headers' => [
            'driver' => 'local',
            'root'   => '../monstercebu/images/headers'
        ],

        'cbu_headers_cms' => [
            'driver' => 'local',
            'root' => '../monstercebu/cebucms/images/headers'
        ],

        'dav_headers' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/images/headers'
        ],

        'dav_headers_cms' => [
            'driver' => 'local',
            'root' => '../monsterdavao/davaocms/images/headers'
        ],
        // END

        'podcasts' => [
            'driver' => 'local',
            'root'   => '../images/podcasts',
        ],

        'studentJocks' => [
            'driver' => 'local',
            'root'   => '../images/studentJocks',
        ],

        'batch' => [
            'driver' => 'local',
            'root'   => '../images/scholarBatch',
        ],

        'students' => [
            'driver' => 'local',
            'root' => '../images/scholars'
        ],

        // INDIEGROUND
        'indie' => [
            'driver' => 'local',
            'root' => '../images/indie',
        ],

        'mnl_indie' => [
            'driver' => 'local',
            'root'   => '../../images/indie',
        ],

        'mnl_indie_cms' => [
            'driver' => 'local',
            'root' => '../../rxcms/images/indie'
        ],

        'cbu_indie' => [
            'driver' => 'local',
            'root'   => '../monstercebu/images/indie'
        ],

        'cbu_indie_cms' => [
            'driver' => 'local',
            'root' => '../monstercebu/cebucms/images/indie'
        ],

        'dav_indie' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/images/indie'
        ],

        'dav_indie_cms' => [
            'driver' => 'local',
            'root' => '../monsterdavao/davaocms/images/indie'
        ],
        // END

        'wallpapers' => [
            'driver' => 'local',
            'root' => '../images/wallpapers',
        ],

        'reports' => [
            'driver' => 'local',
            'root' => '../images/reports'
        ],

        // SONG AUDIOS
        'songs' => [
            'driver' => 'local',
            'root'   => '../audios',
        ],

        'mnl_songs' => [
            'driver' => 'local',
            'root'   => '../../audios',
        ],

        'mnl_songs_cms' => [
            'driver' => 'local',
            'root' => '../../rxcms/audios'
        ],

        'cbu_songs' => [
            'driver' => 'local',
            'root'   => '../monstercebu/audios'
        ],

        'cbu_songs_cms' => [
            'driver' => 'local',
            'root' => '../monstercebu/cebucms/audios'
        ],

        'dav_songs' => [
            'driver' => 'local',
            'root'   => '../monsterdavao/audios'
        ],

        'dav_songs_cms' => [
            'driver' => 'local',
            'root' => '../monsterdavao/davaocms/audios'
        ],
        // END

        // New additions
        '_assets/mobile' =>[
            'driver' => 'local',
            'root' => '../images/_assets/mobile'
        ],

        'bugs' => [
            'driver' => 'local',
            'root' => '../images/bugs'
        ]
        // END
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        ('storage') => storage_path('app/public'),
    ],

];
