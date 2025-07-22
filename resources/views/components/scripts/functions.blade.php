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
            manualToast.fire({
                icon: 'warning',
                title: 'Sorry - you\'re browser doesn\'t support the FileReader API',
            });
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
                $('button[type="submit"]').html('Save');

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
                $('button[type="submit"]').html('Save');

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
            $('#update_song_artist_id, #song_artist_id, #album_artist_id').append(result);
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
            console.log(result);
            $('#chartDates, #newEntryChartDate').empty();
            $('#chartDates, #newEntryChartDate').append(result.dates);

            $('#official, #draft').attr('data-payload', result.latest);
        }
    }

    // Daily Charts
    function loadDailyDates() {
        getAsync('{{ route('charts.daily') }}', { 'isDaily': true }, 'JSON', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            const { surveyDates, chartCount } = result;

            if (!surveyDates) {
                $('#surveyDate').empty();
                $('#surveyDate').append('<option value selected disabled> No available daily chart date </option>');
            } 
            else {
                $('#surveyDate, #newEntryChartDate').empty();
                $('#surveyDate, #newEntryChartDate').append(surveyDates);
            }

            if (chartCount === 5) {
                $('#post').removeAttr('disabled');
                // $('#new').addClass('disabled');
            }
            else {
                $('#post').attr('disabled', 'disabled');
                // $('#new').removeClass('disabled');
            }
        }
    }

    // Store interval reference globally
    let dailyChartReloadInterval = null;

    function createDailySurveyDataTable(date, action) {
        let dailyChartsTable = $('#tdsTable');

        // Destroy old DataTable instance if it exists
        if ($.fn.DataTable.isDataTable(dailyChartsTable)) {
            dailyChartsTable.DataTable().destroy();
        }

        // Create a new DataTable
        let dailyChartsDataTable = dailyChartsTable.DataTable({
            ajax: {
                url: '{{ route('charts.daily') }}',
                data: {
                    'daily': true,
                    'datatable': true,
                    'dated': date,
                    'type': action
                },
                dataSrc: 'charts'
            },
            columns: [
                {data: 'position'},
                {data: 'song.name'},
                {data: 'song.album.artist.name'},
                {data: 'song.album.name'},
                {data: 'total_votes'},
                {data: 'dated'},
                {data: 'options'},
            ],
        });

        // Prevent multiple intervals from stacking
        if (dailyChartReloadInterval) {
            clearInterval(dailyChartReloadInterval);
        }

        // Set up one consistent interval to reload
        dailyChartReloadInterval = setInterval(() => {
            dailyChartsDataTable.ajax.reload(null, false);
        }, 2000); // 2 seconds
    }


    function loadDailyCharts() {
        let date = $('#surveyDate').attr('data-payload') ?? $('#surveyDate').val();
        let action = $('#surveyDate').attr('data-chart-type');

        console.log('loadDailyCharts(): ', [date, action]);

        getAsync('{{ route('charts.daily') }}', { 'daily': true }, 'HTML', beforeSend, onSuccess);

        function beforeSend() {

        }

        function onSuccess(result) {
            $('#dailyCharts').empty();
            $('#dailyCharts').append(result);

            setTimeout(() => {
                createDailySurveyDataTable(date, action);
            }, 800);
        }
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
                getAsync('{{ url()->current() }}' + '/' + outbreak_song, {"song_id": outbreak_song}, 'JSON', beforeSend, onSuccess);

                function beforeSend() {

                }

                function onSuccess(result) {
                    if (result === true) {
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
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }
</script>
