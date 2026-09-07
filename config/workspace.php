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
                        ['slug' => 'station-chart', 'label' => "Station's Chart", 'description' => 'Build and publish the station countdown.'],
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
                        ['slug' => 'scholar-batches', 'label' => 'Monster Scholars', 'description' => 'Organize Monster Scholar batches, students, and sponsors.'],
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
                        ['slug' => 'messages', 'label' => 'Messages', 'description' => 'Read and reply to messages from the website.'],
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
        'mnl' => 'Manila · RX93.1',
        'cbu' => 'Cebu · BT105.9',
        'dav' => 'Davao · BT99.5',
    ],
    'station_switch_levels' => [1, 2],
    'resources' => [
        'staff' => [
            'staffs' => [
                'table' => 'employees',
                'label' => 'Staff',
                'write_levels' => [1, 2, 6],
                'columns' => ['id', 'employee_number', 'first_name', 'last_name', 'designation_id', 'is_active'],
            ],
            'designations' => ['table' => 'designations', 'label' => 'Designations'],
            'jocks' => [
                'table' => 'jocks',
                'label' => 'Jocks',
                'presentation' => 'jock-cards',
                'rich_text_fields' => ['description'],
                'hidden_fields' => ['slug_string', 'jock_type'],
                'generated_fields' => [
                    'slug_string' => ['source' => 'name', 'transform' => 'studly'],
                    'jock_type' => ['value' => 'jock', 'create_only' => true],
                ],
                'station_via' => [
                    'table' => 'employees',
                    'local_key' => 'employee_id',
                    'foreign_key' => 'id',
                    'column' => 'location',
                ],
                'uploads' => [
                    'main_image' => ['directory' => 'jocks', 'width' => 500, 'height' => 500, 'label' => 'Main page picture'],
                    'profile_image' => ['directory' => 'jocks', 'width' => 500, 'height' => 500, 'label' => 'Profile picture'],
                    'background_image' => ['directory' => 'jocks', 'width' => 1920, 'height' => 500, 'label' => 'Header picture'],
                ],
                'field_help' => [
                    'main_image' => 'Displayed on the website main page. Required size: 500 x 500.',
                    'profile_image' => "Displayed after a visitor opens the Jock's profile. Required size: 500 x 500.",
                    'background_image' => 'Displayed as the profile header. Required size: 1920 x 500.',
                ],
            ],
            'radio1-batches' => [
                'table' => 'student_jocks_batches',
                'label' => 'Radio1 Batches',
                'presentation' => 'batch-cards',
            ],
            'student-jocks' => [
                'table' => 'student_jocks',
                'label' => 'Student Jocks',
                'presentation' => 'student-jock-cards',
                'rich_text_fields' => ['description'],
                'uploads' => [
                    'image' => ['directory' => 'studentJocks', 'width' => 500, 'height' => 500, 'label' => 'Student Jock profile picture'],
                ],
                'field_options' => [
                    'position' => [
                        ['value' => 1, 'label' => 'Heads'],
                        ['value' => 2, 'label' => 'Seniors'],
                        ['value' => 3, 'label' => 'Juniors'],
                        ['value' => 4, 'label' => 'Babies'],
                    ],
                ],
            ],
            'awards' => [
                'table' => 'awards',
                'label' => 'Awards',
                'presentation' => 'award-cards',
                'field_order' => ['award_target', 'jock_id', 'show_id', 'name', 'title', 'description', 'year', 'is_special'],
                'virtual_fields' => [
                    'award_target' => [
                        'label' => 'Awarded To',
                        'type' => 'select',
                        'default' => 'jock',
                        'options' => [
                            ['value' => 'jock', 'label' => 'Jock'],
                            ['value' => 'show', 'label' => 'Show'],
                        ],
                    ],
                ],
                'field_overrides' => [
                    'jock_id' => ['show_when' => ['field' => 'award_target', 'value' => 'jock'], 'clears' => ['show_id']],
                    'show_id' => ['show_when' => ['field' => 'award_target', 'value' => 'show'], 'clears' => ['jock_id']],
                ],
            ],
        ],
        'music' => [
            'artists' => [
                'table' => 'artists',
                'label' => 'Artists',
                'uploads' => [
                    'image' => ['directory' => 'artists', 'width' => 500, 'height' => 500, 'label' => 'Square artist image'],
                ],
            ],
            'albums' => [
                'table' => 'albums',
                'label' => 'Albums',
                'uploads' => [
                    'image' => ['directory' => 'albums', 'width' => 500, 'height' => 500, 'label' => 'Square album artwork'],
                ],
            ],
            'songs' => [
                'table' => 'songs',
                'label' => 'Songs',
                'columns' => ['id', 'name', 'album_id', 'type', 'track_link', 'is_charted'],
                'hidden_fields' => ['votes'],
                'field_options' => [
                    'type' => [
                        ['value' => 'spotify', 'label' => 'Spotify'],
                        ['value' => 'sample', 'label' => 'Sample Track'],
                    ],
                ],
                'field_labels' => ['track_link' => 'Spotify Track Link'],
                'field_overrides' => [
                    'track_link' => [
                        'type' => 'url',
                        'show_when' => ['field' => 'type', 'value' => 'spotify'],
                    ],
                ],
            ],
            'genres' => ['table' => 'genres', 'label' => 'Genres'],
            'station-chart' => [
                'table' => 'charts',
                'label' => "Station's Chart",
                'hidden_form_fields' => ['votes', 'last_results', 'phone_votes', 'social_votes', 'online_votes', 'voted_at', 'is_posted'],
                'hidden_table_fields' => ['votes', 'last_results', 'phone_votes', 'social_votes', 'online_votes', 'voted_at', 'is_posted'],
                'filters' => [['column' => 'daily', 'operator' => '=', 'value' => 0]],
            ],
            'daily-survey-top-5' => [
                'table' => 'charts',
                'label' => 'Daily Survey Top 5',
                'hidden_form_fields' => ['votes', 'last_results', 'phone_votes', 'social_votes', 'online_votes', 'voted_at', 'is_posted'],
                'hidden_table_fields' => ['votes', 'last_results', 'phone_votes', 'social_votes', 'online_votes', 'voted_at', 'is_posted'],
                'filters' => [['column' => 'daily', 'operator' => '=', 'value' => 1]],
            ],
            'votes' => ['table' => 'charts', 'label' => 'Votes', 'read_only' => true],
            'dropouts' => [
                'table' => 'charts',
                'label' => 'Dropouts',
                'hidden_form_fields' => ['votes', 'last_results', 'phone_votes', 'social_votes', 'online_votes', 'voted_at', 'is_posted'],
                'hidden_table_fields' => ['votes', 'last_results', 'phone_votes', 'social_votes', 'online_votes', 'voted_at', 'is_posted'],
                'filters' => [
                    ['column' => 'daily', 'operator' => '=', 'value' => 0],
                    ['column' => 'is_dropped', 'operator' => '=', 'value' => 1],
                ],
            ],
            'indieground-artists' => [
                'table' => 'indiegrounds',
                'label' => 'Indieground Artists',
                'presentation' => 'indieground-artist-cards',
                'per_page' => 12,
                'rich_text_fields' => ['introduction'],
                'uploads' => [
                    'image' => ['directory' => 'indie', 'width' => 500, 'height' => 500, 'label' => 'Square Indieground artist image'],
                ],
            ],
            'indieground-featured' => [
                'table' => 'featured_indiegrounds',
                'label' => 'Featured Indieground',
                'presentation' => 'featured-indieground-cards',
                'per_page' => 12,
                'rich_text_fields' => ['content'],
                'field_labels' => [
                    'indieground_id' => 'Indieground Artist',
                ],
                'field_options' => [
                    'month' => [
                        ['value' => '01', 'label' => 'January'],
                        ['value' => '02', 'label' => 'February'],
                        ['value' => '03', 'label' => 'March'],
                        ['value' => '04', 'label' => 'April'],
                        ['value' => '05', 'label' => 'May'],
                        ['value' => '06', 'label' => 'June'],
                        ['value' => '07', 'label' => 'July'],
                        ['value' => '08', 'label' => 'August'],
                        ['value' => '09', 'label' => 'September'],
                        ['value' => '10', 'label' => 'October'],
                        ['value' => '11', 'label' => 'November'],
                        ['value' => '12', 'label' => 'December'],
                    ],
                ],
                'columns' => ['id', 'indieground_id', 'month', 'year'],
                'related_uploads' => [
                    'featured_artist_image' => [
                        'directory' => 'indie',
                        'width' => 500,
                        'height' => 500,
                        'label' => 'Featured Indieground artist image',
                        'relation_field' => 'indieground_id',
                        'target_table' => 'indiegrounds',
                        'target_column' => 'image',
                    ],
                ],
            ],
        ],
        'digital-content-programs' => [
            'articles' => [
                'table' => 'articles',
                'label' => 'Articles',
                'uploads' => [
                    'image' => ['directory' => 'articles', 'width' => 800, 'height' => 800, 'label' => 'Article image'],
                ],
                'write_levels' => [1, 2, 6],
                'presentation' => 'article-cards',
                'hidden_form_fields' => ['employee_id', 'unique_id'],
                'readonly_fields' => ['employee_id', 'unique_id'],
                'generated_fields' => [
                    'employee_id' => [
                        'generator' => 'authenticated_employee_id',
                        'create_only' => true,
                    ],
                    'unique_id' => [
                        'generator' => 'random_code',
                        'length' => 8,
                        'prefix' => 'RX',
                        'suffix' => '931',
                        'create_only' => true,
                    ],
                ],
            ],
            'categories' => [
                'table' => 'categories',
                'label' => 'Categories',
                'write_levels' => [1, 2, 6],
                'hidden_fields' => ['icon', 'dark_mode_icon'],
                'generated_fields' => [
                    'icon' => ['value' => 'default.png', 'create_only' => true],
                    'dark_mode_icon' => ['value' => 'default.png', 'create_only' => true],
                ],
            ],
            'graphics-artist' => [
                'table' => 'headers',
                'label' => 'Graphics',
                'presentation' => 'sortable-graphic-cards',
                'uploads' => [
                    'image' => ['directory' => 'headers', 'width' => 1697, 'height' => 625, 'label' => 'Website header graphic'],
                ],
            ],
            'mobile-application' => [
                'table' => 'mobile_app_assets',
                'label' => 'Mobile Application Assets',
                'presentation' => 'mobile-theme-cards',
                'uploads' => [
                    'logo' => ['directory' => '_assets/mobile', 'width' => 500, 'height' => 500, 'label' => 'Square application logo'],
                    'chart_icon' => ['directory' => '_assets/mobile', 'width' => 500, 'height' => 500, 'label' => 'Square chart icon'],
                    'article_icon' => ['directory' => '_assets/mobile', 'width' => 500, 'height' => 500, 'label' => 'Square article icon'],
                    'podcast_icon' => ['directory' => '_assets/mobile', 'width' => 500, 'height' => 500, 'label' => 'Square podcast icon'],
                    'article_page_icon' => ['directory' => '_assets/mobile', 'width' => 500, 'height' => 500, 'label' => 'Square article page icon'],
                    'youtube_page_icon' => ['directory' => '_assets/mobile', 'width' => 500, 'height' => 500, 'label' => 'Square YouTube page icon'],
                ],
            ],
            'wallpapers' => [
                'table' => 'wallpapers',
                'label' => 'Wallpapers',
                'presentation' => 'wallpaper-cards',
                'uploads' => [
                    'image' => [
                        'directory' => 'wallpapers',
                        'width' => 1920,
                        'height' => 1080,
                        'label' => 'Wallpaper',
                        'variants' => [
                            'selector' => 'device',
                            'web' => ['width' => 1920, 'height' => 1080, 'label' => 'Desktop wallpaper'],
                            'mobile' => ['width' => 1080, 'height' => 1920, 'label' => 'Mobile wallpaper'],
                        ],
                    ],
                ],
                'field_options' => [
                    'device' => [
                        ['value' => 'web', 'label' => 'Desktop'],
                        ['value' => 'mobile', 'label' => 'Mobile'],
                    ],
                ],
            ],
            'monster-music-awards' => [
                'table' => 'music_awards_releases',
                'label' => 'Monster Music Awards',
                'presentation' => 'music-award-cards',
            ],
            'shows' => [
                'table' => 'shows',
                'label' => 'Shows',
                'presentation' => 'show-cards',
                'hidden_fields' => ['slug_string'],
                'rich_text_fields' => ['description'],
                'uploads' => [
                    'icon' => ['directory' => 'shows', 'width' => 500, 'height' => 500, 'label' => 'Main show picture'],
                    'header_image' => ['directory' => 'shows', 'width' => 1920, 'height' => 500, 'label' => 'Show header picture'],
                    'background_image' => ['directory' => 'shows', 'width' => 1920, 'height' => 500, 'label' => 'Show background picture'],
                ],
                'field_options' => [
                    'is_special' => [
                        ['value' => 0, 'label' => 'Daily Show'],
                        ['value' => 1, 'label' => 'Special Show'],
                    ],
                ],
            ],
            'timeslots' => [
                'table' => 'timeslots',
                'label' => 'On-air Schedule',
                'presentation' => 'weekly-schedule',
                'field_options' => [
                    'day' => array_map(
                        static fn (string $day): array => ['value' => $day, 'label' => $day],
                        ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
                    ),
                ],
            ],
            'podcasts' => [
                'table' => 'podcasts',
                'label' => 'Podcasts',
                'presentation' => 'podcast-cards',
                'uploads' => [
                    'image' => ['directory' => 'podcasts', 'width' => 500, 'height' => 500, 'label' => 'Podcast picture'],
                ],
                'columns' => ['id', 'show_id', 'episode', 'date', 'link'],
                'default_sort' => 'date',
                'default_direction' => 'desc',
            ],
        ],
        'events-scholarship' => [
            'schools' => [
                'table' => 'schools',
                'label' => 'Schools',
                'columns' => ['id', 'seal', 'name', 'address'],
            ],
            'gimik-board' => [
                'table' => 'gimikboards',
                'label' => 'Gimik Board',
                'rich_text_fields' => ['title', 'description'],
                'columns' => ['id', 'school_name', 'name', 'start_date', 'end_date'],
                'field_order' => ['school_id', 'name', 'start_date', 'end_date', 'title', 'description', 'image', 'is_published'],
                'field_labels' => ['id' => 'ID', 'school_id' => 'School'],
                'virtual_fields' => [
                    'school_name' => [
                        'label' => 'School',
                        'form' => false,
                        'nullable' => true,
                    ],
                ],
                'hidden_table_fields' => ['school_id', 'title', 'description', 'is_published'],
            ],
            'scholar-batches' => [
                'table' => 'batches',
                'label' => 'Monster Scholars',
                'presentation' => 'scholar-batch-cards',
                'uploads' => [
                    'image' => ['directory' => 'scholarBatch', 'width' => 1600, 'height' => 1066, 'label' => 'Monster Scholars batch image'],
                ],
            ],
            'students' => ['table' => 'students', 'label' => 'Students'],
            'sponsors' => ['table' => 'sponsors', 'label' => 'Sponsors'],
        ],
        'promos' => [
            'giveaways' => [
                'table' => 'giveaways',
                'label' => 'Giveaways',
                'presentation' => 'giveaway-cards',
                'rich_text_fields' => ['description'],
                'hidden_form_fields' => ['code'],
                'generated_fields' => [
                    'code' => ['generator' => 'random_code', 'length' => 10, 'create_only' => true],
                ],
                'uploads' => [
                    'image' => ['directory' => 'giveaways', 'width' => 500, 'height' => 500, 'label' => 'Square giveaway image'],
                ],
                'field_labels' => [
                    'type' => 'Category',
                    'is_restricted' => 'General Audience?',
                ],
                'field_options' => [
                    'type' => [
                        ['value' => 'movies', 'label' => 'Monster Movie Premiere'],
                        ['value' => 'concerts', 'label' => 'Concert Tickets'],
                    ],
                    'is_restricted' => [
                        ['value' => 0, 'label' => 'Yes'],
                        ['value' => 1, 'label' => 'No'],
                    ],
                ],
            ],
            'contestants' => [
                'table' => 'contestants',
                'label' => 'Contestants',
                'read_only' => true,
                'hidden_fields' => ['image'],
                'columns' => ['id', 'first_name', 'middle_name', 'last_name', 'birthday', 'email', 'phone_number', 'city', 'is_verified'],
            ],
        ],
        'utilities' => [
            'users' => ['table' => 'users', 'label' => 'Users', 'read_only' => true],
            'messages' => ['table' => 'messages', 'label' => 'Website Messages', 'read_only' => true, 'columns' => ['id', 'name', 'email', 'topic', 'content', 'is_seen', 'created_at']],
            'reports' => ['table' => 'reports', 'label' => 'Reports'],
            'logs' => ['table' => 'user_logs', 'label' => 'Activity Logs', 'read_only' => true],
            'archives' => ['table' => 'user_logs', 'label' => 'Archived Logs', 'read_only' => true, 'archive_logs' => true],
        ],
    ],
];
