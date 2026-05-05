<script type="text/javascript">
    function viewData() {
        $('*[data-href]').on('click', function () {
            window.location = $(this).data("href");
        });
    }

    function readFile(input) {
        if (input.files && input.files[0]) {
            if (/^image/.test(input.files[0].type)) { // read image file
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('.cropper').addClass('ready');
                    $('.cropperHeader').addClass('ready');
                    $imageToBeCropped.croppie('bind', {
                        url: e.target.result
                    }).then(function () {
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
            console.log("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    function getDate() {
        return new Date().toLocaleDateString('en', {weekday: "long"});
    }

    function getAsync(url, parameters = {}, type = 'JSON', beforeSendCallback = () => {}, successCallback = () => {}) {
        $.ajax({
            url: url,
            type: 'GET',
            data: parameters,
            dataType: type,
            beforeSend: beforeSendCallback,
            success: successCallback,
            error: (xhr, textStatus, errorThrown) => {
                console.log("Error: ", xhr);
                console.log("Error Thrown: ", errorThrown);
                
                $('.modal').modal('hide');
                $('button[type="submit"]').removeAttr('disabled');

                if (xhr.responseJSON) {
                    let errors = "";

                    if (Array.isArray(xhr.responseJSON)) {
                        for (let i = 0; i <= xhr.responseJSON.length; i++) {
                            errors = '<p>' + xhr.responseJSON[i].message + '</p>';
                        }
                    } else {
                        errors = '<p>' + xhr.responseJSON.message + '</p>';
                    }

                    manualToast.fire({
                        icon: 'error',
                        title: 'Error/s had been encountered while processing your request',
                        html: '' +
                            '' + errors + '',
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

    function postAsync(url, parameters = {}, type = 'JSON', beforeSendCallback = () => {}, successCallback = () => {}) {
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

                if (xhr.responseJSON) {
                    let errors = "";

                    if (Array.isArray(xhr.responseJSON)) {
                        for (let i = 0; i <= xhr.responseJSON.length; i++) {
                            errors = '<p>' + xhr.responseJSON[i].message + '</p>';
                        }
                    } else if (xhr.responseJSON.message) {
                        errors = '<p>' + xhr.responseJSON.message + '</p>';
                    } else {
                        errors = '<p>' + xhr.responseJSON.error + '</p>';
                    }

                    manualToast.fire({
                        icon: 'error',
                        title: 'Error/s had been encountered while processing your request',
                        html: '' +
                            '' + errors + '',
                    });
                } else {
                    if (xhr.responseText.includes('SQLSTATE[23000]')) {
                        errors = '<p>A field has been <span class="badge badge-danger">Undefined</span></p>';

                        manualToast.fire({
                            icon: 'error',
                            title: 'Error/s had been encountered while processing your request',
                            html: '' +
                                '' + errors + '',
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
            $('#update_song_artist_id, #song_artist_id, #album_artist_id, #artist_id').append(result);
        }
    }

    // Weekly Chart Dates
    function loadDates() {
        getAsync('{{ route('get.chart.dates') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#chartDates, #newEntryChartDate, #surveyDate').empty();
            $('#chartDates, #newEntryChartDate, #surveyDate').append(result.dates);

            $('#official, #draft, #post').attr('data-payload', result.latest);
        }
    }

    function loadLocalDates() {
        getAsync('{{ route('southsides.create') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            // console.log(result);
            $('#chartDates, #newEntryChartDate').empty();
            $('#chartDates, #newEntryChartDate').append(result.dates);

            $('#official, #draft').attr('data-payload', result.latest);
        }
    }

    // Daily Charts
    function loadDailyDates(date, throwback = false) {
        getAsync(
            '{{ route('charts.daily') }}',
            { 
                daily: true, 
                surveyDates: true, 
                throwback: throwback,
                dated: date
            },
            'JSON',
            null, // No need for an empty beforeSend function
            ({ surveyDates, chartCount, isPosted }) => {
                console.log("Result:", { surveyDates, chartCount, isPosted });

                const $surveyDate = $('#surveyDate');
                const $newEntryChartDate = $('#newEntryChartDate');

                if (surveyDates) {
                    $surveyDate.add($newEntryChartDate).empty().append(surveyDates);
                } 
                else {
                    $surveyDate.empty().append(
                        '<option value selected disabled>No available daily chart date</option>'
                    );
                }

                // if (chartCount == 5 && isPosted == 1) {
                //     $('#post').prop('disabled', true);
                // } 
                // else {
                //     $('#post').removeAttr('disabled');
                // }
            }
        );
    }


    // Store interval reference globally
    let refreshingDataTableInterval = null;

    function createRefreshingDataTable(date, action, throwback = false, elementID) {
        let targetDataTable = $(`#${elementID}`);

        // Destroy old DataTable instance if it exists
        if ($.fn.DataTable.isDataTable(targetDataTable)) {
            targetDataTable.DataTable().destroy();
        }

        console.log("createRefreshingDataTable: ", "Date: ", date, "Action: ", action, "Throwback: ", throwback, "ElementID: ", elementID);

        // Decide first column based on action
        const firstColumn = action == 'draft'
            ? { title: 'ID', data: 'id' }
            : { title: 'Position', data: 'position' };

        // Decide config based on elementID
        let tableConfigs = {
            dailyChartSongsTable: {
                dataSrc: 'songs',
                columns: [
                    firstColumn,
                    { title: 'Song',  data: 'name' },
                    { title: 'Artist',  data: 'album.artist.name' },
                    { title: 'Album',  data: 'album.name' },
                    { title: 'Votes',  data: 'votes' }
                ],
                extraParams: {
                    daily: true,
                    date: date,
                    datatable: true,
                    type: action
                }
            },
            default: {
                dataSrc: 'charts',
                columns: [
                    firstColumn,
                    { title: 'Song', data: 'song.name' },
                    { title: 'Artist', data: 'song.album.artist.name' },
                    { title: 'Album', data: 'song.album.name' },
                    { title: 'Votes', data: 'total_votes' },
                    { title: 'Actions', data: 'options' }
                ],
                extraParams: {
                    daily: true,
                    datatable: true,
                    throwback: throwback,
                    date: date,
                    type: action
                }
            }
        };

        // Pick configuration (fallback to default)
        let config = tableConfigs[elementID] || tableConfigs.default;

        console.log("Config: ", config);

        // Initialize DataTable
        let refreshingDataTable = targetDataTable.DataTable({
            ajax: {
                url: '{{ route('charts.daily') }}',
                data: config.extraParams,
                dataSrc: config.dataSrc
            },
            columns: config.columns
        });

        // Prevent multiple intervals from stacking
        if (refreshingDataTableInterval) {
            clearInterval(refreshingDataTableInterval);
        }

        // Set up one consistent interval to reload
        refreshingDataTableInterval = setInterval(() => {
            refreshingDataTable.ajax.reload(null, false);
        }, 1800); // 1.8 seconds
    }


    function loadDailyCharts() {
        const $surveyDate = $('#surveyDate');

        let date = $surveyDate.attr('data-payload') ?? $surveyDate.val();
        let action = $surveyDate.attr('data-chart-type');
        let chart_type = $surveyDate.attr('data-chart-type') == 'draft' ? 'draft' : 'official';
        // Removed this permanently due to digital direction.
        // let $table = chart_type == 'draft' ? 'dailyChartSongsTable' : 'tdsTable';

        getAsync('{{ route('charts.daily') }}', { 'daily': true, 'chartType': chart_type }, 'HTML', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#dailyCharts').empty();
            $('#dailyCharts').append(result);

            setTimeout(() => {
                // if (chart_type == 'draft') {
                //     $('#post').removeAttr('disabled');
                // }
                // else {
                //     $('#post').prop('disabled', true);
                // }
                createRefreshingDataTable(date, action, false, 'tdsTable');
            }, 800);
        }

        console.log('loadDailyCharts(): ', [date, action]);
    }

    function loadChartData() {
        getAsync('{{ route('charts.index') }}', null, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#monsterCharts').empty();
            $('#monsterCharts').append(result);
        }
    }

    function loadVotingCharts() {
        getAsync('{{ route('survey.votes') }}', null, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#monsterCharts').empty();
            $('#monsterCharts').append(result);
        }
    }

    function loadLocalCharts() {
        getAsync('{{ route('southsides.index') }}', {"load": "charts"}, 'HTML', beforeSend, onSuccess);

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
    function loadShows() {
        getAsync('{{ route('podcasts.index') }}', null, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#show_id, #update_show_id').empty();
            $('#show_id, #update_show_id').append(result.shows);
        }
    }

    // End

    // Articles
    function loadArticles() {
        let article = 'published';

        getAsync('{{ route('articles.index') }}', {"status": article}, "HTML", beforeSend, onSuccess);

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
                    [0, 'desc']
                ]
            });
        }
    }

    // End

    function loadSchools() {
        getAsync('{{ route('students.index') }}', {"location": '{{ env('STATION_CODE') }}'}, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#school_id').empty();
            $('#school_id, #student_school_id').append(result);
        }
    }

    function loadTimeslots() {
        getAsync('{{ route('timeslots.index') }}', {'day': true}, "HTML", beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#timeslot-days li a:contains(' + result + ')').click();
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

            if (outbreaks > 0) {
                for (let i = 0; i < result.outbreaks.length; i++) {
                    spotifyPlayer += '<div class="col-md-6"><div class="embed-container"><iframe src="https://open.spotify.com/embed/track/' + result.outbreaks[i].track_link + '" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe></div></div>';
                }

                $('#outbreakSongsContainer').append(spotifyPlayer);
            }

            let outbreak_song = $('#outbreak_song_id').val();

            $('#newOutbreak').on('shown.bs.modal', function () {
                getAsync("{{ route('outbreaks.index') }}" + '/' + outbreak_song, {"song_id": outbreak_song}, 'JSON', beforeSend, onSuccess);

                function beforeSend() {

                }

                function onSuccess(result) {
                    if (result == true) {
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

    function getAge(date) {
        let today = new Date();
        let birthDate = new Date(date);
        let age = today.getFullYear() - birthDate.getFullYear();
        let m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m == 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    function setMusicAwardSubmitState(disabled, text = 'Save') {
        $('#musicAwardSubmitButton').prop('disabled', disabled).text(text);
    }

    function resetAwardTypeDropdowns() {
        $('#artist_id').html('<option value="">Please select an artist</option>');
        $('#album_id').html('<option value="">Please select an album</option>');
        $('#song_id').html('<option value="">Please select a song</option>');
    }

    function populateDropdown(selector, items, labelKey = 'name', defaultText = 'Please select') {
        let options = `<option value="">${defaultText}</option>`;

        $.each(items, function (_, item) {
            options += `<option value="${item.id}">${item[labelKey]}</option>`;
        });

        $(selector).html(options);
    }

    function loadArtistsDropdown(selectedId = '') {
        return $.ajax({
            url: '{{ route('reload.artists') }}',
            type: 'GET',
            dataType: 'JSON'
        }).done(function (result) {
            const artists = result.artists || result.data || result;
            populateDropdown('#artist_id', artists, 'name', 'Please select an artist');

            if (selectedId) {
                $('#artist_id').val(String(selectedId));
            }
        });
    }

    function loadAlbumsDropdown(selectedId = '') {
        return $.ajax({
            url: '{{ route('albums.index') }}',
            type: 'GET',
            dataType: 'JSON'
        }).done(function (result) {
            const albums = result.albums || result.data || result;
            populateDropdown('#album_id', albums, 'name', 'Please select an album');

            if (selectedId) {
                $('#album_id').val(String(selectedId));
            }
        });
    }

    function loadSongsDropdown(selectedId = '') {
        return $.ajax({
            url: '{{ route('songs.index') }}',
            type: 'GET',
            dataType: 'JSON'
        }).done(function (result) {
            const songs = result.songs || result.data || result;
            populateDropdown('#song_id', songs, 'name', 'Please select a song');

            if (selectedId) {
                $('#song_id').val(String(selectedId));
            }
        });
    }

    function toggleAwardTypeFields(type, selectedId = '') {
        $('#artist-group, #album-group, #song-group').addClass('d-none');
        $('#artist_id, #album_id, #song_id').val('');

        if (!type) {
            setMusicAwardSubmitState(false, 'Save');
            return $.Deferred().resolve().promise();
        }

        setMusicAwardSubmitState(true, 'Loading...');

        if (type === 'artist') {
            $('#artist-group').removeClass('d-none');
            return loadArtistsDropdown(selectedId).always(function () {
                setMusicAwardSubmitState(false, 'Save');
            });
        }

        if (type === 'album') {
            $('#album-group').removeClass('d-none');
            return loadAlbumsDropdown(selectedId).always(function () {
                setMusicAwardSubmitState(false, 'Save');
            });
        }

        if (type === 'song') {
            $('#song-group').removeClass('d-none');
            return loadSongsDropdown(selectedId).always(function () {
                setMusicAwardSubmitState(false, 'Save');
            });
        }

        setMusicAwardSubmitState(false, 'Save');
        return $.Deferred().resolve().promise();
    }
</script>
