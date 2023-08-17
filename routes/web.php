<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArchiveLogsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ContestantController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DropoutsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FactController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\GimikBoardController;
use App\Http\Controllers\GiveawayController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndieController;
use App\Http\Controllers\JockController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MobileAppAssetController;
use App\Http\Controllers\MobileAppTitleController;
use App\Http\Controllers\OutbreakController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\RadioOneController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\SoftDeletesController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SouthsidesController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentJockController;
use App\Http\Controllers\TimeslotController;
use App\Http\Controllers\UserLogsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\WallpapersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function() {
    return redirect()->route('home');
});

Route::get('/home', function() {
    return redirect()->route('home');
});

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    Route::get('/reports', [HomeController::class, 'reports'])->name('reports');
    Route::post('/report_bug', [HomeController::class, 'reportBug'])->name('report.bug');

    Route::resource('/awards', AwardController::class);

    Route::resource('/employees', EmployeeController::class);

    Route::resource('/designations', DesignationController::class);

    Route::resource('/jocks', JockController::class);
    Route::get('/jock/{id}', [JockController::class, 'profile'])->name('jocks.profile');
    Route::post('/dj_delete', [JockController::class, 'delete'])->name('jocks.delete');

    Route::get('/jocks_show_image', [JockController::class, 'showImage'])->name('jocks.show.image');
    Route::post('/jocks_add_image', [JockController::class, 'addImage'])->name('jocks.store.image');
    Route::match(['put', 'patch'], '/jocks_update_image', [JockController::class, 'updateImage'])->name('jocks.update.image');
    Route::delete('/jocks_remove_image', [JockController::class, 'removeImage'])->name('jocks.remove.image');

    Route::get('/jocks_show_link', [JockController::class, 'showLink'])->name('jocks.show.link');
    Route::post('/jocks_add_link', [JockController::class, 'addLink'])->name('jocks.add.link');
    Route::match(['put', 'patch'], '/jocks_update_link/{id}', [JockController::class, 'updateLink'])->name('jocks.update.link');
    Route::delete('/jocks_delete_link/{id}', [JockController::class, 'removeLink'])->name('jocks.delete.link');

    Route::resource('/artists', ArtistController::class);
    Route::get('/reload_artists', [ArtistController::class, 'reloadTable'])->name('reload.artists');
    Route::post('/artists/add/image', [ArtistController::class, 'addArtistImage'])->name('artists.add.image');

    Route::resource('/albums', AlbumController::class);
    Route::get('/reload_albums', [AlbumController::class, 'reloadAlbums'])->name('reload.albums');

    Route::resource('/songs', SongController::class);
    Route::get('/reload_songs', [SongController::class, 'reloadSongsTable'])->name('reload.songs');

    Route::get('/filter_album',[SongController::class, 'filterArtistAlbums'])->name('filter.artist.albums');
    Route::get('/artist_album',[SongController::class, 'selectSongInTable'])->name('songs.select.album');

    Route::resource('/genres', GenreController::class);

    Route::resource('/charts', ChartController::class);
    Route::get('/chart_song',  [ChartController::class, 'selectSongInTable'])->name('charts.select.song');
    Route::get('/filter_chart', [ChartController::class, 'filter'])->name('filter.chart');
    Route::post('drop', [ChartController::class, 'DropChart'])->name('charts.drop');
    Route::post('/new_chart', [ChartController::class, 'NewChart'])->name('charts.new');
    Route::get('/daily', [ChartController::class, 'dailyCharts'])->name('charts.daily');
    Route::get('/chart_dates', [ChartController::class, 'create'])->name('get.chart.dates');
    Route::post('/remove_daily', [ChartController::class, 'removeDailyChart'])->name('charts.daily.remove');

    Route::get('/survey', [VoteController::class, 'index'])->name('survey.votes');
    Route::get('/send_vote', [VoteController::class, 'increaseDecreaseVotes'])->name('vote.song');
    Route::get('/refresh_table', [VoteController::class, 'refreshVotesTable'])->name('reload.votes');
    Route::get('/refresh_vote_logs', [VoteController::class, 'refreshLogsTable'])->name('reload.logs');
    Route::get('/refresh_tally',[VoteController::class, 'refreshTallyLogsTable'])->name('reload.tally');

    Route::resource('/dropouts', DropoutsController::class);

    Route::resource('/articles', ArticleController::class);

    Route::get('/articles_search', [ArticleController::class, 'search'])->name('articles.search');
    Route::get('/article_list', [ArticleController::class, 'yearlyArticles'])->name('yearly.articles');
    Route::post('/article_list_delete', [ArticleController::class, 'delete'])->name('articles.delete');

    Route::post('/article_sub_content', [ArticleController::class, 'addContent'])->name('articles.add.content');

    Route::post('/article_image', [ArticleController::class, 'addImage'])->name('articles.add.image');
    Route::post('/article_image_delete', [ArticleController::class, 'removeImage'])->name('articles.remove.image');

    Route::post('/article_link', [ArticleController::class, 'addLink'])->name('articles.add.link');
    Route::post('/article_link_delete', [ArticleController::class, 'removeLink'])->name('articles.remove.link');

    Route::post('/article_related/{article_id}', [ArticleController::class, 'addRelated'])->name('articles.add.related');
    Route::post('/article_related_delete/{article_id}', [ArticleController::class, 'removeRelated'])->name('articles.remove.related');

    Route::post('/article_publish', [ArticleController::class, 'publish'])->name('articles.publish');
    Route::get('/archive', [ArticleController::class, 'archive'])->name('article.archives');
    Route::get('/articles/{id}/preview', [ArticleController::class, 'preview'])->name('article.preview');

    Route::resource('/articles/{id}/sub_contents', ContentController::class);

    Route::get('/digitalContentSpecialist/{employeeNumber}', [ArticleController::class, 'article.user']);
    Route::post('/digitalContentSpecialist/{id}', [ArticleController::class, 'article.user.update']);

    Route::resource('/categories', CategoryController::class);
    Route::resource('/sliders', HeaderController::class);

    Route::resource('/shows', ShowController::class);
    Route::post('/shows_Jocks/{id}', [ShowController::class, 'addJock'])->name('shows.add.jock');
    Route::post('/remove_jocks/{id}', [ShowController::class, 'removeJock'])->name('shows.remove.jock');
    Route::match(['put', 'patch'], '/shows/update/background_image/{id}', [ShowController::class, 'storeBackgroundImage'])->name('shows.update.background.image');
    Route::match(['put', 'patch'], '/shows/update/header_image/{id}', [ShowController::class, 'storeHeaderImage'])->name('shows.update.header.image');

    Route::resource('/timeslots', TimeslotController::class);
    Route::get('/timeslot/select', [TimeslotController::class, 'selectDay'])->name('timeslots.select');
    Route::get('/timeslot/{id}/add/jock/{jock_id}', [TimeslotController::class, 'addJock'])->name('timeslot.add.jock');
    Route::get('/timeslot/{id}/remove/jock/{jock_id}', [TimeslotController::class, 'removeJock'])->name('timeslot.remove.jock');

    Route::resource('/podcasts', PodcastController::class);

    Route::resource('/messages', MessageController::class, ['only' => ['index']]);

    Route::resource('/schools', SchoolController::class);

    Route::resource('/gimikboards', GimikBoardController::class);

    Route::resource('/batch', BatchController::class);
    Route::post('/batch_image',[BatchController::class, 'addImage'])->name('batch.add.image');
    Route::get('/add_param', [BatchController::class, 'addParam'])->name('batch.add.param');
    Route::post('/del_param/{id}', [BatchController::class, 'delParam'])->name('batch.delete.param');

    Route::resource('/students', StudentController::class, ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('/sponsors', SponsorController::class, ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

    Route::resource('/indiegrounds', IndieController::class, ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('/featured', FeaturedController::class, ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

    Route::resource('/giveaways', GiveawayController::class, ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    Route::get('/giveaways/activate/{id}', [GiveawayController::class, 'activate'])->name('giveaways.activate');
    Route::resource('/contestants', ContestantController::class, ['only' => ['index', 'show', 'update', 'destroy']]);

    Route::resource('/user_logs', UserLogsController::class, ['only' => ['index']]);
    Route::resource('/archives', ArchiveLogsController::class, ['only' => ['index']]);

    Route::get('/softdeletes',[SoftDeletesController::class, 'index'])->name('softdeletes');
    Route::match(['get', 'post'], '/retrieve/{model}/{id}', [SoftDeletesController::class, 'restoreData'])->name('softdeletes.restore');

    Route::get('/radio_one/batches', [RadioOneController::class, 'batches'])->name('radioOne.batches');
    Route::get('/radio_one/batch/{id}', [RadioOneController::class, 'showBatch'])->name('radioOne.batch');
    Route::post('/radio_one/batches/store', [RadioOneController::class, 'storeBatch'])->name('radioOne.batches.new');
    Route::match(['put', 'patch'], '/radio_one/batch/update/{id}', [RadioOneController::class, 'updateBatch'])->name('radioOne.batches.update');
    Route::delete('/radio_one/batch/delete/{id}', [RadioOneController::class, 'deleteBatch'])->name('radioOne.batches.delete');

    Route::get('/radio_one/jocks', [StudentJockController::class, 'studentJocks'])-> name('radioOne.jocks');
    Route::get('/radio_one/jocks/view/{id}', [StudentJockController::class, 'showStudentJock'])-> name('radioOne.jocks.show');
    Route::post('/radio_one/jocks/store', [StudentJockController::class, 'storeJock'])-> name('radioOne.jocks.store');
    Route::match(['put', 'patch'], '/radio_one/jocks/update/{id}', [StudentJockController::class, 'updateStudentJock'])-> name('radioOne.jocks.update');
    Route::delete('/radio_one/jocks/{id}', [StudentJockController::class, 'deleteJock'])-> name('radioOne.jocks.delete');

    Route::post('/radio_one/add/student_jock/{id}', [StudentJockController::class, 'addStudentToBatch'])->name('radioOne.add.student');
    Route::delete('/radio_one/remove/student_jock/{id}', [StudentJockController::class, 'removeStudentFromBatch'])->name('radioOne.remove.student');
    Route::get('/radio_one/view/photo/{id}', [StudentJockController::class, 'showImage'])->name('radioOne.view.photo');
    Route::get('/radio_one/view/social/{id}', [StudentJockController::class, 'viewSocial'])->name('radioOne.view.social');

    Route::resource('/wallpapers', WallpapersController::class);

    Route::resource('/users', UsersController::class, ['only' => ['index', 'show']]);
    Route::post('/password', [UsersController::class, 'changePassword'])->name('change.password');

    Route::get('/profile/{employeeNumber}', [UsersController::class, 'profile'])->name('users.profile');
    Route::get('/jock/{id}/header', [UsersController::class, 'changeHeader'])->name('users.header');
    Route::post('/profile/upload', [UsersController::class, 'addProfile'])->name('users.photo.upload');

    Route::post('/update', [UsersController::class, 'saveProfileImageToDatabase'])->name('users.image.update');
    Route::post('/header', [UsersController::class, 'saveHeaderImageToDatabase'])->name('users.header.update');
    Route::post('/main', [UsersController::class, 'saveMainImageToDatabase'])->name('users.main.update');

    Route::resource('/outbreaks', OutbreakController::class, ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

    Route::resource('/southsides', SouthsidesController::class);

    // Photos, Facts, and Links
    Route::post('/photos/upload', [PhotoController::class, 'store'])->name('photos.store');
    Route::patch('/photos/update/{id}', [PhotoController::class, 'update'])->name('photos.update');
    Route::delete('/photos/delete/{id}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    Route::resource('/facts', FactController::class);

    Route::post('/socials/add', [SocialController::class, 'store'])->name('socials.store');
    Route::patch('/socials/update/{id}', [SocialController::class, 'update'])->name('socials.update');
    Route::delete('/socials/delete/{id}', [SocialController::class, 'destroy'])->name('socials.destroy');

    Route::prefix('assets')->group(function() {
        Route::get('', [MobileAppAssetController::class, 'index'])->name('asset.index');
        Route::get('/show/{id}', [MobileAppAssetController::class, 'show'])->name('asset.show');
        Route::post('/store', [MobileAppAssetController::class, 'store'])->name('asset.store');
        Route::put('/update/{id}', [MobileAppAssetController::class, 'update'])->name('asset.update');
        Route::delete('/delete/{id}', [MobileAppAssetController::class, 'destroy'])->name('asset.destroy');
        Route::post('/upload', [MobileAppAssetController::class, 'uploadImage'])->name('asset.upload-image');
    });

    Route::prefix('titles')->group(function() {
        Route::get('', [MobileAppTitleController::class, 'index'])->name('title.index');
        Route::get('/show/{id}', [MobileAppTitleController::class, 'show'])->name('title.show');
        Route::post('/store', [MobileAppTitleController::class, 'store'])->name('title.store');
        Route::put('/update/{id}', [MobileAppTitleController::class, 'update'])->name('title.update');
        Route::delete('/delete/{id}', [MobileAppTitleController::class, 'destroy'])->name('title.destroy');
    });
});

