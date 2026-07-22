<?php

return [
    'navigation' => [
        [
            'slug' => 'staff',
            'label' => 'Staff',
            'icon' => 'staff',
            'description' => 'Employees, station talent, Radio1 members, and milestones.',
            'groups' => [
                [
                    'label' => 'Employees',
                    'items' => [
                        ['slug' => 'staffs', 'label' => 'Staffs', 'description' => 'Manage employee records and station assignments.'],
                        ['slug' => 'designations', 'label' => 'Designations', 'description' => 'Maintain roles and access levels.'],
                        ['slug' => 'jocks', 'label' => 'Jocks', 'description' => 'Manage on-air talent profiles and media.'],
                    ],
                ],
                [
                    'label' => 'Radio1',
                    'items' => [
                        ['slug' => 'radio1-batches', 'label' => 'Batches', 'description' => 'Organize Radio1 student-jock batches.'],
                        ['slug' => 'student-jocks', 'label' => 'Student Jocks', 'description' => 'Manage Radio1 student-jock profiles.'],
                    ],
                ],
                [
                    'label' => 'Milestones',
                    'items' => [
                        ['slug' => 'awards', 'label' => 'Awards', 'description' => 'Record station awards and milestones.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'music',
            'label' => 'Music',
            'icon' => 'music',
            'description' => 'Catalog, charts, voting, dropouts, and Indieground content.',
            'groups' => [
                [
                    'label' => 'Chart Music',
                    'items' => [
                        ['slug' => 'artists', 'label' => 'Artists', 'description' => 'Maintain artists and their profile media.'],
                        ['slug' => 'albums', 'label' => 'Albums', 'description' => 'Manage albums linked to artists.'],
                        ['slug' => 'songs', 'label' => 'Songs', 'description' => 'Maintain the station music catalog.'],
                        ['slug' => 'genres', 'label' => 'Genres', 'description' => 'Organize music by genre.'],
                    ],
                ],
                [
                    'label' => 'Charts',
                    'items' => [
                        ['slug' => 'station-chart', 'label' => 'Station Chart', 'description' => 'Build and publish the station countdown.'],
                        ['slug' => 'daily-survey-top-5', 'label' => 'Daily Survey Top 5', 'description' => 'Manage daily listener survey charts.'],
                        ['slug' => 'votes', 'label' => 'Votes', 'description' => 'Review song votes and tally activity.'],
                        ['slug' => 'dropouts', 'label' => 'Dropouts', 'description' => 'Track songs removed from active charts.'],
                    ],
                ],
                [
                    'label' => 'Indieground',
                    'items' => [
                        ['slug' => 'indieground-artists', 'label' => 'Artists', 'description' => 'Manage independent artists.'],
                        ['slug' => 'indieground-featured', 'label' => 'Featured', 'description' => 'Curate featured Indieground releases.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'digital-content-programs',
            'label' => 'Digital Content & Programs',
            'icon' => 'content',
            'description' => 'Editorial content, visual assets, mobile content, shows, and podcasts.',
            'groups' => [
                [
                    'label' => 'Digital Contents',
                    'items' => [
                        ['slug' => 'articles', 'label' => 'Articles', 'description' => 'Create, review, publish, and archive articles.'],
                        ['slug' => 'categories', 'label' => 'Categories', 'description' => 'Maintain editorial categories.'],
                        ['slug' => 'graphics-artist', 'label' => 'Graphics Artist', 'description' => 'Manage sliders and featured graphics.'],
                        ['slug' => 'mobile-application', 'label' => 'Mobile Application', 'description' => 'Maintain mobile application assets and titles.'],
                        ['slug' => 'wallpapers', 'label' => 'Wallpapers', 'description' => 'Publish downloadable station wallpapers.'],
                        ['slug' => 'monster-music-awards', 'label' => 'Monster Music Awards', 'description' => 'Manage award releases, nominees, and visibility.'],
                    ],
                ],
                [
                    'label' => 'Programs',
                    'items' => [
                        ['slug' => 'shows', 'label' => 'Shows', 'description' => 'Manage station shows and assigned jocks.'],
                        ['slug' => 'timeslots', 'label' => 'Timeslots', 'description' => 'Maintain the weekly broadcast schedule.'],
                        ['slug' => 'podcasts', 'label' => 'Podcasts', 'description' => 'Publish show episodes and podcast metadata.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'events-scholarship',
            'label' => 'Events & Scholarship',
            'icon' => 'events',
            'description' => 'Gimikboards, schools, scholars, student batches, and sponsors.',
            'groups' => [
                [
                    'label' => 'Gimikboards',
                    'items' => [
                        ['slug' => 'schools', 'label' => 'Schools', 'description' => 'Maintain participating school records.'],
                        ['slug' => 'gimik-board', 'label' => 'Gimik Board', 'description' => 'Manage campus representatives and activity.'],
                    ],
                ],
                [
                    'label' => 'Scholars',
                    'items' => [
                        ['slug' => 'scholar-batches', 'label' => 'Batch', 'description' => 'Organize scholarship batches.'],
                        ['slug' => 'students', 'label' => 'Student', 'description' => 'Manage scholar and student records.'],
                        ['slug' => 'sponsors', 'label' => 'Sponsors', 'description' => 'Maintain scholarship sponsor records.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'promos',
            'label' => 'Promos',
            'icon' => 'gift',
            'description' => 'Monster giveaways, activations, and contestant records.',
            'groups' => [
                [
                    'label' => 'Monster Giveaways',
                    'items' => [
                        ['slug' => 'giveaways', 'label' => 'Giveaways', 'description' => 'Create and activate station giveaways.'],
                        ['slug' => 'contestants', 'label' => 'Contestants', 'description' => 'Review and manage giveaway entries.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'utilities',
            'label' => 'Utilities',
            'icon' => 'tools',
            'description' => 'User access, operational reports, activity logs, and archives.',
            'groups' => [
                [
                    'label' => 'Logs & Reports',
                    'items' => [
                        ['slug' => 'users', 'label' => 'Users', 'description' => 'Review accounts and profile access.'],
                        ['slug' => 'reports', 'label' => 'Reports', 'description' => 'Review operational and issue reports.'],
                        ['slug' => 'logs', 'label' => 'Logs', 'description' => 'Inspect user and system activity.'],
                        ['slug' => 'archives', 'label' => 'Archives', 'description' => 'Browse archived system records.'],
                    ],
                ],
            ],
        ],
    ],
    'station_code' => env('APP_CODE', 'mnl'),
    'stations' => [
        'mnl' => 'Manila · RX 93.1',
        'cbu' => 'Cebu · BT 105.9',
        'dav' => 'Davao · BT 99.5',
    ],
    'station_switch_levels' => [1, 2],
    'resources' => [
        'staff' => [
            'staffs' => ['table' => 'employees', 'label' => 'Staff', 'write_levels' => [1, 2, 6]],
            'designations' => ['table' => 'designations', 'label' => 'Designations'],
            'jocks' => ['table' => 'jocks', 'label' => 'Jocks'],
            'radio1-batches' => ['table' => 'student_jocks_batches', 'label' => 'Radio1 Batches'],
            'student-jocks' => ['table' => 'student_jocks', 'label' => 'Student Jocks'],
            'awards' => ['table' => 'awards', 'label' => 'Awards'],
        ],
        'music' => [
            'artists' => ['table' => 'artists', 'label' => 'Artists'],
            'albums' => ['table' => 'albums', 'label' => 'Albums'],
            'songs' => ['table' => 'songs', 'label' => 'Songs'],
            'genres' => ['table' => 'genres', 'label' => 'Genres'],
            'station-chart' => [
                'table' => 'charts',
                'label' => 'Station Chart',
                'filters' => [['column' => 'daily', 'operator' => '=', 'value' => 0]],
            ],
            'daily-survey-top-5' => [
                'table' => 'charts',
                'label' => 'Daily Survey Top 5',
                'filters' => [['column' => 'daily', 'operator' => '=', 'value' => 1]],
            ],
            'votes' => ['table' => 'votes', 'label' => 'Votes', 'read_only' => true],
            'dropouts' => [
                'table' => 'charts',
                'label' => 'Dropouts',
                'filters' => [
                    ['column' => 'daily', 'operator' => '=', 'value' => 0],
                    ['column' => 'is_dropped', 'operator' => '=', 'value' => 1],
                ],
            ],
            'indieground-artists' => ['table' => 'indiegrounds', 'label' => 'Indieground Artists'],
            'indieground-featured' => ['table' => 'featured_indiegrounds', 'label' => 'Featured Indieground'],
        ],
        'digital-content-programs' => [
            'articles' => ['table' => 'articles', 'label' => 'Articles', 'write_levels' => [1, 2, 6]],
            'categories' => ['table' => 'categories', 'label' => 'Categories', 'write_levels' => [1, 2, 6]],
            'graphics-artist' => ['table' => 'headers', 'label' => 'Graphics'],
            'mobile-application' => ['table' => 'mobile_app_assets', 'label' => 'Mobile Application Assets'],
            'wallpapers' => ['table' => 'wallpapers', 'label' => 'Wallpapers'],
            'monster-music-awards' => ['table' => 'music_awards_releases', 'label' => 'Monster Music Awards'],
            'shows' => ['table' => 'shows', 'label' => 'Shows'],
            'timeslots' => ['table' => 'timeslots', 'label' => 'Timeslots'],
            'podcasts' => ['table' => 'podcasts', 'label' => 'Podcasts'],
        ],
        'events-scholarship' => [
            'schools' => ['table' => 'schools', 'label' => 'Schools'],
            'gimik-board' => ['table' => 'gimikboards', 'label' => 'Gimik Board'],
            'scholar-batches' => ['table' => 'batches', 'label' => 'Scholar Batches'],
            'students' => ['table' => 'students', 'label' => 'Students'],
            'sponsors' => ['table' => 'sponsors', 'label' => 'Sponsors'],
        ],
        'promos' => [
            'giveaways' => ['table' => 'giveaways', 'label' => 'Giveaways'],
            'contestants' => ['table' => 'contestants', 'label' => 'Contestants'],
        ],
        'utilities' => [
            'users' => ['table' => 'users', 'label' => 'Users', 'read_only' => true],
            'reports' => ['table' => 'reports', 'label' => 'Reports'],
            'logs' => ['table' => 'user_logs', 'label' => 'Activity Logs', 'read_only' => true],
            'archives' => ['table' => 'user_logs', 'label' => 'Archived Logs', 'read_only' => true, 'archive_logs' => true],
        ],
    ],
];
