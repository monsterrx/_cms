<script type="text/javascript">
    /* Section is for accesing the routes */
    @if(Auth::user())
    if (window.location.href === '{{ route('users.profile', Auth::user()->Employee->employee_number) }}') {
        loadProfile();
    }
    @endif

    if (window.location.href === '{{ route('charts.daily') }}') {
        loadDailyCharts();
        loadArtists();

        $(document).on('click', '#newDailyChart, #updateDailyChart, #deleteDailyChart', function () {
            let id = $(this).attr('data-id');

            getAsync('{{ route('charts.daily') }}', {"song_id": id}, 'JSON', beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                $('#new_song_id, #update_song_id, #delete_song_id').empty();

                $('#update_song_id').val(result.song.id);
                $('#update_positions').val(result.Positions);
                $('#update_dated').val(result.dated);

                $('#delete_song_id').val(result.id);

                $('#updateDailyChartedSongForm').attr('action', '{{ route('charts.index') }}' + '/' + result.id);

                $('#modal-body').html('Are you sure to remove <strong>' + result.song.name + '</strong> by <strong>' + result.song.album.artist.name + '</strong> from The Daily Survey Charts?');
            }
        });

        $(document).on('submit', '#deleteDailyChartedSongForm', function (event) {
            event.preventDefault();
            let url = $(this).attr('action');
            let formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: () => {
                    manualToast.fire({
                        icon: 'info',
                        title: 'Sending request ...'
                    });
                },
                success: (response) => {
                    $('#deleteChart').modal('hide');
                    $('button[type="submit"]').removeAttr('disabled');

                    loadDailyCharts();

                    Toast.fire({
                        icon: response.status,
                        title: response.message
                    });
                },
                error: (error) => {
                    $('#deleteChart').modal('hide');
                    $('button[type="submit"]').removeAttr('disabled');

                    manualToast.fire({
                        icon: 'error',
                        title: error.status + ' ' + error.statusText
                    });
                }
            });
        });

        // On modal show, click the artist tab.
        $('#dataList').on('shown.bs.modal', function () {
            $('[data-name="artist"]').click();
        });

        $('a[data-toggle="tab"]').on('click', function (e) {
            let tab = $(this).text();

            switch (tab) {
                case 'Artist':
                    $('#tabButtons').empty();
                    $('#tabButtons').append('<a href="#new-artist" class="btn btn-outline-dark btn-block" data-toggle="modal" data-dismiss="modal">New Artist</a>');
                    $('#tabs').find('#' + tab.toLowerCase() + 'Tbl').removeAttr('hidden');
                    $('#albumTbl').attr('hidden', 'hidden');
                    $('#songTbl').attr('hidden', 'hidden');
                    break;
                case 'Album':
                    $('#tabButtons').empty();
                    $('#tabButtons').append('<a href="#new-album" class="btn btn-outline-dark btn-block" data-toggle="modal" data-dismiss="modal">New Album</a>');
                    $('#tabs').find('#' + tab.toLowerCase() + 'Tbl').removeAttr('hidden');
                    $('#artistTbl').attr('hidden', 'hidden');
                    $('#songTbl').attr('hidden', 'hidden');
                    break;
                case 'Song':
                    $('#tabButtons').empty();
                    $('#tabButtons').append('<a href="#new-song" class="btn btn-outline-dark btn-block" data-toggle="modal" data-dismiss="modal">New Song</a>');
                    $('#tabs').find('#' + tab.toLowerCase() + 'Tbl').removeAttr('hidden');
                    $('#artistTbl').attr('hidden', 'hidden');
                    $('#albumTbl').attr('hidden', 'hidden');
                    break;
                default:
                    $('#tabButtons').empty();
                    $('#artistTbl').attr('hidden', 'hidden');
                    $('#albumTbl').attr('hidden', 'hidden');
                    $('#albumTbl').attr('hidden', 'hidden');
                    break;
            }
        });
    }

    if (window.location.href === '{{ route('home') }}') {
        let station_code = '{{ env('STATION_CODE') }}';

        if (station_code !== 'mnl') {
            loadOutbreaks();
        } else {
            loadShows();
        }
    }

    if (window.location.href === '{{ route('songs.index') }}') {
        loadArtists();
    }

    if (window.location.href === '{{ route('charts.index') }}') {
        loadChartData();
        loadDates();
    }

    if (window.location.href === '{{ route('survey.votes') }}') {
        loadVotingCharts();
        loadDates();
    }

    if (window.location.href === '{{ route('podcasts.index') }}') {
        loadShows();
    }

    if (window.location.href === '{{ route('articles.index') }}') {
        loadArticles();
    }

    if (window.location.href === '{{ route('users.index') }}') {
        usersTable.ajax.reload(null, false);
    }

    if (window.location.href === '{{ route('students.index') }}') {
        schoolsTable.ajax.reload(null, false);
        loadSchools();
    }

    if (window.location.href === '{{ route('indiegrounds.index') }}') {
        indiegroundsTable.ajax.reload(null, false);

        $('#artist_id').on('change', function () {
            let artist_id = $(this).find(':selected').val();

            if (!artist_id) {

            } else {
                $.ajax({
                    url: '{{ route('charts.index') }}' + '/' + artist_id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: (response) => {
                        if (response.image === null || !response.image) {
                            $('#image').attr('required', 'required');
                            manualToast.fire({
                                icon: 'info',
                                title: 'Image missing',
                            });
                        } else {
                            $('#indieground_image').attr('hidden', 'hidden');
                            $('#image').removeAttr('required');

                            Toast.fire({
                                icon: 'info',
                                title: 'Image detected',
                            });
                        }
                    },
                    error: (error) => {
                        Toast.fire({
                            icon: 'error',
                            title: error.status + ' ' + error.statusText
                        });
                    }
                });
            }
        });

        $(document).on('click', '#indie-artist, #delete-indie', function () {
            let indie_artist_id = $(this).attr('data-id');

            getAsync('{{ route('indiegrounds.index') }}' + '/' + indie_artist_id, null, 'JSON', beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                $('#indie-name, #update-content, #image-container, #delete-indie-header, #delete-indie-body').empty();
                $('#indie-name, #delete-indie-header').empty().append(result.indieground.artist.name);
                $('#delete-indie-body').empty().append('<p class="h5 text-center">Are you sure to delete ' + result.indieground.artist.name + ' from indiegrounds?</p>')
                $('#indie_artist_id, #artist_id').val(result.indieground.artist_id);
                $('#image-container').empty().append('<div class="text-center"><img src="' + result.indieground.image + '" alt="' + result.indieground.artist.name + '" width="200px"></div>');
                tinymce.get('update-content').setContent('<p>' + result.indieground.introduction + '</p>');
                let content = tinymce.get('update-content').getContent();

                if (!content || content === null) {
                    $('button[type="submit"]').attr('disabled', 'disabled');
                } else {

                }

                // Setting the update-form action
                $('#indieground-form, #updateIndiegroundForm, #deleteIndiegroundForm').attr('action', '{{ url('indiegrounds') }}' + '/' + result.indieground.id);
            }
        });
    }

    if (window.location.href === '{{ route('timeslots.index') }}') {
        loadTimeslots();
    }

    if (window.location.href === '{{ route('featured.index') }}') {
        loadFeaturedIndie();

        $(document).on('click', '#update-featured-artist, #delete-featured-artist', function () {
            let indie_artist_id = $(this).attr('data-id');

            getAsync('{{ route('featured.index') }}' + '/' + indie_artist_id, null, 'JSON', beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                $('#update-featured-indie-header, #update-content, #delete-featured-indie-header, #delete-featured-indie-body').empty();
                $('#update-featured-indie-header, #delete-featured-indie-header').append(result.indie.artist.name);
                $('#delete-featured-indie-body').append('<p class="h5 text-center">Are you sure to delete ' + result.indie.artist.name + ' from featured indiegrounds?</p>')
                $('#update_featured_artist_id').val(result.indie.id);
                tinymce.get('update-content').setContent('<p>' + result.content + '</p>');
                let content = tinymce.get('update-content').getContent();

                if (!content || content === null) {
                    $('button[type="submit"]').attr('disabled', 'disabled');
                } else {

                }

                getAsync('{{ url('featured') }}' + '/' + indie_artist_id, {"albums": 1,}, 'HTML', beforeSend, onSuccess);

                function beforeSend() {
                    manualToast.fire({
                        'icon': 'info',
                        'title': 'Loading Albums ...'
                    });
                }

                function onSuccess(result) {
                    Toast.fire({
                        'icon': 'success',
                        'title': 'Albums are loaded',
                    });

                    $('#artist_albums_row').empty().append(result);
                }

                // Setting the update-form action
                $('#updateFeaturedIndieForm, #deleteFeaturedIndieForm').attr('action', '{{ route('featured.index') }}' + '/' + result.id);
            }
        });
    }

    if (window.location.href === '{{ route('user_logs.index') }}') {
        getAsync('{{ route('user_logs.index') }}', {"process": 'load'}, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#userLogsContainer').empty();
            $('#userLogsContainer').append(result);

            $('#userLogsTable').DataTable({
                ajax: {
                    url: '{{ route('user_logs.index') }}',
                    dataSrc: '',
                },
                columns: [
                    {data: 'created_at'},
                    {data: 'user.email'},
                    {data: 'action'},
                    {data: 'employee.employee_number'},
                ],
                order: [
                    0, 'desc',
                ]
            });
        }
    }

    if (window.location.href === '{{ route('archives.index') }}') {
        getAsync('{{ route('archives.index') }}', {"process": 'load'}, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#archivedLogsContainer').empty();
            $('#archivedLogsContainer').append(result);

            $('#archivedLogsTable').DataTable({
                ajax: {
                    url: '{{ route('archives.index') }}',
                    dataSrc: '',
                },
                columns: [
                    {data: 'created_at'},
                    {data: 'user.email'},
                    {data: 'action'},
                    {data: 'employee.employee_number'},
                ],
                order: [
                    0, 'desc',
                ]
            });
        }
    }

    if (window.location.href === '{{ route('outbreaks.index') }}') {
        loadOutbreaks();
    }

    if (window.location.href === '{{ route('southsides.index') }}') {
        loadLocalDates();
        loadLocalCharts();
    }
    /* End */
</script>
