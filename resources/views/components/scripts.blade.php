<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables.min.js') }}"></script>
<script src="{{ asset('js/feather.min.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="{{ asset('js/all.min.js') }}"></script>
 <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('js/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/croppie.min.js') }}"></script>
<script src="{{ asset('js/customScripts.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json; utf-8;',
        },
    });

    setTimeout(function(){
        $('.alert-success').fadeOut();
        $('.alert-danger').fadeOut();
    }, 2500);

    setTimeout(() => {
        Toast.close();
    }, 3500)

    function viewData() {
        $('*[data-href]').on('click', function () {
            window.location = $(this).data("href");
        });
    }

    function readFile(input) {
        if (input.files && input.files[0]) {
            if (/^image/.test( input.files[0].type)){ // read image file
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('.cropper').addClass('ready');
                    $('.cropperHeader').addClass('ready');
                    $imageToBeCropped.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        console.log('Croppie: jQuery bind complete');
                    });
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                manualToast.fire({
                    icon: 'warning',
                    title: 'You may only select image files',
                });
            }
        } else {
            manualToast.fire({
                icon: 'warning',
                title: 'Sorry - you\'re browser doesn\'t support the FileReader API',
            });
        }
    }

    function getDate() {
        return new Date().toLocaleDateString('en', { weekday: "long" });
    }

    function getAsync(url, parameters = null, type = 'JSON', beforeSendCallback, successCallback) {
        $.ajax({
            url: url,
            type: 'GET',
            data: parameters,
            dataType: type,
            beforeSend: beforeSendCallback,
            success: successCallback,
            error: (xhr, textStatus, errorThrown) => {
                $('.modal').modal('hide');
                $('button[type="submit"]').removeAttr('disabled');
                $('button[type="submit"]').html('Save');

                if(xhr.responseJSON) {
                    let errors = "";

                    if(Array.isArray(xhr.responseJSON)) {
                        for(let i = 0; i <= xhr.responseJSON.length; i++) {
                            errors = '<p>'+xhr.responseJSON[i].message+'</p>';
                        }
                    } else {
                        errors = '<p>'+xhr.responseJSON.message+'</p>';
                    }

                    manualToast.fire({
                        icon: 'error',
                        title: 'Error/s had been encountered while processing your request',
                        html: '' +
                            ''+errors+'',
                    });
                } else {
                    manualToast.fire({
                        icon: 'error',
                        title: xhr.status + ' ' + xhr.statusText,
                    });
                }
            }
        });
    }

    function postAsync(url, parameters, type = 'JSON', beforeSendCallback, successCallback) {
        $.ajax({
            url: url,
            type: 'POST',
            data: parameters,
            dataType: type,
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: beforeSendCallback,
            success: successCallback,
            error: (xhr, textStatus, errorThrown) => {
                console.log(xhr);
                $('.modal').modal('hide');
                $('button[type="submit"]').removeAttr('disabled');
                $('button[type="submit"]').html('Save');

                if(xhr.responseJSON) {
                    let errors = "";

                    if(Array.isArray(xhr.responseJSON)) {
                        for(let i = 0; i <= xhr.responseJSON.length; i++) {
                            errors = '<p>'+xhr.responseJSON[i].message+'</p>';
                        }
                    } else if(xhr.responseJSON.message) {
                        errors = '<p>'+xhr.responseJSON.message+'</p>';
                    } else {
                        errors = '<p>'+xhr.responseJSON.error+'</p>';
                    }

                    manualToast.fire({
                        icon: 'error',
                        title: 'Error/s had been encountered while processing your request',
                        html: '' +
                            ''+errors+'',
                    });
                } else {
                    if(xhr.responseText.includes('SQLSTATE[23000]')) {
                        errors = '<p>A field has been <span class="badge badge-danger">Undefined</span></p>';

                        manualToast.fire({
                            icon: 'error',
                            title: 'Error/s had been encountered while processing your request',
                            html: '' +
                                ''+errors+'',
                        });
                    } else {
                        manualToast.fire({
                            icon: 'error',
                            title: xhr.status + ' ' + xhr.statusText,
                        });
                    }
                }
            }
        });
    }

    function loadArtists() {
        getAsync('{{ route('artists.index') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            console.log('Artists have been loaded');
            $('#update_song_artist_id, #song_artist_id, #album_artist_id').append(result);
        }
    }

    // Weekly Chart Dates
    function loadDates() {
        getAsync('{{ route('get.chart.dates') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#chartDates, #newEntryChartDate').empty();
            $('#chartDates, #newEntryChartDate').append(result.dates);

            $('#official, #draft, #post').attr('data-payload', result.latest);
        }
    }

    function loadLocalDates() {
        getAsync('{{ route('southsides.create') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            console.log(result);
            $('#chartDates, #newEntryChartDate').empty();
            $('#chartDates, #newEntryChartDate').append(result.dates);

            $('#official, #draft').attr('data-payload', result.latest);
        }
    }

    // Charts
    function loadDailyCharts(){
        getAsync('{{ route('charts.daily') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            if(result.dailyCharts === "" || !result.dailyCharts) {
                $('#dailyCharts').empty();
                $('#dailyCharts').append('<tr><td colspan="6"><div class="alert alert-info mb-0"><div class="text-center">The Daily Survey Top 5 charts goes here. click <strong>New Entry</strong> to create.</div></div></td></tr>')
            } else {
                $('#dailyCharts').empty();
                $('#dailyCharts').append(result.dailyCharts);
            }

            if(result.surveyDates === "" || !result.dailyCharts) {
                $('#surveyDate').empty();
                $('#surveyDate').append('<option value selected disabled>--</option>');
            } else {
                $('#surveyDate').empty();
                $('#surveyDate').append(result.surveyDates);
            }
        }
    }

    function loadChartData(){
        getAsync('{{ route('charts.index') }}', null, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#monsterCharts').empty();
            $('#monsterCharts').append(result);
        }
    }

    function loadVotingCharts(){
        getAsync('{{ route('survey.votes') }}', null, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#monsterCharts').empty();
            $('#monsterCharts').append(result);
        }
    }

    function loadLocalCharts(){
        getAsync('{{ route('southsides.index') }}', { "load": "charts" }, 'HTML', beforeSend, onSuccess);

        function beforeSend() {
            $('#monsterCharts').empty();
            $('#monsterCharts').append('<div class="text-center"><div class="spinner-border text-secondary" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div></div>');
        }

        function onSuccess(result) {
            $('#monsterCharts').empty();
            $('#monsterCharts').append(result);
        }
    }
    // End

    // Shows
    function loadShows(){
        getAsync('{{ route('podcasts.index') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#show_id, #update_show_id').empty();
            $('#show_id, #update_show_id').append(result.shows);
            Toast.fire({
                icon: 'success',
                title: 'Shows has been loaded',
            });
        }
    }
    // End

    // Articles
    function loadArticles() {
        let article = 'published';

        getAsync('{{ route('articles.index') }}', { "status": article }, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#articleStatus').empty();
            $('#articleStatus').append(result);
        }
    }

    function loadYearlyArticles() {
        getAsync('{{ route('yearly.articles') }}', null, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#articleStatus').empty();
            $('#articleStatus').append(result);
        }
    }

    function loadArchivedArticles() {
        getAsync('{{ route('article.archives') }}', null, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#articleStatus').empty();
            $('#articleStatus').append(result);
            $('#genericTable').DataTable({
                order: [
                    [ 0, 'desc' ]
                ]
            });
        }
    }
    // End

    function loadSchools() {
        getAsync('{{ route('students.index') }}', { "location": '{{ env('STATION_CODE') }}' }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#school_id').empty();
            $('#school_id, #student_school_id').append(result);
        }
    }

    function loadTimeslots() {
        getAsync('{{ route('timeslots.index') }}', { 'day': true }, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#timeslot-days li a:contains('+result+')').click();
            $('#switch_timeslots').attr('day', result);
        }
    }

    function loadFeaturedIndie() {
        getAsync('{{ route('featured.index') }}', null, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#featuredIndieContainer').empty();
            $('#featuredIndieContainer').append(result);
        }
    }

    function loadOutbreaks() {
        getAsync('{{ route('outbreaks.index') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#outbreak_song_id, #outbreakSongsContainer, #update_outbreak_song_id').empty();
            $('#outbreak_song_id, #update_outbreak_song_id').append(result.options);

            let outbreaks = result.outbreaks.length;
            let spotifyPlayer = "";

            if(outbreaks > 0) {
                for(let i = 0; i < result.outbreaks.length; i++) {
                    spotifyPlayer+='<div class="col-md-6"><div class="embed-container"><iframe src="https://open.spotify.com/embed/track/'+result.outbreaks[i].track_link+'" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe></div></div>';
                }

                $('#outbreakSongsContainer').append(spotifyPlayer);
            }

            let outbreak_song = $('#outbreak_song_id').val();

            $('#newOutbreak').on('shown.bs.modal', function(){
                getAsync('{{ url()->current() }}' + '/' + outbreak_song, { "song_id": outbreak_song }, 'JSON', beforeSend, onSuccess);

                function beforeSend() {

                }

                function onSuccess(result) {
                    if(result === true) {
                        $('#linkString').attr('hidden', 'hidden');
                        Toast.fire({
                            icon: 'info',
                            title: 'Song already has a demo',
                        });
                    } else {
                        $('#linkString').removeAttr('hidden');
                        $('#linkString').attr('required', 'required');
                    }
                }
            });
        }
    }

    @if(Auth::user())
    function loadProfile() {
        getAsync('{{ route('users.profile', Auth::user()->Employee->employee_number) }}', null, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#userProfile').empty();
            $('#userProfile').append(result);
        }
    }
    @endif

    function getAge(date){
        let today = new Date();
        let birthDate = new Date(date);
        let age = today.getFullYear() - birthDate.getFullYear();
        let m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    /* DataTables */
    let awardsTable = $('#awardsTable').DataTable({
        order: [
            0, 'desc',
        ]
    });
    let showsTable = $('#showsTable').DataTable({
        order: [
            [ 4, 'asc']
        ]
    });
    let employeesTable = $('#employeesTable').DataTable({
        ajax: {
            url: '{{ route('employees.index') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'employee_number' },
            { data: 'name' },
            { data: 'designation.name' },
            { data: 'location' },
            { data: 'options' },
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
            { data: 'id' },
            { data: 'employee_name' },
            { data: 'name' },
            { data: 'moniker' },
            { data: 'location' },
            { data: 'options' },
        ],
        order: [
            [ 1, 'asc' ],
        ]
    });
    let usersTable = $('#usersTable').DataTable({
        ajax: {
            url: '{{ route('users.index') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'updated_at' },
            { data: 'id' },
            { data: 'name' },
            { data: 'employee.designation.name' },
            { data: 'employee.location' },
            { data: 'email' },
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
            { data: 'device' },
            { data: 'location' },
            { data: 'name' },
            { data: 'options' }
        ],
    });
    let messagesTable = $('#messageTable').DataTable({
        ajax: {
            url: '{{ route('messages.index') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'id' },
            { data: 'created_at' },
            { data: 'name' },
            { data: 'topic' },
            { data: 'is_seen' },
            { data: 'options' }
        ],
        order: [
            [ 0, 'desc' ]
        ]
    });
    let localSongsList = $('#localSongsList').DataTable({
        ajax: {
            url: '{{ route('southsides.index') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'artist.album.song.id' },
            { data: 'year' },
            { data: 'artist.album.song.name' },
            { data: 'artist.name' },
            { data: 'album.name' }
        ],
        order: [
            [ 0, 'desc' ]
        ]
    });
    let podcastsTable = $('#podcastTable').DataTable({
        ajax: {
            url: '{{ route('podcasts.index') }}',
            dataSrc: 'podcasts',
        },
        columns: [
            { data: 'date' },
            { data: 'show.title' },
            { data: 'episode' },
            { data: 'options' }
        ],
        order: [
            [ 0, 'desc'],
        ]
    });
    let artistsTable = $('#artistsTable').DataTable({
        ajax: {
            url: '{{ route('reload.artists') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'name' },
            { data: 'country' },
            { data: 'type' },
            { data: 'options' }
        ],
    });
    let albumsTable = $('#albumsTable').DataTable({
        ajax: {
            url: '{{ route('reload.albums') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'name' },
            { data: 'artist.name' },
            { data: 'year' },
            { data: 'options' }
        ],
    });
    let songsTable = $('#songsTable').DataTable({
        ajax: {
            url: '{{ route('reload.songs') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'name' },
            { data: 'album.artist.name' },
            { data: 'album.name' },
            { data: 'album.year' },
            { data: 'track_link' },
            { data: 'options' }
        ],
    });
    let songsList = $('#songsList').DataTable({
        ajax: {
            url: '{{ route('charts.daily') }}',
            dataSrc: 'songs',
        },
        columns: [
            { data: 'id' },
            { data: 'album.year' },
            { data: 'name' },
            { data: 'album.artist.name' },
            { data: 'album.name' }
        ],
        order: [
            [ 0, 'desc' ]
        ]
    });
    let categoriesTable = $('#categoriesTable').DataTable({
        ajax: {
            url: '{{ route('categories.index') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'name' },
            { data: 'description' },
            { data: 'created_at' },
            { data: 'options' }
        ]
    });
    let reportsTable = $('#reportsTable').DataTable({
        ajax: {
            url: '{{ route('reports') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'created_at' },
            { data: 'title' },
            { data: 'description' },
            { data: 'name' },
            { data: 'option' }
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
            { data: 'name' },
            { data: 'school.name' },
            { data: 'year_level' },
            { data: 'course' },
            { data: 'options' }
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
            { data: 'artist.name' },
            { data: 'introduction' },
            { data: 'option' },
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
            { data: 'indie.artist.name' },
            { data: 'indie.date_featured' },
            { data: 'indie.artist.type' },
            { data: 'option' },
        ]
    });
    let outbreaksTable = $('#outbreaksTable').DataTable({
        ajax: {
            url: '{{ route('outbreaks.index') }}',
            dataSrc: 'outbreaks'
        },
        columns: [
            { data: 'dated' },
            { data: 'song.name' },
            { data: 'song.album.artist.name' },
            { data: 'options' },
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
            { data: 'dated' },
            { data: 'action' },
            { data: 'name' },
            { data: 'name' },
        ],
        order: [
            [ 0, 'desc' ]
        ]
    });
    let votesTable = $('#votesTable').DataTable({
        ajax: {
            url: '',
            dataSrc: '',
        },
        columns: [
            { data : "votes" },
            { data : "onlineVotes" },
            { data : "socmedVotes" },
            { data : "phoneVotes" },
            { data : "name" },
            { data : "voted_at" },
            { data : "options" },
        ],
        order: [
            [ 0, "desc" ]
        ]
    });
    let tallyVotes = $('#tallyLogsTable').DataTable({
        ajax: {
            url: '{{ route('reload.tally') }}',
            dataSrc: '',
        },
        columns: [
            { data: 'dated' },
            { data: 'tally' },
            { data: 'lastTally' },
            { data: 'name' },
        ],
        order: [
            [ 1, 'desc' ],
        ]
    });
    /* End */

    /* Section is for accesing the routes */
    @if(Auth::user())
    if(window.location.href === '{{ route('users.profile', Auth::user()->Employee->employee_number) }}') {
        loadProfile();
    }
    @endif

    if(window.location.href === '{{ route('charts.daily') }}') {
        loadDailyCharts();
        loadArtists();

        $(document).on('click', '#newDailyChart, #updateDailyChart, #deleteDailyChart', function(){
            let id = $(this).attr('data-id');

            getAsync('{{ route('charts.daily') }}', { "song_id": id }, 'JSON', beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                $('#new_song_id, #update_song_id, #delete_song_id').empty();

                $('#update_song_id').val(result.song.id);
                $('#update_positions').val(result.Positions);
                $('#update_dated').val(result.dated);

                $('#delete_song_id').val(result.id);

                $('#updateDailyChartedSongForm').attr('action', '{{ route('charts.index') }}' + '/' + result.id);

                $('#modal-body').html('Are you sure to remove <strong>'+result.song.name+'</strong> by <strong>'+result.song.album.artist.name+'</strong> from The Daily Survey Charts?');
            }
        });

        $(document).on('submit', '#deleteDailyChartedSongForm', function(event){
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
        $('#dataList').on('shown.bs.modal', function() {
            $('[data-name="artist"]').click();
        });

        $('a[data-toggle="tab"]').on('click', function(e){
            let tab = $(this).text();

            switch(tab) {
                case 'Artist':
                    $('#tabButtons').empty();
                    $('#tabButtons').append('<a href="#new-artist" class="btn btn-outline-dark btn-block" data-toggle="modal" data-dismiss="modal">New Artist</a>');
                    $('#tabs').find('#'+tab.toLowerCase()+'Tbl').removeAttr('hidden');
                    $('#albumTbl').attr('hidden', 'hidden');
                    $('#songTbl').attr('hidden', 'hidden');
                    break;
                case 'Album':
                    $('#tabButtons').empty();
                    $('#tabButtons').append('<a href="#new-album" class="btn btn-outline-dark btn-block" data-toggle="modal" data-dismiss="modal">New Album</a>');
                    $('#tabs').find('#'+tab.toLowerCase()+'Tbl').removeAttr('hidden');
                    $('#artistTbl').attr('hidden', 'hidden');
                    $('#songTbl').attr('hidden', 'hidden');
                    break;
                case 'Song':
                    $('#tabButtons').empty();
                    $('#tabButtons').append('<a href="#new-song" class="btn btn-outline-dark btn-block" data-toggle="modal" data-dismiss="modal">New Song</a>');
                    $('#tabs').find('#'+tab.toLowerCase()+'Tbl').removeAttr('hidden');
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

    if(window.location.href === '{{ route('home') }}') {
        let station_code = '{{ env('STATION_CODE') }}';

        if(station_code !== 'mnl') {
            loadOutbreaks();
        } else {
            loadShows();
        }
    }

    if(window.location.href === '{{ route('songs.index') }}') {
        loadArtists();
    }

    if(window.location.href === '{{ route('charts.index') }}') {
        loadChartData();
        loadDates();
    }

    if(window.location.href === '{{ route('survey.votes') }}') {
        loadVotingCharts();
        loadDates();
    }

    if(window.location.href === '{{ route('podcasts.index') }}') {
        loadShows();
    }

    if(window.location.href === '{{ route('articles.index') }}') {
        loadArticles();
    }

    if(window.location.href === '{{ route('users.index') }}') {
        usersTable.ajax.reload(null, false);
    }

    if(window.location.href === '{{ route('students.index') }}') {
        schoolsTable.ajax.reload(null, false);
        loadSchools();
    }

    if(window.location.href === '{{ route('indiegrounds.index') }}') {
        indiegroundsTable.ajax.reload(null, false);

        $('#artist_id').on('change', function() {
            let artist_id = $(this).find(':selected').val();

            if(!artist_id) {

            } else {
                $.ajax({
                    url: '{{ route('charts.index') }}' + '/' + artist_id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: (response) => {
                        if(response.image === null || !response.image) {
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

        $(document).on('click', '#indie-artist, #delete-indie', function(){
            let indie_artist_id = $(this).attr('data-id');

            getAsync('{{ route('indiegrounds.index') }}' + '/' + indie_artist_id, null, 'JSON', beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                $('#indie-name, #update-content, #image-container, #delete-indie-header, #delete-indie-body').empty();
                $('#indie-name, #delete-indie-header').empty().append(result.indieground.artist.name);
                $('#delete-indie-body').empty().append('<p class="h5 text-center">Are you sure to delete '+result.indieground.artist.name+' from indiegrounds?</p>')
                $('#indie_artist_id, #artist_id').val(result.indieground.artist_id);
                $('#image-container').empty().append('<div class="text-center"><img src="{{ asset('images/indie') }}/'+result.indieground.image+'" alt="'+result.indieground.artist.name+'" width="200px"></div>');
                tinymce.get('update-content').setContent('<p>'+result.indieground.introduction+'</p>');
                let content = tinymce.get('update-content').getContent();

                if(!content || content === null) {
                    $('button[type="submit"]').attr('disabled', 'disabled');
                } else {

                }

                // Setting the update-form action
                $('#indieground-form, #updateIndiegroundForm, #deleteIndiegroundForm').attr('action', '{{ url('indiegrounds') }}' + '/' + result.indieground.id);
            }
        });
    }

    if(window.location.href === '{{ route('timeslots.index') }}') {
        loadTimeslots();
    }

    if(window.location.href === '{{ route('featured.index') }}') {
        loadFeaturedIndie();

        $(document).on('click', '#update-featured-artist, #delete-featured-artist', function(){
            let indie_artist_id = $(this).attr('data-id');

            getAsync('{{ route('featured.index') }}' + '/' + indie_artist_id, null, 'JSON', beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                $('#update-featured-indie-header, #update-content, #delete-featured-indie-header, #delete-featured-indie-body').empty();
                $('#update-featured-indie-header, #delete-featured-indie-header').append(result.indie.artist.name);
                $('#delete-featured-indie-body').append('<p class="h5 text-center">Are you sure to delete '+result.indie.artist.name+' from featured indiegrounds?</p>')
                $('#update_featured_artist_id').val(result.indie.id);
                tinymce.get('update-content').setContent('<p>'+result.content+'</p>');
                let content = tinymce.get('update-content').getContent();

                if(!content || content === null) {
                    $('button[type="submit"]').attr('disabled', 'disabled');
                } else {

                }

                getAsync('{{ url('featured') }}' + '/' + indie_artist_id, { "albums": 1, }, 'HTML', beforeSend, onSuccess);

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

    if(window.location.href === '{{ route('user_logs.index') }}') {
        getAsync('{{ route('user_logs.index') }}', { "process": 'load' }, "HTML", beforeSend, onSuccess);

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
                    { data: 'created_at' },
                    { data: 'user.email' },
                    { data: 'action' },
                    { data: 'employee.employee_number' },
                ],
                order: [
                    0, 'desc',
                ]
            });
        }
    }

    if(window.location.href === '{{ route('archives.index') }}') {
        getAsync('{{ route('archives.index') }}', { "process": 'load' }, "HTML", beforeSend, onSuccess);

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
                    { data: 'created_at' },
                    { data: 'user.email' },
                    { data: 'action' },
                    { data: 'employee.employee_number' },
                ],
                order: [
                    0, 'desc',
                ]
            });
        }
    }

    if(window.location.href === '{{ route('outbreaks.index') }}') {
        loadOutbreaks();
    }

    if(window.location.href === '{{ route('southsides.index') }}') {
        loadLocalDates();
        loadLocalCharts();
    }
    /* End */

    /* Interaction Scripts, onClick, onChange, keyPress functions */
    $(document).on('keyup', '#email, #password, #password_confirmation, #employee_number, #current_password', function() {
        if(!$(this).val()) {
            $(this).hasClass('is-valid');
            $(this).removeClass('is-invalid');
            $(this).addClass('is-invalid');
        } else {
            $(this).hasClass('is-invalid');
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
        }
    });
    $(document).on('keypress', 'form', function (e){
        if(e.keyCode === 13) {
            $(this).submit();
        }
    });

    $(document).on('click', '#logout, #logoutJock', function(event){
        event.preventDefault();

        $('#logout-form').submit();
        $('#logout-form').on('submit', function(event){
            event.preventDefault();

            let url = $(this).attr('href');
            let formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                cache: false,
                success: (response) => {
                    // must remain empty okay, it just logs out the whole thing.
                },
                error: (errors) => {
                    // must remain empty okay, it just logs out the whole thing.
                }
            })
        });
    });
    $(document).on('click', '#view-jock-modal, #delete-jock-modal', function() {
        let jock_id = $(this).attr('data-id');
        let url = $(this).attr('data-route');
        let jock_modal = $(this);

        getAsync(url, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Loading Jock Info ...',
            });
        }

        function onSuccess(result) {
            $('#update_jock_form, #delete_jock_form').attr('action', url);
            $('#view_jock_modal_title, #delete_jock_modal_title').empty().append(result.name);

            if(jock_modal[0].hash.includes('delete')) {
                manualToast.close();
                $('#delete_jock_modal_body_text').empty().append('Are you sure to delete <b>'+result.name+'</b> from the Jocks? The action is irreversible.');
            } else {
                $('#update_profile_image, #update_background_image, #update_main_image').attr('src', '');

                $('#update_main_image').attr('src', result.main_image);
                $('#update_profile_image').attr('src', result.profile_image);
                $('#update_background_image').attr('src', result.background_image);

                $('#update_employee_id').val(result.employee.id);
                $('#update_designation_id').val(result.employee.designation_id);

                $('#update_employee_name').val(result.name);
                $('#update_designation').val(result.designation_name);

                $('#update_name').val(result.name);
                $('#update_moniker').val(result.moniker);
                $('#update_is_active').val(result.is_active);

                $('#new_link_jock_id, #new_image_jock_id, #new_fact_jock_id').val(result.id);

                tinymce.get('update-content').setContent('<p>'+result.description+'</p>');
                let content = tinymce.get('update-content').getContent();

                $('#jockImagesTable').DataTable({
                    ajax: {
                        url: url,
                        dataSrc: 'images',
                        data: {
                            jock_info: "jock_info"
                        },
                    },
                    columns: [
                        { data: 'created_at' },
                        { data: 'name' },
                        { data: 'options' }
                    ],
                    order: [
                        [ 0, 'desc' ]
                    ]
                });

                $('#jockLinksTable').DataTable({
                    ajax: {
                        url: url,
                        dataSrc: 'links',
                        data: {
                            jock_info: "jock_info"
                        },
                    },
                    columns: [
                        { data: 'website' },
                        { data: 'url' },
                        { data: 'options' ,
                        }
                    ],
                    order: [
                        [ 0, 'desc' ]
                    ],
                });

                $('#jockFactsTable').DataTable({
                    ajax: {
                        url: url,
                        dataSrc: 'facts',
                        data: {
                            jock_info: "jock_info"
                        },
                    },
                    columns: [
                        { data: 'content' },
                        { data: 'options' ,
                        }
                    ],
                    order: [
                        [ 0, 'desc' ]
                    ],
                });

                $('#view_jock_modal').on('hidden.bs.modal', function() {
                    $('#jockImagesTable').DataTable().clear().destroy();
                    $('#jockLinksTable').DataTable().clear().destroy();
                    $('#jockFactsTable').DataTable().clear().destroy();
                });

                $("#update-profile, #update-header, #delete-jock-fact-modal, #view-jock-fact-modal, #add-jock-fact-modal, #delete-jock-link-modal, #view-jock-link-modal, #add-jock-link-modal, #delete-jock-image-modal, #view-jock-image-modal, #add-jock-image-modal").on('hidden.bs.modal', function() {
                    jock_modal.click();
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Jock information loaded!'
                });
            }
        }
    });
    $(document).on('click', '#post, #official, #draft', function(event){
        event.preventDefault();

        let action = $(this).attr('id');
        let payload = $(this).attr('data-payload');
        let type = $(this).attr('data-local');

        console.log([action, payload, type]);
        if(action === 'post') {
            getAsync('{{ route('charts.index') }}', { "action": action, "dated": payload, "data-local": type }, 'JSON', onSend, onSuccess)

            function onSend() {
                manualToast.fire({
                    icon: 'info',
                    title: 'Posting charts ...',
                });
            }

            function onSuccess(result) {
                Toast.fire({
                    icon: result.status,
                    title: result.message
                });

                $('button#official').click();
            }
        }

        if (action === 'official') {
            $('#chart-subtitle').empty();
            $('#chart-subtitle').append(action.charAt(0).toUpperCase() + action.slice(1) + ' Charts')
            $('#chartDates').attr('data-chart-type', action);

            getAsync('{{ route('charts.index') }}', { "action": action, "date": payload, "data-local": type }, "HTML", onSend, onSuccess);

            function onSend() {
                $('#monsterCharts').empty();
                $('#monsterCharts').append("<div class='text-center'><div class='spinner-border text-dark' role='status'><span class='sr-only'>Loading...</span></div></div>");
                manualToast.fire({
                    icon: 'info',
                    title: 'Loading '+action+' charts ...',
                });
            }

            function onSuccess(result) {
                Toast.fire({
                    icon: 'success',
                    title: action.charAt(0).toUpperCase() + action.slice(1) + ' charts has been loaded'
                });

                $('#monsterCharts').empty();
                $('#monsterCharts').append(result);
            }
        }

        if (action === 'draft') {
            $('#chart-subtitle').empty();
            $('#chart-subtitle').append(action.charAt(0).toUpperCase() + action.slice(1) + ' Charts');
            $('#chartDates').attr('data-chart-type', action);

            getAsync('{{ route('charts.index') }}', { "action": action, "date": payload, "data-local": type }, "HTML", onSend, onSuccess);

            function onSend() {
                $('#monsterCharts').empty();
                $('#monsterCharts').append("<div class='text-center'><div class='spinner-border text-dark' role='status'><span class='sr-only'>Loading...</span></div></div>");
                manualToast.fire({
                    icon: 'info',
                    title: 'Loading '+action+' charts ...',
                });
            }

            function onSuccess(result) {
                Toast.fire({
                    icon: 'success',
                    title: action.charAt(0).toUpperCase() + action.slice(1) + ' charts has been loaded'
                });

                $('#monsterCharts').empty();
                $('#monsterCharts').append(result);
            }
        } else {

        }
    })
    $(document).on('click', '#timeslot-days li a', function(event){
        event.preventDefault();
        let day = $(this).text();
        let schedule_type = $(this).attr('type');

        getAsync('{{ route('timeslots.select') }}', { "day": day, "type": schedule_type }, "HTML", beforeSend, onSuccess)

        function beforeSend() {
            $(this).attr('disabled', 'disabled');
            manualToast.fire({
                icon: 'info',
                title: day +' timeslots loading'
            });
        }

        function onSuccess(result) {
            $(this).removeAttr('disabled');
            $('#timeslot-subtitle').empty();
            $('#timeslot-subtitle').append(day +' '+ schedule_type +' timeslots');
            $('#switch_timeslots').attr('day', day);

            Toast.fire({
                icon: 'success',
                title: day + ' '+ schedule_type +' timeslots has been loaded'
            });

            $('#tab-container').empty();
            $('#tab-container').append(result);
        }
    });
    $(document).on('click', '#edit-timeslot-toggler, #delete-timeslot-toggler', function(){
        let timeslot_id = $(this).attr('data-id');
        let timeslot_type = $(this).attr('type');

        getAsync('{{ route('timeslots.index') }}' + '/' + timeslot_id, { 'type': timeslot_type }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            console.log(timeslot_type);
        }

        function onSuccess(result) {
            $('bs.modal').modal('hide');
            $('#update_start, #update_end, #timeslot-modal-title, #delete-timeslot-header, #delete-timeslot-body').empty();

            $('#update_schedule_type').attr('disabled', 'disabled');

            if(timeslot_type === 'jock') {
                $('#update_shows').attr('hidden', 'hidden');
                $('#update_show_id').attr('disabled', 'disabled');
                $('#update_schedule_type').val(timeslot_type);
                $('#update_jocks').removeAttr('hidden').attr('required', 'required');
                $('#update_jock_id').val(result.jock.id);
                $('#timeslot-modal-title').append(result.jock.name + '\'s Timeslot');
                $('#delete-timeslot-header').append('Delete '+result.jock.name);
                $('#delete-timeslot-body').append('Are you sure to delete '+result.jock.name+'\'s timeslot?');
            } else {
                $('#update_jocks').attr('hidden', 'hidden');
                $('#update_jock_id').attr('disabled', 'disabled');
                $('#update_schedule_type').val(timeslot_type);
                $('#update_shows').removeAttr('hidden').attr('required', 'required');
                $('#update_show_id').val(result.show.id);
                $('#timeslot-modal-title').append(result.show.title + ' Timeslot');
                $('#delete-timeslot-header').append('Delete '+result.show.title);
                $('#delete-timeslot-body').append('Are you sure to delete '+result.show.title+' timeslot?');
            }

            $('#update_day').val(result.day);
            $('#update_start').val(result.start);
            $('#update_end').val(result.end);
            $('#update-timeslot-form, #delete-timeslot-form').attr('action', '{{ route('timeslots.index') }}' + '/' + timeslot_id);
        }
    });
    $(document).on('click', '#update-award-toggler, #delete-award-toggler', function(){
        let award_id = $(this).attr('data-id');

        getAsync('{{ route('awards.index') }}' + '/' + award_id, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Getting award info ...',
            });

            $('button[type="submit"]').attr('disabled', 'disabled');
            $('button[data-dismiss="modal"]').attr('disabled', 'disabled');
        }

        function onSuccess(result) {
            $('#update-awardee, #update-title, #update-year, #update-description, #delete-award-body').val('');
            $('#update_award').val(result.name);

            if(result.jock_id !== null) {
                $('#update-awardee-type').val("jock");
                getAsync('{{ route('awards.index') }}', { "awardee": 'jock' }, 'JSON', beforeSend, onSuccess);

                function beforeSend() {}

                function onSuccess(response) {
                    $('#update-awardee').append(response);
                    $('#update-awardee').val(result.jock_id);
                }
            } else {
                $('#update-awardee-type').val("show");
                getAsync('{{ route('awards.index') }}', { "awardee": 'show' }, 'JSON', beforeSend, onSuccess);

                function beforeSend() {}

                function onSuccess(response) {
                    $('#update-awardee').append(response);
                    $('#update-awardee').val(result.show_id);
                }
            }

            $('#update-title').val(result.title);
            $('#update-year').val(result.year);
            $('#update-special').val(result.is_special);
            $('#update-description').val(result.description);

            $('#delete-award-body').append('Are you sure to delete '+result.title+'?');

            $('#updateAwardForm, #deleteAwardForm').attr('action', '{{ route('awards.index') }}' + '/' + result.id);

            Toast.fire({
                'icon': 'success',
                'title': 'Award has been loaded.'
            });
            $('button[type="submit"]').removeAttr('disabled');
            $('button[data-dismiss="modal"]').removeAttr('disabled');
        }
    });
    $(document).on('click', '#update-genre-toggler, #delete-genre-toggler', function(){
        let genre_id = $(this).attr('data-id');

        getAsync('{{ route('genres.index') }}' + '/' + genre_id, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            Toast.fire({
                icon: 'info',
                title: 'Getting genre info ...',
            });
        }

        function onSuccess(result) {
            $('#update-name, #update-description, #delete-genre-body').empty();
            $('#update-name').val(result.name);

            if(!result.GenreDescription) {
                $('#update-description').attr('placeholder', 'No Available Description');
            } else {
                $('#update-description').val(result.GenreDescription);
            }

            $('#delete-genre-body').append('Are you sure to delete '+result.name+'? Some data may be affected');

            Toast.fire({
                icon: 'success',
                title: 'Genre loaded'
            });

            $('#updateGenreForm, #deleteGenreForm').attr('action', '{{ route('genres.index') }}' + '/' + result.id);
        }
    });
    $(document).on('click', '#update-artist-modal, #delete-artist-modal', function(){
        let artist_id = $(this).attr('data-id');

        getAsync('{{ route('artists.index') }}' + '/' + artist_id, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#update_artist_name, #delete-artist-body, #view_albums').empty();

            $.each(result.album, function(index, album) {
                $('#view_albums').val('');
                $('#view_albums').append('<option value='+album.id+'>'+album.name+'</option>');
            });

            $('#artistImage').attr('src', '/images/artists/'+result.image);
            $('#update_artist_id').val(result.id);
            $('#update_artist_name').val(result.name);
            $('#update_artist_country').val(result.country);
            $('#update_artist_type').val(result.type);

            $('#delete-artist-body').append('Are you sure to delete '+result.name+'? Deleting may affect other data.');

            let album_id = $('#view_albums').val();

            if(!album_id) {
                $('#view_album_image').removeAttr('src');
                $('#view_album_image').removeAttr('alt');
                $('#view_songs').empty();
            } else {
                getAsync('{{ route('albums.index') }}' + '/' + album_id, null, 'JSON', beforeSend, onSuccess);

                function beforeSend() {

                }

                function onSuccess(result) {
                    $('#view_songs').empty();
                    $('#view_album_image').attr('src', result.image);
                    $('#view_album_image').attr('alt', result.name);

                    $.each(result.song, function(index, song){
                        $('#view_songs').append('' +
                            '<tr>' +
                            '   <td>'+song.name+'</td>' +
                            '   <td>'+result.genre.name+'</td>' +
                            '</tr>');
                    });

                    Toast.fire({
                        icon: 'success',
                        title: "Album has been loaded"
                    });
                }
            }

            $('#updateArtistForm, #deleteArtistForm').attr('action', '{{ route('artists.index') }}' + '/' + result.id);
        }
    });
    $(document).on('click', '#update-album-modal, #delete-album-modal', function(){
        let album_id = $(this).attr('data-id');

        getAsync('{{ route('albums.index') }}' + '/' + album_id, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#update_album_image, #update_album_name, #update_album_year, #delete-album-body').empty();

            $('#update_album_image').attr('src', result.image);
            $('#update_album_name').val(result.name);
            $('#update_artist_id').val(result.artist.id);
            $('#update_genre_id').val(result.genre.id);
            $('#update_album_year').val(result.year);
            $('#update_album_type').val(result.type);

            $('#delete-album-body').append('Are you sure to delete '+result.name+'? Deleting may affect other data.');

            $('#view_songs, #album_view_songs').empty();

            $.each(result.song, function(index, song){
                $('#view_songs, #album_view_songs').append('' +
                    '<tr>' +
                    '   <td>'+song.name+'</td>' +
                    '   <td>'+result.genre.name+'</td>' +
                    '</tr>');
            });

            $('#updateAlbumForm, #deleteAlbumForm').attr('action', '{{ route('albums.index') }}' + '/' + result.id);
        }
    });
    $(document).on('click', '#update-song-modal, #delete-song-modal', function(){
        let song_id = $(this).attr('data-id');

        getAsync('{{ route('songs.index') }}' + '/' + song_id, null, "JSON", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#songDemoContainer').empty();

            if(result.type === "mp3/m4a") {
                $('#songDemoContainer').append('<div class="text-center"><audio controls controlsList="nodownload"><source src="{{ asset('audios') }}'+ '/' + result.track_link +'" type="audio/mpeg">Your browser does not support audio element</audio></div>');
                $('#update_spotify').attr('hidden', 'hidden');
                $('#update_spotify_link').attr('disabled', 'disabled');
                $('#update_file').removeAttr('hidden');
                $('#update_song_file').removeAttr('disabled');
                $('#update_song_file_old').removeAttr('disabled');
                $('#update_song_file_old').val(result.track_link);
            } else {
                if(result.track_link) {
                    $('#songDemoContainer').append('<div class="embed-container"><iframe src="https://open.spotify.com/embed/track/'+result.track_link+'" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe></div>');
                    $('#update_file').attr('hidden', 'hidden');
                    $('#update_song_file').attr('disabled', 'disabled');
                    $('#update_song_file_old').attr('disabled', 'disabled');
                    $('#update_spotify').removeAttr('hidden');
                    $('#update_spotify_link').removeAttr('disabled');
                    $('#update_spotify_link').val(result.track_link);
                } else {
                    $('#songDemoContainer').append('<div class="alert alert-warning text-center lead" role="alert">No Track Link is Present</div>');
                }
            }

            $('#update_song_name').val(result.name);
            $('#update_song_artist_id').val(result.album.artist.id);
            $('#update_album_id').val(result.album.id);
            $('#update_album_name').val(result.album.name);
            $('#update_song_type').val(result.type);

            $('#delete-song-body').empty().append('Are you sure to delete '+result.name+' by '+result.album.artist.name+'?');

            $('#updateSongForm, #deleteSongForm').attr('action', '{{ route('songs.index') }}' + '/' + result.id);
        }
    });
    $(document).on('click', '#update-podcast-modal, #delete-podcast-modal', function() {
        let podcast_id = $(this).attr('data-id');

        getAsync('{{ url('podcasts') }}' + '/' + podcast_id, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Loading Podcast ...'
            });
        }

        function onSuccess(result) {
            manualToast.close();

            $('button[type="submit"]').attr('disabled', 'disabled');
            $('#update_episode, #update_date, #update_link').val('');

            $('#update_podcast_title_header').empty().append(result.podcast.episode);

            $('#update_episode').val(result.podcast.episode);
            $('#update_date').val(result.podcast.date);
            $('#update_link').val(result.podcast.link);
            $('#update_show_id').val(result.podcast.show_id);
            $('#update_podcast_image').attr('src', '/images/podcasts/' + result.podcast.image).attr('alt', result.podcast.episode);
            $('#update_podcast_audio').attr('src', result.podcast.link)

            $('#delete_podcast_form_text').empty().append('<div class="lead">Are you sure to delete a podcast from <b>'+result.podcast.show.title+'</b> called <b>'+result.podcast.episode+'</b>?</div>');

            $('#update_podcast_form').attr('action', '{{ url('podcasts') }}' + '/' + result.podcast.id);
            $('#delete_podcast_form').attr('action', '{{ url('podcasts') }}' + '/' + result.podcast.id);
            $('button[type="submit"]').removeAttr('disabled');
        }
    });
    $(document).on('click', '#update-outbreak-modal, #delete-outbreak-modal', function(){
        let outbreak_id = $(this).attr('data-id');

        getAsync('{{ route('outbreaks.index') }}' + '/' + outbreak_id, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            console.log(result);
            // for outbreaks
            $('#delete-outbreak-song-body').empty();
            $('#update_outbreak_song_id').val(result.song_id);
            $('#update_dated').val(result.dated);

            $('#updateOutbreakSongForm, #deleteOutbreakSongForm').attr('action', '{{ route('outbreaks.index') }}' + '/' + result.id);

            $('#delete-outbreak-song-body').append('Are you sure to delete '+result.song.name+' by '+result.song.album.artist.name+'?');
        }
    });
    $(document).on('click', '#openStudentModal', function(event) {
        event.preventDefault();

        let student_id = $(this).attr('student-id');
        let url = '{{ route('students.index') }}' + '/' + student_id;

        getAsync(url, null, 'HTML', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#student_modal_body').empty().append(result);

            tinymce.remove('#update-content');

            tinymce.init({
                selector: '#update-content',
                theme: "modern",
                height: 200,
                width: '100%',
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                    "table contextmenu directionality emoticons paste textcolor responsivefilemanager code advlist instagram twitter_url"
                ],
                toolbar1: "undo redo | bold italic underline fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview code | caption instagram twitter_url",
                image_caption: true,
                image_advtab: true,

                external_filemanager_path:window.location.protocol + "//" + window.location.host + "/tinymce/filemanager/",
                filemanager_title: "File Manager",
                external_plugins: {"filemanager":window.location.protocol + "//" + window.location.host + "/tinymce/filemanager/plugin.min.js"},
                visualblocks_default_state: true,

                style_formats_autohide: true,
                style_formats_merge: true,

                plugin_preview_width: 1000,
                plugin_preview_height: 800,

                valid_elements : '+*[*]',

                extended_valid_elements : "iframe[width|height|name|align|class|frameborder|allowfullscreen|allow|src|*]," +
                    "script[language|type|async|src|charset]" +
                    "img[*]" +
                    "embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|allowscriptaccess|type|pluginspage]" +
                    "blockquote[dir|style|cite|class|id|lang|onclick|ondblclick"
                    +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
                    +"|onmouseover|onmouseup|title]",
                setup: function (editor) {
                    //console.log(editor);
                    editor.on('init', function (args) {
                        editor_id = args.target.id;
                    });
                },
            });
        }
    });
    $(document).on('click', '#update-employee-modal, #delete-employee-modal', function() {
        let url = $(this).attr('data-route');
        let update_url = $(this).attr('data-update-route');
        let delete_url = $(this).attr('data-delete-route');

        getAsync(url, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Loading employee ...'
            })
        }

        function onSuccess(result) {
            manualToast.close();

            $('#update_first_name, #update_last_name, #update_address, #update_contact_number').val('');

            $('#update_first_name').val(result.first_name);
            $('#update_last_name').val(result.last_name);
            $('#update_address').val(result.address);
            $('#update_contact_number').val(result.contact_number);
            $('#update_gender').val(result.gender);
            $('#update_birthday').val(result.birthday);
            $('#update_designation_id').val(result.designation_id);

            $('#update_employee_form').attr('action', update_url);
            $('#delete_employee_form').attr('action', delete_url);

            $('#delete_employee_form_body').empty().append('<div class="lead text-center">Are you sure to delete '+result.first_name+' '+result.last_name+' from the Database? Data related to this person would also be deleted.</div>');
        }
    })
    $(document).on('click', 'a[add_student]', function() {
        let url = '{{ route('radioOne.jocks') }}';
        let student_jock_id = $(this).attr('student_jock_id');

        getAsync(url, { "student_jock_id": student_jock_id }, 'HTML', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#edit-student-jock-body, #edit-student-jock-title').empty();

            $('#edit-student-jock-title').append('Edit Student Jock');
            $('#edit-student-jock-body').append(result);
        }
    });
    $(document).on('click', 'a[remove_student]', function() {
        let url = '{{ route('radioOne.jocks') }}';
        let student_jock_id = $(this).attr('student_jock_id');

        getAsync(url, { "student_jock_id": student_jock_id, "remove": true }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#remove-student-jock-title, #remove-student-jock-body').empty();

            $('#remove-student-jock-form').attr('action', '{{ url('radio_one/remove/student_jock/') }}' + '/' + result.batch[0].id);
            $('#remove_student_jock_id').val(result.id);

            $('#remove-student-jock-title').append('Confirm');
            $('#remove-student-jock-body').append('Are you sure to remove <strong>'+result.nickname+'</strong> from this batch? Action is reversible');
        }
    });
    $(document).on('click', 'a[data-open]', function() {
        let url = $(this).attr('data-link');
        let data_id = $(this).attr('data-id');

        getAsync(url, { "id": data_id }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            if(result.image) {
                $('#view_image, #name, #file').val('');
                $('#jock-remove-image-text, #custom-file-label').empty();

                $('[name="jock_id"]').val(result.image.jock.id);

                $('#view_image').attr('src', '{{ url('images/jocks') }}' + '/' + result.image.file);
                $('#view_image').attr('alt', result.image.name);

                $('#name').val(result.image.name); cdess
                $('#custom-file-label').append(result.image.file);
                $('#jock-remove-image-text').append('Are you sure to delete <b>' + result.image.file + '</b>?');

                $('#update_jock_image_form').attr('action', '{{ url('photos/update') }}' + '/' + result.image.id);
                $('#delete_jock_image_form').attr('action', '{{ url('photos/delete') }}' + '/' + result.image.id);
            } else if(result.fact) {
                $('#jock-remove-fact-text').empty();

                $('[name="jock_id"]').val(result.fact.jock.id);

                $('#update_facts').val(result.fact.content);
                $('#update_jock_fact_form').attr('action', '{{ url('facts') }}' + '/' + result.fact.id);
                $('#remove_jock_fact_form').attr('action', '{{ url('facts') }}' + '/' + result.fact.id);
            } else if(result.link) {
                $('#jock-remove-link-text').empty();

                $('[name="jock_id"]').val(result.link.jock.id);

                $('#update_website').val(result.link.website);
                $('#update_track_link').val(result.link.track_link);
                $('#jock-remove-link-text').append('Are you sure to delete the jock\'s <b>'+result.link.website+'</b> link?');

                $('#update_jock_link_form').attr('action', '{{ url('socials/update') }}' + '/' + result.link.id);
                $('#delete_jock_link_form').attr('action', '{{ url('socials/delete') }}' + '/' + result.link.id);
            }
        }
    });
    $(document).on('click', '#add-artist-image, #update-artist-image-button', function() {
        sessionStorage.setItem('artist_id', $('#update_artist_id').val() || $('#new_indie_artist_id').val() || $('#indie_artist_id').val());
        sessionStorage.setItem('artist_name', $('#artist_name').val() || $('#update_artist_name').val());
        sessionStorage.setItem('artist_country', $('#country').val() || $('#update_artist_country').val());
        sessionStorage.setItem('artist_type', $('#artist_type').val() || $('#update_artist_type').val());
        sessionStorage.setItem('active_modal', '#'+$('.modal:visible').attr('id'));

        active_modal = sessionStorage.getItem('active_modal');

        if(active_modal.includes('indie-artist-modal') || active_modal.includes('new-indie') || active_modal.includes('new-featured') || active_modal.includes('update-featured-indie')) {
            sessionStorage.setItem('indieground_content', tinymce.get('update-content').getContent());
        }

        $('#update-artist-image').modal('show');
    });
    $(document).on('click', '#artistCropButton', function () {
        $imageToBeCropped.croppie('result', {
            type: 'canvas',
            size: { width: 500, height: 500 },
            quality: 1
        }).then(function (response) {
            artist_id = sessionStorage.getItem('artist_id');

            $.ajax({
                url: '{{ route('artists.add.image') }}',
                method: 'POST',
                data: {
                    "image": response,
                    "artist_id": artist_id
                },
                beforeSend: () => {
                    $('#artistCropButton').attr('disabled', 'disabled');
                    Toast.fire({
                        icon: 'info',
                        title: 'Uploading artist picture please wait',
                    });
                },
                success: function(data) {
                    $('#custom').removeAttr('hidden');
                    $('#artistCropButton').removeAttr('disabled').attr('hidden', 'hidden');
                    $('#cancelButton').attr('hidden', 'hidden');
                    $('#doneButton').removeAttr('hidden');
                    $('.cropper').removeClass('ready');
                    $('#croppingImage').croppie('destroy');
                    $('#image').val('');

                    sessionStorage.setItem('image', data);

                    $('#new-artist, #update-artist, #new-indie, #indie-artist-modal').modal('show');

                    $('#name, #update_artist_name').val(sessionStorage.getItem('artist_name'));
                    $('#country, #update_artist_country').val(sessionStorage.getItem('artist_country'));
                    $('#type, #update_artist_type').val(sessionStorage.getItem('artist_type'));
                    $('#ArtistImage, #update_artist_image').val(sessionStorage.getItem('image'));
                    $('#artistImage').attr('src', '{{ asset('images/artists') }}' + '/' + sessionStorage.getItem('image'));
                    $('#image-container').empty().append('<img src="{{ asset('images/indie') }}/'+sessionStorage.getItem('image')+'" width="200px">');

                    active_modal = sessionStorage.getItem('active_modal');

                    if(active_modal.includes('indie-artist-modal') || active_modal.includes('new-indie') || active_modal.includes('new-featured') || active_modal.includes('update-featured-indie')) {
                        tinymce.get('update-content').setContent(sessionStorage.getItem('indieground_content'));
                    }

                    Toast.fire({
                        icon: 'success',
                        title: 'Image cropped and uploaded!',
                    });
                },
                error: (xhr) => {
                    manualToast.fire({
                        icon: 'error',
                        title: xhr.status + ' ' + xhr.statusText,
                    });
                }
            });
        });
    });
    // Album Addition in Songs
    $(document).on('click', 'a[href="#new-album"]', () => {
        $(':input').val('');
        $('#new-song').modal('hide');
    });
    // Artist Addition in Songs
    $(document).on('click', 'a[href="#new-artist"]', () => {
        $(':input').val('');
        $('#new-album').modal('hide');
    });
    $(document).on('click', "#deleteTimeslotButton", function(event){
        event.preventDefault();

        $('#deleteTimeslotForm').submit();
    });
    // How the buttons work in Articles
    $(document).on('click', '#yearlyArticles', function(event) {
        event.preventDefault();

        loadYearlyArticles();

        $('#switch_pub').removeAttr('hidden');
        $('#switch_unpub').attr('hidden', 'hidden');

        Toast.fire({
            icon: 'success',
            title: 'Yearly Articles has been successfully loaded'
        });
    });
    $(document).on('click', '#archives', function (event) {
        event.preventDefault();

        $('#switch_pub').removeAttr('hidden');
        $('#switch_unpub').attr('hidden', 'hidden');

        loadArchivedArticles();

        Toast.fire({
            icon: 'success',
            title: 'Archived Articles has been successfully loaded'
        });
    });
    $(document).on('click', "#switch_pub", function () {
        $("#switch_pub").attr('hidden', 'hidden');
        $("#switch_unpub").removeAttr('hidden');
    });
    $(document).on('click', "#switch_unpub", function () {
        $("#switch_unpub").attr('hidden', 'hidden');
        $("#switch_pub").removeAttr('hidden');
    });
    $(document).on('click', 'button[type="crop"]', function(){
        $imageToBeCropped.croppie('result', {
            type: 'canvas',
            size: { width: 800, height: 800 },
            quality: 1
        }).then((response) => {
            $.ajax({
                url: '{{ route('articles.store') }}',
                type: 'POST',
                data: { "croppedImage" : response },
                beforeSend: () => {
                    $('button[type="crop"]').attr('disabled', 'disabled');
                    manualToast.fire({
                        icon: 'info',
                        title: 'Uploading article picture please wait',
                    });
                },
                success: (data) => {
                    $('#cropButton').attr('hidden', 'hidden');
                    $('#croppedArticleImage').val(data);

                    setTimeout(() => {
                        $('#saveButton').click();
                    }, 2000);
                },
                error: (error) => {
                    console.log(error);
                    manualToast.fire({
                        icon: 'error',
                        title: error.status + ' ' + error.statusText
                    });
                }
            });
        })
    });

    // Song Voting
    $(document).on('click', '#voteButton', function(e) {
        e.preventDefault();
        let device = $(this).attr('data-device');
        let chart_id = $(this).attr('data-id');

        getAsync('{{ route('vote.song') }}', { "device": device, "chart_id": chart_id }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            $('button[type="button"]').attr('disabled', 'disabled');
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...'
            });
        }

        function onSuccess(result) {
            $('button[type="button"]').removeAttr('disabled');
            Toast.fire({
                icon: result.status,
                title: result.message
            });
            loadVotingCharts();
            votesTable.ajax.reload(null, false);
        }
    });
    // Open reporting
    $(document).on('click', '#openReport', function(e){
        e.preventDefault();
        let report_id = $(this).attr('data-report-id');

        getAsync('{{ route('reports') }}', { "report_id": report_id }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#report-title-header').empty();
            $('#report-title-header').append('Report: '+result.bugTitle);

            $('#report-title').empty();
            $('#report-title').append('<div class="lead h5">Bug Title: <br><strong>'+result.bugTitle+'</strong></div>');

            $('#report-description').empty();
            $('#report-description').append('<div class="lead text-justify">Bug Description: <br><strong>'+result.bugDescription+'</strong></div>');

            $('#report-image').empty();
            $('#report-image').append('<div class="lead">Attachment:<br><center><img src="'+result.image+'" alt="'+result.bugTitle+'" width="450px"></img></center></div>');
        }
    });
    $(document).on('click', '#switch_unpub, #switch_pub', function() {
        let article = $(this).attr('data-article');

        getAsync('{{ route('articles.index') }}', { "status": article }, "HTML", beforeSend, onSuccess);

        function beforeSend() {
            // not necessary
        }

        function onSuccess(result) {
            $('#articleStatus').empty();
            $('#articleStatus').append(result);

            Toast.fire({
                icon: 'success',
                title: article.charAt(0).toUpperCase() + article.slice(1) + ' articles has been loaded'
            });
        }
    })
    // Pagination in Articles
    $(document).on('click', 'button[type="next"], button[type="previous"]', function(event){
        let page = $(this).attr('data-href');
        let article = $(this).attr('data-article');

        getAsync(page, { "status": article }, "html", beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Loading Articles'
            });
        }

        function onSuccess(result) {
            $('#articleStatus').empty();
            $('#articleStatus').append(result);

            manualToast.close();
        }
    });

    // for changing the timeslot type from Show to Jock
    $(document).on('click', '#switch_timeslots', function() {
        let view = $(this).attr('switch');
        let day = $(this).attr('day');

        if(view === 'show') {
            $(this).attr('switch', 'jock');
            $('#timeslot-days li a').attr('type', 'show');
            $('#timeslot-subtitle').empty().append(day +' '+ view +' timeslots');
        } else {
            $(this).attr('switch', 'show');
            $('#timeslot-days li a').attr('type', 'jock');
            $('#timeslot-subtitle').empty().append(day +' '+ view +' timeslots');
        }

        getAsync('{{ route('timeslots.select') }}', { 'day': day, 'type': view }, "HTML", beforeSend, onSuccess);

        function beforeSend() {
            Toast.fire({
                icon: 'info',
                title: 'Loading '+view+' timeslots'
            });
        }

        function onSuccess(result) {
            $('#tab-container').empty().append(result);

            Toast.fire({
                icon: 'success',
                title: 'Successfully loaded '+view+' timeslots'
            });
        }
    });

    // for opening the podcast
    $(document).on('click', '#podcast_card', function() {
        let podcast_id = $(this).attr('data-id');

        getAsync('{{ url('podcasts') }}' + '/' + podcast_id, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Loading Podcast ...'
            });
        }

        function onSuccess(result) {
            manualToast.close();

            $('button[type="submit"]').attr('disabled', 'disabled');
            $('#update_episode, #update_date, #update_link').val('');

            $('#update_podcast_title_header').empty().append(result.podcast.episode);

            $('#update_episode').val(result.podcast.episode);
            $('#update_date').val(result.podcast.date);
            $('#update_link').val(result.podcast.link);
            $('#update_show_id').val(result.podcast.show_id);
            $('#update_podcast_image').attr('src', '/images/podcasts/' + result.podcast.image).attr('alt', result.podcast.episode);
            $('#update_podcast_audio').attr('src', result.podcast.link)

            $('#delete_podcast_form_text').empty().append('<div class="lead">Are you sure to delete a podcast from <b>'+result.podcast.show.title+'</b> called <b>'+result.podcast.episode+'</b>?</div>');

            $('#update_podcast_form').attr('action', '{{ url('podcasts') }}' + '/' + result.podcast.id);
            $('#delete_podcast_form').attr('action', '{{ url('podcasts') }}' + '/' + result.podcast.id);
            $('button[type="submit"]').removeAttr('disabled');
        }
    });

    // Show what schedule type, jock, or show.
    $(document).on('change', '#schedule_type', function() {
        let type = $(this).val();

        if(type === 'jock') {
            $('#jocks').removeAttr('hidden');
            $('#jock_id').attr('required', 'required');
            $('#shows').attr('hidden', 'hidden');
            $('#show_id').attr('disabled', 'disabled').removeAttr('required');
        } else {
            $('#shows').removeAttr('hidden');
            $('#show_id').attr('required', 'required');
            $('#jocks').attr('hidden', 'hidden');
            $('#jock_id').attr('disabled', 'disabled').removeAttr('required');
        }
    });

    $(document).on('change', '#image', function() {
        $imageToBeCropped = $('#imageCropper').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300,
                type: 'square'
            },
            boundary: {
                width: 500,
                height: 500
            }
        });

        readFile(this);
    });
    $(document).on('change', '#new_indie_artist_id', function() {
        let data_id = $(this).val();
        let url = '{{ url('/artists') }}' + '/' + data_id;

        if(!data_id) {

        } else {
            getAsync(url, null, "JSON", beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                if(result.image === "default.png" || result.image === null || result.image === '{{ asset('images/artists/default.png') }}') {
                    $('#indieground_image').removeAttr('hidden');
                    $('#indieground_save_button').attr('disabled', 'disabled');

                    Toast.fire({
                        icon: 'warning',
                        title: 'Artist has No Image!'
                    });
                } else {
                    $('#indieground_image').attr('hidden', 'hidden');
                    $('#indieground_save_button').removeAttr('disabled');

                    Toast.fire({
                        icon: 'success',
                        title: 'Artist has already an Image'
                    });
                }
            }
        }
    });
    $(document).on('change', '#position', function() {
        let position = $(this).val();
        let chart_input = $('#newEntryChartsDate');
        let chart_select = $('#newEntryChartDate');

        if(position == 1) {
            chart_input.removeAttr('hidden');
            chart_input.removeAttr('disabled');
            chart_select.attr('hidden', 'hidden');
            chart_select.attr('disabled', 'disabled');
        } else {
            chart_input.attr('hidden', 'hidden');
            chart_input.attr('disabled', 'disabled');
            chart_select.removeAttr('hidden');
            chart_select.removeAttr('disabled');
        }
    });
    $(document).on('change', '#outbreak_song_id', function() {
        let song_id = $(this).val();

        getAsync('{{ route('outbreaks.index') }}' + '/' + song_id, { "song_id": song_id }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            if(result === true) {
                $('#linkString').attr('hidden', 'hidden');
                $('#linkString').removeAttr('required');
                Toast.fire({
                    icon: 'info',
                    title: 'Song already has a demo',
                });
            }

            if(result === false) {
                $('#linkString').removeAttr('hidden');
                $('#linkString').attr('required', 'required');
            }
        }
    });
    $(document).on('change', '#song_type, #update_song_type', function() {
        let val = $(this).val();

        if(val == "spotify") {
            $('#spotify').removeAttr('hidden');
            $('#file').attr('hidden', 'hidden');
            $('#spotify_link').removeAttr('disabled');
            $('#song_file').attr('disabled', 'disabled');

            $('#update_spotify').removeAttr('hidden');
            $('#update_file').attr('hidden', 'hidden');
            $('#update_spotify_link').removeAttr('disabled');
            $('#update_song_file').attr('disabled', 'disabled');
            $('#update_song_file_old').attr('disabled', 'disabled');
        } else {
            $('#file').removeAttr('hidden');
            $('#spotify').attr('hidden', 'hidden');
            $('#song_file').removeAttr('disabled');
            $('#spotify_link').attr('disabled', 'disabled');

            $('#update_file').removeAttr('hidden');
            $('#update_spotify').attr('hidden', 'hidden');
            $('#update_song_file').removeAttr('disabled');
            $('#update_song_file_old').removeAttr('disabled');
            $('#update_spotify_link').attr('disabled', 'disabled');
        }
    });
    $(document).on('change', '#artist_image', function() {
        $('#custom').attr('hidden', 'hidden');
        $('#artistCropButton').removeAttr('hidden');
        $('#cancelButton').removeAttr('hidden');
        $('#doneButton').attr('hidden', 'hidden');

        $imageToBeCropped = $('#croppingImage').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300,
                type: 'square'
            },
            boundary: {
                width: 500,
                height: 500
            }
        });

        readFile(this);
    });
    $(document).on('change', '#surveyDate', function(e){
        e.preventDefault();

        let date = $(this).find(':selected').val();
        let daily = $(this).attr('data-chart');

        getAsync('{{ route('charts.daily') }}', { "date": date, "daily": daily }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            $('#dailyCharts').empty();
            manualToast.fire({
                icon: 'info',
                title: 'Loading Daily Charts'
            });
        }

        function onSuccess(result) {
            $('#dailyCharts').append(result);
            Toast.fire({
                icon: 'success',
                title: 'Daily Top 5 has been loaded',
            });
        }
    });
    $(document).on('change', '#view_albums', function(){
        let album_id = $(this).val();

        getAsync('{{ route('albums.index') }}' + '/' + album_id, null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: "Loading artist's album songs",
            });
        }

        function onSuccess(result) {
            Toast.fire({
                icon: 'success',
                title: "Album has been loaded"
            });

            $('#view_songs').empty();

            $('#view_album_image').attr('src', result.image);
            $('#view_album_image').attr('alt', result.name);

            $.each(result.song, function(index, songs){
                $('#view_songs').append('' +
                    '<tr>' +
                    '   <td>'+songs.name+'</td>' +
                    '   <td>'+result.genre.name+'</td>' +
                    '</tr>');
            });

            $(this).focus();
        }
    });
    // Song Addition in Songs
    $(document).on('change', '#song_artist_id, #update_song_artist_id', function() {
        let value = $(this).val();

        getAsync('{{ route('filter.artist.albums') }}', { "search": value }, "JSON", beforeSend, onSuccess);

        function beforeSend() {
            $('#song_artist_id, #update_song_artist_id').attr('disabled', 'disabled');
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...'
            });
        }

        function onSuccess(result) {
            $('#song_artist_id, #update_song_artist_id').removeAttr('disabled');
            $('#albumResults, #update_album_results').html(result);
            Toast.fire({
                icon: 'success',
                title: 'Albums has been loaded',
            });
            $('#song_artist_id, #update_song_artist_id').focus();
        }
    });
    // Change Dates in Charts
    $(document).on('change', '#chartDates', function(){
        let date = $(this).val();
        let chart_type = $(this).attr('data-chart-type');

        getAsync('{{ route('filter.chart') }}', { "date": date, "chart_type": chart_type }, 'HTML', beforeSend, onSuccess);

        function beforeSend() {
            $('#monsterCharts').empty();
            $('#monsterCharts').append('<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
            $('#loader').append('<div class="d-flex justify-content-center"><div class="spinner-border text-dark" role="status" style="width: 3rem; height: 3rem;"><span class="sr-only">Loading...</span></div></div>')
        }

        function onSuccess(result) {
            $('#loader').empty();
            $('#monsterCharts').empty();
            $('#monsterCharts').append(result);
            $('#post, #official, #draft').attr('data-payload', date);

            $('*[data-href]').on('click', function() {
                window.location = $(this).data("href");
            });
        }
    });
    // Select Awardee
    $(document).on('change', '#select_awardee', function(){
        let val = $(this).val();

        if (val) {
            getAsync('{{ route('awards.index') }}', { "awardee": val }, "JSON", beforeSend, onSuccess);

            function beforeSend() {
                $('#awardee').attr('hidden', 'hidden');
                manualToast.fire({
                    icon: 'info',
                    title: 'Sending request ...'
                });
            }

            function onSuccess(result) {
                Toast.fire({
                    icon: 'success',
                    title: val.charAt(0).toUpperCase() + val.slice(1) + 's has been loaded'
                });
                $('#awardee').removeAttr('hidden');
                $('#awardee').html(result);
            }
        }
    });

    // Music page functions
    $('#albumList, #updateAlbumList').on('click', 'tr', function(e){
        e.preventDefault();
        let album_id = $(this).attr('data-value');

        getAsync('{{ route('songs.select.album') }}', { "album_id": album_id }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#album_id, #update_album_id').empty();
            $('#name, #update_album_name').empty();

            $.each(result, function(index, albumObj){
                $('#album_id, #update_album_id').val(albumObj.id);
                $('#name, #update_album_name').val(albumObj.name);
                $('#Album').val(albumObj.name);
            });
        }
    });
    $('#songList').on('click', 'tr', function(e){
        e.preventDefault();
        let song_id = $(this).attr('value');

        getAsync('{{ route('charts.select.song') }}', { "song_id": song_id }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#song_id, #song_search, #name').empty();
            $('#song_id').val(result.id);
            $('#song_name').val(result.name);
        }
    });
    $('#songsList, #localSongsList').on('click', 'tr', function(e) {
        e.preventDefault();
        let song_id = $(this).children('td:first').text();

        getAsync('{{ route('charts.select.song') }}', { "song_id": song_id }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#song_id, #song_name, #new_song_id').empty();

            $('#song_id').val(result.id);
            $('#new_song_id').val(result.id);
            $('#song_name').val(result.name);
        }
    });
    /* End */

    /* Reloading Data Tables */
    setInterval(function () {
        votesTable.ajax.reload(null, false);
        tallyVotes.ajax.reload(null, false);
    }, 2500);

    setInterval(function() {
        songsTable.ajax.reload(null, false);
    }, 5000);
    /* End */

    /* Form Submission Functions */
    $(document).on('submit', 'form', function () {
        $(':button[type="submit"]').attr('disabled', 'disabled');
        $(':button[type="submit"]').html('Please Wait ...');
        $('input[name="_token"]').val('{{ csrf_token() }}');
    });
    $(document).on('submit', '#artistForm, #updateArtistForm', function (event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...'
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');
            Toast.fire({
                icon: result.status,
                title: result.message
            });
            artistsTable.ajax.reload(null, false);
            $('#artist_name, #country, #artist_type, #update_artist_name, #update_artist_country, #update_artist_type').val('');
            $('#new-artist, #update-artist').modal('hide');
            loadArtists();
        }
    });
    $(document).on('submit', '#albumForm, #updateAlbumForm', function (event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...'
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');
            Toast.fire({
                icon: result.status,
                title: result.message
            });
            albumsTable.ajax.reload(null, false);
            $('#new-album, #update-album').modal('hide');
            $('#album_name, #album_type, #year, #artist_id, #update_album_name, #update_album_type, #update_album_year, #update_artist_id').val('');
        }
    });
    $(document).on('submit', '#songForm', function (event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...'
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');
            songsTable.ajax.reload(null, false);
            songsList.ajax.reload(null, false);
            $('#new-song').modal('hide');
            $('#song_name, #spotify_link, #song_artist_id, #update_song_name, #update_spotify_link, #update_song_artist_id, #name, #album_id').val('');
            $('#albumResults').empty();
            Toast.fire({
                icon: result.status,
                title: result.message
            });
        }
    });
    $(document).on('submit', '#userUpdateForm', function (event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...'
            });
        }

        function onSuccess(result) {
            $('#userModal').modal('hide');
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');

            $('#name, #age, #gender, #contact_number, #Designation, #address, #DateCreated, #DateUpdated').empty();

            $('#name').html(result.employee.first_name + ' ' + result.employee.last_name);
            $('#Designation').html(result.employee.designation.name);
            $('#gender').html(result.employee.gender);
            $('#DateCreated').html(result.employee.created_at);
            $('#DateUpdated').html(result.employee.updated_at);

            $('#age').html(getAge(result.employee.birthday) ? getAge(result.employee.birthday) : "Well, you haven't given your birthday");
            $('#contact_number').html(result.employee.contact_number ? result.employee.contact_number : "Give us a contact number");
            $('#address').html(result.employee.address ? result.employee.address : "Tell us where you live");

            Toast.fire({
                icon: 'success',
                title: 'User profile has been updated'
            });

        }
    });
    $(document).on('submit', '#createCategoryForm', function (event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...'
            });
        }

        function onSuccess(result) {
            $('#AddCategory').modal('hide');
            Toast.fire({
                icon: result.status,
                title: result.message
            });
            $('#categoryname, #description').val('');
            categoriesTable.ajax.reload(null, false);
        }
    });
    $(document).on('submit', '#newChartedSongForm, #newDailyChartedSongForm', function (event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Saving charted song, please wait',
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').removeAttr('disabled');
            manualToast.close();
            $('button[type="submit"]').html('Save');
            $('#new-chart, #newChart, #update-chart').modal('hide');

            $('#chartDates').empty();
            $('#chartDates').append(result.dated);

            Toast.fire({
                icon: 'success',
                title: 'Song position has been updated!'
            });

            loadDailyCharts();
            loadChartData();
            loadDates();
        }
    });
    $(document).on('submit', '#newLocalChartedSongForm, #updateLocalChartedSongForm', function (event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Saving charted song, please wait',
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').removeAttr('disabled');
            manualToast.close();
            $('button[type="submit"]').html('Save');
            $('#new-chart, #newChart, #update-chart').modal('hide');

            $('#chartDates').empty();
            $('#chartDates').append(result.dated);

            Toast.fire({
                icon: 'success',
                title: 'Song position has been updated!'
            });

            loadLocalCharts();
            loadLocalDates();
        }
    });
    $(document).on('submit', '#podcastForm', function (event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Saving podcast please wait'
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');
            Toast.fire({
                icon: 'success',
                title: result.message
            });
            $('#new-podcast').modal('hide');
            $('#episode, #date, #link, #image').val('');
            podcastsTable.ajax.reload(null, false);
        }
    });
    $(document).on('submit', '#wallpaperForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Uploading wallpaper please wait'
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').removeAttr('disabled');
            $('#new-wallpaper').modal('hide');
            manualToast.fire({
                icon: result.status,
                title: result.message,
            });
            $(':input').val();
            wallpapersTable.ajax.reload(null, false);
        }
    });
    $(document).on('submit', '#wallpaperUpdateForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Uploading new Wallpaper',
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').removeAttr('disabled');
            manualToast.fire({
                icon: result.status,
                title: result.message,
                text: 'Wallpaper will be available in 3 seconds'
            });

            setTimeout(() => {
                location.reload();
            }, 2500);
        }
    });
    $(document).on('submit', '#chartsForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, "JSON", beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Processing request please wait'
            });
        }

        function onSuccess(result) {
            loadChartData();
            $('#newEntry').modal('hide');
            $(':input').val();
            $('button[type="submit"]').removeAttr('disabled');
            Toast.fire({
                icon: 'success',
                title: 'A song has been added to the charts',
            });
        }
    });
    $(document).on('submit', '#updateDailyChartedSongForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, "HTML", beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...'
            });
        }

        function onSuccess(result) {
            $('#update-chart, #updateChart').modal('hide');
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');

            loadDailyCharts();
            loadChartData();
            loadDates();

            Toast.fire({
                icon: 'success',
                title: 'Charted song has been updated'
            });
        }
    });
    $(document).on('submit', '#deleteChartedSongForm, #deleteDailyChartedSongForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, "HTML", beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Deleting ...'
            });
        }

        function onSuccess(result) {
            console.log(result);
            $('#delete-chart, #deleteChart').modal('hide');
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');

            loadDailyCharts();
            loadChartData();
            loadDates();

            Toast.fire({
                icon: 'success',
                title: 'Charted song has been deleted'
            });
        }
    });
    $(document).on('submit', '#deleteLocalChartedSongForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, "HTML", beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Deleting ...'
            });
        }

        function onSuccess(result) {
            console.log(result);
            $('#delete-chart, #deleteChart').modal('hide');
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');

            loadLocalCharts();
            loadLocalDates();

            Toast.fire({
                icon: 'success',
                title: 'Charted song has been deleted'
            });
        }
    });
    $(document).on('submit', '#reportBugForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Submitting report ... '
            });
        }

        function onSuccess(result) {
            $('#reportBug').modal('hide');
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');
            $(':input').val('');
            Toast.fire({
                icon: result.status,
                title: result.message
            });
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    });
    $(document).on('submit', '#newStudentForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, "HTML", beforeSend, onSuccess);

        function beforeSend() {
            $('button[type="submit"]').attr('disabled', 'disabled');
            $('button[type="submit"]').html('Please Wait ...');
            manualToast.fire({
                icon: 'info',
                title: 'Adding new student ...',
            });
        }

        function onSuccess(result) {
            $('button[type="submit"]').remove('disabled');
            $('button[type="submit"]').html('Save');
        }
    });
    $(document).on('submit', '#update-timeslot-form, #delete-timeslot-form', function(event){
        event.preventDefault();
        let url = $(this).attr('action');
        let formData = new FormData(this);
        let day = $('#update_day').val();

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Sending request ...',
            });
        }

        function onSuccess(result) {
            $('#edit-timeslot, #delete-timeslot').modal('hide');
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');

            Toast.fire({
                icon: 'success',
                title: 'Timeslot updated!',
            });

            setTimeout(() => {
                getAsync('{{ route('timeslots.select') }}', { "day": day }, "HTML", beforeSend, onTimeslotSuccess)

                function onTimeslotSuccess() {
                    $('#timeslot-days li a:contains('+day+')').click();
                    $('#switch_timeslots').attr('day', result);
                }
            }, 1500);
        }
    });
    $(document).on('submit', '#update_employee_form, #delete_employee_form', function(event) {
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Processing request ...'
            });
        }

        function onSuccess(result) {
            employeesTable.ajax.reload(null, false);
            $('.modal').modal('hide');
            Toast.fire({
                icon: result.status,
                title: result.message
            });
        }
    });
    $(document).on('submit', '#update_jock_form, #delete_jock_form', function(event) {
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Processing request ...'
            });
        }

        function onSuccess(result) {
            jocksTable.ajax.reload(null, false);

            $('#view_jock_modal, #delete_jock_modal').modal('hide');

            Toast.fire({
                icon: result.status,
                title: result.message
            });
        }
    });
    $(document).on('submit', '#newIndiegroundForm, #updateIndiegroundForm, #deleteIndiegroundForm', function(event) {
        event.preventDefault();

        let url = $(this).attr('action');
        let formData = new FormData(this);

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Processing request ...'
            });
        }

        function onSuccess(result) {
            $('#yes_button').removeAttr('disabled').html('Yes');
            $('button[type="submit"]').removeAttr('disabled').html('Save');

            indiegroundsTable.ajax.reload(null, false);

            $('#new-indie ,#indie-artist-modal, #delete-indie-modal').modal('hide');

            Toast.fire({
                icon: result.status,
                title: result.message
            });
        }
    });
    $(document).on('submit', '#featuredIndieForm, #updateFeaturedIndieForm, #deleteFeaturedIndieForm', function(event) {
        event.preventDefault();

        let url = $(this).attr('action');
        let formData = new FormData(this);

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            manualToast.fire({
                icon: 'info',
                title: 'Processing request ...'
            });
        }

        function onSuccess(result) {
            featuredIndiegroundsTable.ajax.reload(null, false);

            $('#new-featured, #update-featured-indie, #delete-featured-indie').modal('hide');

            Toast.fire({
                icon: result.status,
                title: result.message
            });
        }
    })
    $(document).on('submit', '#dailyChartsForm, #newEntryChartsForm', function(event){
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            $('button[type="submit"]').attr('disabled', 'disabled');
            manualToast.fire({
                icon: 'info',
                title: 'Processing request please wait'
            });
        }

        function onSuccess(result) {
            if(formData.has('local')) {
                loadLocalCharts();
                loadLocalDates();
            } else {
                loadDailyCharts();
                loadChartData();
                loadDates();
            }

            $('#newEntry').modal('hide');
            $('#Positions, #dated, #name, #song_id').val('');
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').html('Save');

            Toast.fire({
                icon: 'success',
                title: result.message
            });
        }
    });
    $(document).on('submit', '#uploadBackgroundImageForm, #uploadHeaderImageForm', function(event) {
        event.preventDefault();

        let formData = new FormData(this);
        let url = $(this).attr('action');

        postAsync(url, formData, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            $('button[type="submit"]').attr('disabled', 'disabled');
            manualToast.fire({
                icon: 'info',
                title: 'Processing request please wait'
            });
        }

        function onSuccess(result) {
            $('.modal').modal('hide');
            $('button[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').empty().html('<i class="fas fa-save"></i>  Save');

            Toast.fire({
                icon: 'success',
                title: result.message
            });

            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    });
    /* End */

    /* Modal Scripts */
    // Article Image Cropper
    $(document).on('show.bs.modal', '#addArticleImage', function(){
        $('input[id="image"]').click();
    });

    $(document).on('show.bs.modal', '[data-action="Open"]', function(){
        let id = $(this).attr('data-message');

        getAsync('{{ route('messages.index') }}', { "message_id": id }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            // not necessary
        }

        function onSuccess(result) {
            messagesTable.ajax.reload(null, false);
        }
    });

    $(document).on('show.bs.modal', '#new-featured', function() {
        $('#featured_indie_artist_id').on('change', function() {
            let indie_artist_id = $(this).val();

            getAsync('{{ url('indiegrounds') }}' + '/' + indie_artist_id, null, 'JSON', beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                if(result.indieground.introduction === "No Data"){
                    Toast.fire({
                        'icon': 'info',
                        'title': 'Indieground needs an introduction',
                    });
                } else if(result.indieground.introduction != null) {
                    tinymce.get('content').setContent('<p>' + result.indieground.introduction + '</p>');

                    Toast.fire({
                        'icon': 'info',
                        'title': 'Indieground already has introduction',
                    });
                } else {
                    $('#content').attr('required', 'required');
                }
            }
        });
    });

    $(document).on('hide.bs.modal', '#update-artist-image, #update-profile, #update-header', function() {
        $('#custom').removeAttr('hidden');
        $('#artistCropButton').removeAttr('disabled').attr('hidden', 'hidden');
        $('#cancelButton').attr('hidden', 'hidden');
        $('#doneButton').removeAttr('hidden');
        $('.cropper').removeClass('ready');
        $('#croppingImage').croppie('destroy');
    });

    // for clossing the current modal that's opened or active.
    $(document).on('show.bs.modal', '.modal', function() {
        $('.modal').modal('hide');
    });
    /* End */

    /* Miscellaneous Scripts */
    // Upload Profile Picture
    $('#jockImage').on('change', function () {
        $('#custom').attr('hidden', 'hidden');
        $('#cropButton').removeAttr('hidden');
        $('#cancelButton').removeAttr('hidden');
        $('#doneButton').attr('hidden', 'hidden');
        $imageToBeCropped = $('#croppingImage').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300,
                type: 'square'
            },
            boundary: {
                width: 500,
                height: 500
            }
        });

        readFile(this);
    });

    $('#cancelButton').on('click', function () {
        $('.cropper').removeClass('ready');
        $imageToBeCropped.croppie('destroy');
        $('#addArticleImage').modal('hide');
        $('#croppedArticleImage').val('');
        $('#update-profile').modal('hide');
        $('#update-artist-image').modal('hide');
        $('#dialog').modal('show');
        $('#jockImage').val('');
        $('#custom').removeAttr('hidden');
        $('#cropButton').attr('hidden', 'hidden');
        $('#cancelButton').attr('hidden', 'hidden');
        $('#doneButton').removeAttr('hidden');

        $('#noButton').on('click', function () {
            $('#update-profile').modal('show');
            $('#update-artist-image').modal('show');
        });
    });

    $('#cropButton').on('click', function () {
        $imageToBeCropped.croppie('result', {
            type: 'canvas',
            size: { width: 500, height: 500 },
            quality: 1
        }).then(function (response) {
            $.ajax({
                url: '{{ route('users.photo.upload') }}',
                method: 'POST',
                data: {
                    'image' : response
                },
                beforeSend: () => {
                    $('#cropButton').attr('disabled', 'disabled');
                    Toast.fire({
                        icon: 'info',
                        title: 'Uploading profile picture please wait',
                    });
                },
                success: (result) => {
                    $('#cropButton').attr('hidden', 'hidden');
                    $('input[id="imageName"]').val(result);

                    Toast.fire({
                        icon: 'success',
                        title: 'Profile picture has been saved!',
                    });

                    setTimeout(() => {
                        $('#saveButton').click();
                    }, 2000);
                },
                error: (result) => {
                    manualToast.fire({
                        icon: 'error',
                        title: result.status + ' ' + result.statusText,
                    });
                }
            });
        });
    });

    // Upload Header Photo
    $('#header').on('change', function () {
        $('#headerCustom').attr('hidden', 'hidden');
        $('#cropHeaderButton').removeAttr('hidden');
        $('#cancelHeaderButton').removeAttr('hidden');
        $('#doneHeaderButton').attr('hidden', 'hidden');

        $imageToBeCropped = $('#croppingHeaderImage').croppie({
            enableExif: true,
            viewport: {
                width: 1920,
                height: 500,
                type: 'square'
            },
            boundary: {
                width: 1920,
                height: 800
            }
        });

        readFile(this);
    });

    $('#cancelHeaderButton').on('click', function () {
        $('.cropperHeader').removeClass('ready');
        $imageToBeCropped.croppie('destroy');
        $('#update-cover').modal('hide');
        $('#dialog').modal('show');
        $('#header').val('');
        $('#headerCustom').removeAttr('hidden');
        $('#cropHeaderButton').attr('hidden', 'hidden');
        $('#cancelHeaderButton').attr('hidden', 'hidden');
        $('#doneHeaderButton').removeAttr('hidden');

        $('#noButton').on('click', function () {
            $('#update-cover').modal('show');
        });
    });

    $('#cropHeaderButton').on('click', function () {
        $imageToBeCropped.croppie('result', {
            type: 'canvas',
            size: { width: 1920, height: 500 },
        }).then(function (response) {
            $.ajax({
                url: '{{ route('users.photo.upload') }}',
                method: 'POST',
                data: {
                    'image': response
                },
                beforeSend: () => {
                    $('#cropHeaderButton').attr('disabled', 'disabled');
                    manualToast.fire({
                        icon: 'info',
                        title: 'Uploading header image please wait',
                    });
                },
                success: (result) => {
                    $('#cropHeaderButton').attr('hidden', 'hidden');
                    $('input[id="headerImageName"]').val(result);

                    Toast.fire({
                        icon: 'success',
                        title: 'Header image has been saved!'
                    });

                    setTimeout(() => {
                        $('#saveHeaderButton').click();
                    }, 2000);
                },
                error: (result) => {
                    manualToast.fire({
                        icon: 'error',
                        title: result.status + ' ' + result.statusText,
                    });
                }
            });
        });
    });

    // Main image
    $('#jockMainImage').on('change', function() {
        $('#customMain').attr('hidden', 'hidden');
        $('#cropMainButton').removeAttr('hidden');
        $('#cancelMainButton').removeAttr('hidden');
        $('#doneMainButton').attr('hidden', 'hidden');
        $imageToBeCropped = $('#croppingMainImage').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300,
                type: 'square'
            },
            boundary: {
                width: 500,
                height: 500
            }
        });

        readFile(this);
    });

    $('#cancelMainButton').on('click', function () {
        $('.cropper').removeClass('ready');
        $imageToBeCropped.croppie('destroy');
        $('#addArticleImage').modal('hide');
        $('#croppedArticleImage').val('');
        $('#update-profile').modal('hide');
        $('#update-artist-image').modal('hide');
        $('#dialog').modal('show');
        $('#jockMainImage').val('');
        $('#customMain').removeAttr('hidden');
        $('#cropMainButton').attr('hidden', 'hidden');
        $('#cancelMainButton').attr('hidden', 'hidden');
        $('#doneMainButton').removeAttr('hidden');

        $('#noButton').on('click', function () {
            $('#update-profile').modal('show');
            $('#update-artist-image').modal('show');
        });
    });

    $('#cropMainButton').on('click', function () {
        $imageToBeCropped.croppie('result', {
            type: 'canvas',
            size: { width: 500, height: 500 },
            quality: 1
        }).then(function (response) {
            $.ajax({
                url: '{{ route('users.photo.upload') }}',
                method: 'POST',
                data: {
                    'image' : response
                },
                beforeSend: () => {
                    $('#cropMainButton').attr('disabled', 'disabled');
                    Toast.fire({
                        icon: 'info',
                        title: 'Uploading main picture please wait',
                    });
                },
                success: (result) => {
                    $('#cropMainButton').attr('hidden', 'hidden');
                    $('input[id="mainImageName"]').val(result);

                    Toast.fire({
                        icon: 'success',
                        title: 'Main picture has been saved!',
                    });

                    setTimeout(() => {
                        $('#saveMainButton').click();
                    }, 2000);
                },
                error: (result) => {
                    manualToast.fire({
                        icon: 'error',
                        title: result.status + ' ' + result.statusText,
                    });
                }
            });
        });
    });

    $('#active').addClass('active');
    $('table').css('width', '100%');
    /* End */
</script>
