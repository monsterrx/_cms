<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Bugs;
use App\Models\Chart;
use App\Models\Employee;
use App\Models\Contest;
use App\Models\Message;
use App\Models\Outbreak;
use App\Models\Podcast;
use App\Models\Song;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Firebase\JWT\JWT;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $level = Auth::user()->Employee->Designation->level;
        if ($level === 1 || $level === 2 || $level === 6 || $level === 7 || $level === 9) {
            $where = DB::table('charts')
                ->whereNull('deleted_at')
                ->where('daily', 0)
                ->where('local', 0)
                ->where('location', $this->getStationCode())
                ->select('dated')
                ->max('dated');

            $chart = Chart::where('dated', $where)
                ->whereNull('deleted_at')
                ->where('daily', 0)
                ->where('local', 0)
                ->where('location', $this->getStationCode())
                ->orderBy('dated','desc')
                ->orderBy('position')
                ->get()
                ->take(10);

            foreach ($chart as $c) {
                $c->Song->Album->image = $this->verifyPhoto($c->Song->Album->image, 'albums');
            }

            //
            $employees = Employee::whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->count();

            $inactiveEmployees = Employee::onlyTrashed()
                ->where('location', $this->getStationCode())
                ->count();

            //
            $artists = Artist::whereNull('deleted_at')->count();
            $albums = Album::whereNull('deleted_at')->count();
            $songs = Song::whereNull('deleted_at')->count();


            //
            $recentArtist = Artist::orderBy('created_at', 'desc')
                ->take('5')
                ->get();
            $recentAlbum = Album::orderBy('created_at', 'desc')
                ->take('5')
                ->get();
            $recentSong = Song::orderBy('created_at', 'desc')
                ->take('5')
                ->get();

            //
            $recGiveaway = Contest::whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->orderBy('created_at', 'desc')->get();

            //
            $messages = Message::where('is_seen', '0')
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->count();

            //
            $podcasts = Podcast::with('Show')
                ->whereNull('deleted_at')
                ->latest()
                ->get()
                ->take(3);

            foreach ($podcasts as $podcast) {
                $podcast->image = $this->verifyPhoto($podcast->image, 'podcasts');
            }

            //
            $outbreaks = Outbreak::with('Song.Album.Artist')
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->where('dated', $where)
                ->get();

            if($this->getStationCode() !== 'mnl') {
                $data = array(
                    'artists' => $artists,
                    'albums' => $albums,
                    'songs' => $songs,
                    'recArtist' => $recentArtist,
                    'recAlbum' => $recentAlbum,
                    'recSong' => $recentSong,
                    'recGiveaway' => $recGiveaway,
                    'message' => $messages,
                    'podcasts' => $podcasts,
                    'outbreaks' => $outbreaks
                );

                return view('_cms.system-views.dashboard', compact('chart', 'employees', 'where', 'inactiveEmployees', 'data'));
            }

            $data = array(
                'artists' => $artists,
                'albums' => $albums,
                'songs' => $songs,
                'recArtist' => $recentArtist,
                'recAlbum' => $recentAlbum,
                'recSong' => $recentSong,
                'recGiveaway' => $recGiveaway,
                'message' => $messages,
                'podcasts' => $podcasts
            );

            return view('_cms.system-views.dashboard', compact('chart', 'employees', 'where', 'inactiveEmployees', 'data'));
        }

        switch ($level){
            case 3:
                return redirect()->route('articles.index');
            case 4:
                return redirect()->route('sliders.index');
            case 5 || 8:
                return redirect()->route('jocks.index');
            case 6:
                return redirect()->route('messages.index');
            case 7:
                return redirect()->route('artists.index');
            default:
                return redirect()->back()->withErrors('User level not found');
        }
    }

    public function reports(Request $request) {
        if($request->ajax()) {
            if($request['report_id']) {
                $report = Bugs::with('Employee')
                    ->where('id', $request['report_id'])
                    ->first();

                $report['image'] = url('images/reports/'. $report['image']);

                return response()->json($report);
            }

            $reports = Bugs::with('Employee')
                ->whereNull('deleted_at')
                ->get();

            foreach($reports as $report) {
                $report->name = $report->Employee->first_name . ' ' . $report->Employee->last_name;
                $report->option = '<a href="#reportModal" id="openReport" data-report-id="'.$report->id.'" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-envelope-open"></i></a>';
            }

            return response()->json($reports);
        }

        return view('_cms.system-views.users.reports');
    }

    public function reportBug(Request $request) {
        $this->validate($request, [
            'title' => ['required', 'min:6'],
            'description' => 'required',
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg']
        ]);

        $employee = Auth::user()->Employee->id;

        $image = $request['image'];
        $path = 'images/reports';

        if($image) {
            $imageName = date('Ymd') . '-' . mt_rand() . '.' . $image->getClientOriginalExtension();

            $image->move($path, $imageName);

            $file = 'images/reports/'.$imageName;
            Storage::disk('reports')->put($imageName, file_get_contents($file));

            if($this->getStationCode() === 'dav') {
                copy($file, '../monsterdavao/images/reports/'.$imageName);
            }

            if($this->getStationCode() === 'cbu') {
                copy($file, '../monstercebu/images/reports/'.$imageName);
            }

            if($request->ajax()) {
                $bugs = new Bugs([
                    'title' => $request['title'],
                    'description' => $request['description'],
                    'image' => $imageName,
                    'location' => $this->getStationCode(),
                    'employee_id' => $employee
                ]);

                $bugs->save();

                return response()->json(['status' => 'success', 'message' => 'Report submitted!']);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Error occurred, please try again later'], 404);
    }
}
