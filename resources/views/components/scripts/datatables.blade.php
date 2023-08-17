<script type="text/javascript">
    /* DataTables */
    let awardsTable = $('#awardsTable').DataTable({
        order: [
            0, 'desc',
        ]
    });
    let showsTable = $('#showsTable').DataTable({
        order: [
            [4, 'asc']
        ]
    });
    let employeesTable = $('#employeesTable').DataTable({
        ajax: {
            url: '{{ route('employees.index') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'employee_number'},
            {data: 'name'},
            {data: 'designation.name'},
            {data: 'location'},
            {data: 'options'},
        ],
        order: [
            1, 'asc'
        ]
    });
    let jocksTable = $('#jocksTable').DataTable({
        ajax: {
            url: '{{ route('jocks.index') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'id'},
            {data: 'employee_name'},
            {data: 'name'},
            {data: 'moniker'},
            {data: 'location'},
            {data: 'options'},
        ],
        order: [
            [1, 'asc'],
        ]
    });
    let usersTable = $('#usersTable').DataTable({
        ajax: {
            url: '{{ route('users.index') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'updated_at'},
            {data: 'id'},
            {data: 'name'},
            {data: 'employee.designation.name'},
            {data: 'employee.location'},
            {data: 'email'},
        ],
        order: [
            0, 'desc'
        ],
    });
    let wallpapersTable = $('#wallpapersTable').DataTable({
        ajax: {
            url: '{{ route('wallpapers.index') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'device'},
            {data: 'location'},
            {data: 'name'},
            {data: 'options'}
        ],
    });
    let messagesTable = $('#messageTable').DataTable({
        ajax: {
            url: '{{ route('messages.index') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'id'},
            {data: 'created_at'},
            {data: 'name'},
            {data: 'topic'},
            {data: 'is_seen'},
            {data: 'options'}
        ],
        order: [
            [0, 'desc']
        ]
    });
    let localSongsList = $('#localSongsList').DataTable({
        ajax: {
            url: '{{ route('southsides.index') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'artist.album.song.id'},
            {data: 'year'},
            {data: 'artist.album.song.name'},
            {data: 'artist.name'},
            {data: 'album.name'}
        ],
        order: [
            [0, 'desc']
        ]
    });
    let podcastsTable = $('#podcastTable').DataTable({
        ajax: {
            url: '{{ route('podcasts.index') }}',
            dataSrc: 'podcasts',
        },
        columns: [
            {data: 'date'},
            {data: 'show.title'},
            {data: 'episode'},
            {data: 'options'}
        ],
        order: [
            [0, 'desc'],
        ]
    });
    let artistsTable = $('#artistsTable').DataTable({
        ajax: {
            url: '{{ route('reload.artists') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'name'},
            {data: 'country'},
            {data: 'type'},
            {data: 'options'}
        ],
    });
    let albumsTable = $('#albumsTable').DataTable({
        ajax: {
            url: '{{ route('reload.albums') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'name'},
            {data: 'artist.name'},
            {data: 'year'},
            {data: 'options'}
        ],
    });
    let songsTable = $('#songsTable').DataTable({
        ajax: {
            url: '{{ route('reload.songs') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'name'},
            {data: 'album.artist.name'},
            {data: 'album.name'},
            {data: 'album.year'},
            {data: 'track_link'},
            {data: 'options'}
        ],
    });
    let songsList = $('#songsList').DataTable({
        ajax: {
            url: '{{ route('charts.daily') }}',
            dataSrc: 'songs',
        },
        columns: [
            {data: 'id'},
            {data: 'album.year'},
            {data: 'name'},
            {data: 'album.artist.name'},
            {data: 'album.name'}
        ],
        order: [
            [0, 'desc']
        ]
    });
    let categoriesTable = $('#categoriesTable').DataTable({
        ajax: {
            url: '{{ route('categories.index') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'name'},
            {data: 'description'},
            {data: 'created_date'},
            {data: 'options'}
        ]
    });
    let reportsTable = $('#reportsTable').DataTable({
        ajax: {
            url: '{{ route('reports') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'created_at'},
            {data: 'title'},
            {data: 'description'},
            {data: 'name'},
            {data: 'option'}
        ],
        order: [
            0, 'desc',
        ]
    });
    let schoolsTable = $('#schoolsTable').DataTable({
        ajax: {
            url: '{{ route('students.index') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'name'},
            {data: 'school.name'},
            {data: 'year_level'},
            {data: 'course'},
            {data: 'options'}
        ],
        order: [
            0, 'desc',
        ]
    });
    let indiegroundsTable = $('#indiegroundsTable').DataTable({
        ajax: {
            url: '{{ route('indiegrounds.index') }}',
            dataSrc: 'indiegrounds',
        },
        columns: [
            {data: 'artist.name'},
            {data: 'introduction'},
            {data: 'option'},
        ]
    });
    let featuredIndiegroundsTable = $('#featuredIndieTable').DataTable({
        ajax: {
            url: '{{ route('featured.index') }}',
            dataSrc: 'indie',
            data: {
                "refresh": true,
            }
        },
        columns: [
            {data: 'indie.artist.name'},
            {data: 'indie.date_featured'},
            {data: 'indie.artist.type'},
            {data: 'option'},
        ]
    });
    let outbreaksTable = $('#outbreaksTable').DataTable({
        ajax: {
            url: '{{ route('outbreaks.index') }}',
            dataSrc: 'outbreaks'
        },
        columns: [
            {data: 'dated'},
            {data: 'song.name'},
            {data: 'song.album.artist.name'},
            {data: 'options'},
        ],
        order: [
            0, 'desc',
        ]
    })

    let logsTable = $('#logsTable').DataTable({
        ajax: {
            url: '{{ route('reload.logs') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'dated'},
            {data: 'action'},
            {data: 'name'},
            {data: 'name'},
        ],
        order: [
            [0, 'desc']
        ]
    });
    let votesTable = $('#votesTable').DataTable({
        ajax: {
            url: '',
            dataSrc: '',
        },
        columns: [
            {data: "votes"},
            {data: "onlineVotes"},
            {data: "socmedVotes"},
            {data: "phoneVotes"},
            {data: "name"},
            {data: "voted_at"},
            {data: "options"},
        ],
        order: [
            [0, "desc"]
        ]
    });
    let tallyVotes = $('#tallyLogsTable').DataTable({
        ajax: {
            url: '{{ route('reload.tally') }}',
            dataSrc: '',
        },
        columns: [
            {data: 'dated'},
            {data: 'tally'},
            {data: 'lastTally'},
            {data: 'name'},
        ],
        order: [
            [1, 'desc'],
        ]
    });
</script>
