<?php namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Chart;
use App\Models\Genre;
use App\Models\Jock;
use App\Models\Song;
use App\Models\Tally;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller {

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $action = $request->get("action");

            // Switching the charts
            if(isset($action)) {
                $isSouthsideCharts = $request->has("data-local");
                $chartType = $request->get('chart-type');
                $chartDate = $request->get('date') ?? $request->get('dated');
                $throwback = $request->get('is_throwback');

                if (!isset($chartDate)) {
                    $chartDate = date('Y-m-d');
                }

                if($isSouthsideCharts) {
                    $type = $request->get('data-local');

                    if($action === 'official') {
                        $chart = Chart::where('dated', $chartDate)
                            ->whereNull('deleted_at')
                            ->where('local', '=', $type)
                            ->where('daily', '=', 0)
                            ->where('is_posted', 1)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();

                        return view('_cms.system-views.music._chart.charts', compact('chart'));
                    }

                    if($action === 'draft') {
                        $chart = Chart::where('dated', $chartDate)
                            ->whereNull('deleted_at')
                            ->where('local', '=', $type)
                            ->where('daily', '=', 0)
                            ->where('is_posted', 0)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();

                        return view('_cms.system-views.music._chart.charts', compact('chart'));
                    }

                    if($action === 'post') {
                        Chart::where('dated', $request['dated'])
                            ->whereNull('deleted_at')
                            ->where('local', '=', $type)
                            ->where('daily', '=', 0)
                            ->where('is_posted', 0)
                            ->where('location', $this->getStationCode())
                            ->update(['is_posted' => 1]);

                        return response()->json(['status' => 'success', 'message' => 'The chart has been posted']);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'Unknown chart']);
                    }
                } 
                else if ($chartType === 'daily') {
                    if($action === 'official') {
                        $charts = Chart::where('dated', $chartDate)
                            ->whereNull('deleted_at')
                            ->where('local', 0)
                            ->where('daily', '=', 1)
                            ->where('throwback', '=', 0)
                            ->where('is_posted', 1)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();

                        if ($throwback) {
                            $charts = Chart::where('dated', $chartDate)
                            ->whereNull('deleted_at')
                            ->where('local', 0)
                            ->where('daily', '=', 1)
                            ->where('throwback', '=', $throwback)
                            ->where('is_posted', 1)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();
                        }
 
                        foreach ($charts as $chart) {
                            $chart->total_votes = $chart->LatestChartTally->results ?? 0;
                            // $chart->total_votes = (floatval($chart->online_votes) + floatval($chart->phone_votes) + floatval($chart->social_votes)) ?? 0;
                        }

                        return view('_cms.system-views.music.daily.table', compact('charts'));
                    }

                    if($action === 'draft') {
                        // $songs = Song::query()
                        //     ->with('Album.Artist')
                        //     ->orderBy('votes', 'desc')
                        //     ->orderBy('created_at', 'desc')
                        //     ->get()
                        //     ->take(5);

                        // foreach ($songs as $key => $song) {
                        //     $song->position = $key + 1;
                        // }

                        // return view('_cms.system-views.music.daily.songs-table', compact('songs'));

                        $charts = Chart::query()
                            ->whereNull('deleted_at')
                            ->where('local', 0)
                            ->where('daily', '=', 1)
                            ->where('throwback', '=', 0)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();
 
                        foreach ($charts as $chart) {
                            $chart->total_votes = $chart->LatestChartTally->results ?? 0;
                            // $chart->total_votes = (floatval($chart->online_votes) + floatval($chart->phone_votes) + floatval($chart->social_votes)) ?? 0;
                        }

                        return view('_cms.system-views.music.daily.table', compact('charts'));
                    }

                    if($action === 'throwback') {
                        $charts = Chart::where('dated', $chartDate)
                            ->whereNull('deleted_at')
                            ->where('local', 0)
                            ->where('daily', '=', 1)
                            ->where('throwback', '=', 1)
                            ->where('is_posted', '=', 1)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();

                        foreach ($charts as $chart) {
                            $chart->total_votes = (floatval($chart->online_votes) + floatval($chart->phone_votes) + floatval($chart->social_votes)) ?? 0;
                        }

                        return view('_cms.system-views.music.daily.table', compact('charts'));
                    }

                    if($action === 'post') {
                        $currentDate = date('Y-m-d');

                        if ($throwback) {
                            Chart::where('dated', $request['dated'])
                            ->whereNull('deleted_at')
                            ->where('local', 0)
                            ->where('daily', '=', 1)
                            ->where('throwback', '=', 1)
                            ->where('is_posted', 0)
                            ->where('location', $this->getStationCode())
                            ->update(['is_posted' => 1]);

                            return response()->json(['status' => 'success', 'message' => 'The throwback charts has been posted'], 202);
                        }

                        // $dailyCharts = Chart::query()
                        //     ->where('dated', $currentDate)
                        //     ->where('daily', '=', 1)
                        //     ->where('throwback', '=', 0)
                        //     ->where('is_posted', '=', 1)
                        //     ->get();

                        // $songs = Song::query()
                        //     ->where('votes', '>', 0)
                        //     ->orderByDesc('votes')
                        //     ->take(5)
                        //     ->get();

                        // // If there is an existing chart by the same date, return an error to avoid duplicate entries.
                        // if (count($dailyCharts) > 0) {
                        //     return response()->json([
                        //         'status' => 'error',
                        //         'message' => 'Daily charts have already been posted for today.'
                        //     ], 400);
                        // } else {
                        //     foreach ($songs as $key => $song) {
                        //         // Create the charts based on the top 5 songs with high votes.
                        //         $date = date('Y-m-d');

                        //         $chartedSong = Chart::query()
                        //             ->where('song_id', $song->id)
                        //             ->get()
                        //             ->first();

                        //         $chart = [
                        //             'song_id' => $song->id,
                        //             'position' => $key + 1,
                        //             'last_position' => $chartedSong ? $chartedSong->position : 0,
                        //             're_entry' => 0,
                        //             'dated' => $date,
                        //             'is_dropped' => 0,
                        //             'daily' => 1,
                        //             'throwback' => 0,
                        //             'local' => 0,
                        //             'votes' => 0,
                        //             'last_results' => 0,
                        //             'phone_votes' => 0,
                        //             'social_votes' => 0,
                        //             'online_votes' => $song->votes,
                        //             'voted_at' => Carbon::now(),
                        //             'is_posted' => 1,
                        //             'location' => $this->getStationCode()
                        //         ];

                        //         Chart::create($chart);
                        //     }

                        //     return response()->json([
                        //         'status' => 'success', 
                        //         'message' => 'The daily survey has been posted'
                        //     ], 202);
                        // }

                        Chart::where('dated', $request['dated'])
                            ->whereNull('deleted_at')
                            ->where('local', 0)
                            ->where('daily', '=', 1)
                            ->where('throwback', '=', 0)
                            ->where('is_posted', 0)
                            ->where('location', $this->getStationCode())
                            ->update(['is_posted' => 1]);

                            return response()->json([
                                'status' => 'success',
                                'message' => 'The daily survey has been posted'], 
                            202);
                    }
                }

                if($action === 'official') {
                    $charts = Chart::where('dated', $chartDate)
                        ->whereNull('deleted_at')
                        ->where('local', 0)
                        ->where('daily', 0)
                        ->where('is_posted', 1)
                        ->where('location', $this->getStationCode())
                        ->where('position', '>', 0)
                        ->orderBy('position')
                        ->get();

                    return view('_cms.system-views.music._chart.charts', compact('charts'));
                }

                if($action === 'draft') {
                    $charts = Chart::where('dated', $chartDate)
                        ->whereNull('deleted_at')
                        ->where('local', 0)
                        ->where('daily', 0)
                        ->where('is_posted', 0)
                        ->where('location', $this->getStationCode())
                        ->where('position', '>', 0)
                        ->orderBy('position')
                        ->get();

                    return view('_cms.system-views.music._chart.charts', compact('charts'));
                }

                if($action === 'post') {
                    Chart::where('dated', $request['dated'])
                        ->whereNull('deleted_at')
                        ->where('local', 0)
                        ->where('daily', 0)
                        ->where('is_posted', 0)
                        ->where('location', $this->getStationCode())
                        ->update(['is_posted' => 1]);

                    return response()->json(['status' => 'success', 'message' => 'The chart has been posted']);
                }

                return response()->json(['status' => 'warning', 'message' => 'Action unknown']);
            }

            // for getting the song
            if($request['chart_id']) {
                $latestChartDate = DB::table('charts')
                    ->whereNull('deleted_at')
                    ->where('daily', 0)
                    ->where('local', 0)
                    ->where('location', $this->getStationCode())
                    ->select('dated')
                    ->Max('dated');

                $song = Chart::with('Song')
                    ->whereNull('deleted_at')
                    ->where('id', $request['chart_id'])
                    ->first();

                return response()->json(['chart' => $song, 'latestDate' => $latestChartDate]);
            }

            // for the main charts
            $latestChartDate = DB::table('charts')
                ->whereNull('deleted_at')
                ->where('daily', 0)
                ->where('local', 0)
                ->where('location', $this->getStationCode())
                ->select('dated')
                ->max('dated');

            $charts = Chart::where('dated', $latestChartDate)
                ->whereNull('deleted_at')
                ->where('local', 0)
                ->where('daily', 0)
                ->where('throwback', 0)
                ->where('position', '>', 0)
                ->where('location', $this->getStationCode())
                ->orderBy('position')
                ->get();

            return view('_cms.system-views.music._chart.charts', compact('charts'));
        }

        $chart_type = "";

        // for the main charts obviously
        $latestChartDate = DB::table('charts')
            ->whereNull('deleted_at')
            ->where('daily', 0)
            ->where('local', 0)
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->max('dated');

        if($latestChartDate === null) {
            $latestChartDate = DB::table('charts')
                ->whereNull('deleted_at')
                ->where('daily', 0)
                ->where('local', 0)
                ->where('location', $this->getStationCode())
                ->select('dated')
                ->Max('dated');
        }

        $chart = Chart::where('dated', $latestChartDate)
            ->whereNull('deleted_at')
            ->where('position', '>', 0)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get();

        if($chart->first()->is_posted === 0 || count($chart) === 0) {
            $chart_type = 'Draft';
        }

        if($chart->first()->is_posted === 1) {
            $chart_type = 'Official';
        }

        $level = Auth::user()->Employee->Designation->level;

        if ($level === 1 || $level === 2 || $level === 6|| $level === 7) {
            return view('_cms.system-views.music._chart.index', compact('chart', 'latestChartDate', 'chart_type'));
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function create(Request $request)
    {
        // for chart dates
        if($request->ajax())
        {
            $dated = DB::table('charts')
                ->whereNull('deleted_at')
                ->where('daily', 0)
                ->where('local', 0)
                ->where('location', $this->getStationCode())
                ->select('dated')
                ->groupBy('dated')
                ->orderBy('dated', 'desc')
                ->get();

            $options = "";

            foreach ($dated as $dates) {
                $options.= '<option value="'.$dates->dated.'">'.date('M d Y', strtotime($dates->dated)).'</option>';
            }

            $latestChart = $dated->first()->dated;

            return response()->json([
                'dates' => $options, 
                'latest' => $latestChart
            ]);
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            if($request['daily'] === '1') {
                if ($request['type'] === 'dailyChart') {
                    $verifyCharts = Chart::query()
                        ->whereNull('deleted_at')
                        ->where('daily', 1)
                        ->where('local', 0)
                        ->where('throwback', 0)
                        ->where('is_posted', 1)
                        ->where('dated', $request['dated'])
                        ->count();

                    if($verifyCharts >= 5) {
                        return response()->json([
                            'status' => 'error', 
                            'message' => "The daily survey charts can only be five songs per day."
                        ], 400);
                    }

                    $request->merge([
                        're_entry' => 0,
                        'is_dropped' => 0,
                        'location' => $this->getStationCode(),
                        'is_posted' => 1
                    ]);

                    Chart::create($request->all());

                    return response()->json([
                        'status' => 'success', 
                        'message' => 'A new daily chart song has been added.'
                    ], 201);
                }

                // If the current request is adding songs to the TDS playlist
                if ($request['type'] === 'song') {
                    $request->merge([
                        'song_id'    => $request['song_id'],
                        'daily'      => 1,
                        'throwback'  => 0,
                        'local'      => 0,
                        'position'   => 0,
                        'is_posted'  => 0,
                        'is_dropped' => 0,
                        're_entry' => 0,
                        'dated'      => date('Y-m-d'),
                    ]);

                    $message = "A new playlist song has been uploaded.";
                } 

                // If the current request is intended for throwback songs
                elseif ($request['throwback'] === '1') {
                    $request->merge([
                        'daily' => 1,
                        'throwback'  => 1,
                        'local'      => 0,
                        'is_posted'  => 0,
                        're_entry' => 0,
                        'dated'      => date('Y-m-d'),
                    ]);

                    $message = "A new throwback song has been uploaded.";
                } 
                else {
                    $message = "A new charted song has been uploaded.";
                }

                Chart::create($request->all());

                return response()->json([
                    'status'  => 'success',
                    'message' => $message
                ], 201);
            }
        }

        $level = Auth::user()->Employee->Designation->level;

        $validator = Validator::make($request->all(), [
            'song_id' => 'required',
            'position' => 'required',
            'dated' => 'required'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all());
        }

        $request->merge([
            'last_position' => 0,
            're_entry'      => 0,
            'is_dropped'    => 0,
            'location'      => $this->getStationCode(),
        ]);
        
        Chart::create($request->all());

        Song::where('id', $request['song_id'])
            ->update(['is_charted' => 1]);

        if ($request['local'] || in_array($level, [1, 2, 5, 6, 7])) {
            return response()->json([
                'status'  => 'success',
                'message' => "A new charted song has been uploaded"
            ]);
        }

        Session::flash('error', 'Restricted Access!');
        return redirect()->back();
    }

    public function show($id)
    {
        // Obsolete
    }

    public function edit($id)
    {
        // Obsolete
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position' => 'required',
            'dated' => 'required'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all());
        }

        $chart = Chart::findOrfail($id);
        $chart->update($request->all());

        Session::flash('success', 'A charted song position has been updated');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $chart = Chart::findOrfail($id);

        $chart->delete();

        Session::flash('success', 'A charted song has been successfully deleted!');
        return redirect()->back();
    }

    public function selectSongInTable(Request $request) {
        $song = Song::where('id', $request['song_id'])->first();

        return response()->json($song);
    }

    public function NewChart(Request $request)
    {
        $this->validate($request, [
            'position' => 'required',
            'dated' => 'required'
        ]);

        $request['last_position'] = $request['position'];

        $request['re_entry'] = 0;
        $request['is_dropped'] = 0;
        $request['result'] = 0;
        $request['last_results'] = 0;

        $request['location'] = $this->getStationCode();

        $chart = new Chart($request->all());
        $chart->save();

        $option = "";

        // Get the latest chart date
        $dated = DB::table('charts')
            ->whereNull('deleted_at')
            ->where('daily', 0)
            ->select('dated')
            ->groupBy('dated')
            ->orderBy('dated', 'desc')
            ->get();

        foreach ($dated as $chartDates) {
            $option.= '<option value="'.$chartDates->dated.'">'.date('M d Y', strtotime($chartDates->dated)).'</option>';
        }

        return response()->json([
            'dated' => $option
        ], 200);
    }

    public function dropChart(Request $request)
    {
        $charted = Chart::findOrfail($request['chart_id']);

        $request['position'] = '0';
        $request['last_position'] = $charted['position'];
        $request['dated'] = $charted['dated'];
        $request['song_id'] = $charted['song_id'];
        $request['re_entry'] = '0';
        $request['is_dropped'] = '1';

        $chart = new Chart($request->all());
        $chart->save();

        Session::flash('success', 'A charted song has been dropped from the charts');
        return redirect()->route('charts.index');
    }

    public function filter(Request $request)
    {
        if ($request->ajax()) {
            if($request->has('data-payload')) {
                $payload = $request['data-payload'];

                if($payload === 'southsides') {
                    $chart = Chart::where('dated', $request['date'])
                        ->whereNull('deleted_at')
                        ->where('local', 1)
                        ->where('daily', 0)
                        ->where('is_posted', 1)
                        ->where('location', $this->getStationCode())
                        ->where('position', '>', 0)
                        ->orderBy('dated', 'desc')
                        ->orderBy('position')
                        ->get();

                    return view('_cms.system-views.music._chart.charts', compact('chart'));
                }
            }

            if($request->has('chart_type')) {
                $chart_type = $request['chart_type'];
                $is_daily = $request->get('daily');
                $chart_date = $request->get('date');

                // For The Daily Survey Top 5 Charts
                if ($is_daily) {
                    if($chart_type === 'official') {
                        $charts = Chart::where('dated', $chart_date)
                            ->whereNull('deleted_at')
                            ->where('local', 0)
                            ->where('daily', '=', 1)
                            ->where('throwback', '=', 0)
                            ->where('is_posted', 1)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();

                        return view('_cms.system-views.music.daily.table', compact('charts'));
                    }

                    if($chart_type === 'draft') {
                        $songs = Song::query()
                            ->orderBy('votes', 'desc')
                            ->get()
                            ->take(5);
                        
                        foreach ($songs as $key => $song) {
                            $song->position = $key + 1;
                        }

                        return view('_cms.system-views.music.daily.songs-table', compact('songs'));
                    }

                    if($chart_type === 'throwback') {
                        $charts = Chart::where('dated', $chart_date)
                            ->whereNull('deleted_at')
                            ->where('local', 0)
                            ->where('daily', '=', 1)
                            ->where('throwback', '=', 1)
                            ->where('is_posted', '=', 0)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();

                        return view('_cms.system-views.music.daily.table', compact('charts'));
                    }

                    return response()->json(['status' => 'warning', 'message' => 'Action unknown'], 400);
                }

                if($chart_type === 'voting') {
                    $chart = Chart::where('dated', $request['date'])
                        ->whereNull('deleted_at')
                        ->where('local', 0)
                        ->where('daily', 0)
                        ->where('location', $this->getStationCode())
                        ->orderBy('position')
                        ->get();

                    $vote = Vote::with('Chart.Song','Employee')
                        ->get();

                    return view('_cms.system-views.music._chart.charts_voting', compact('chart', 'vote'));
                }

                if($chart_type === 'official') {
                    $chart = Chart::where('dated', $request['date'])
                        ->whereNull('deleted_at')
                        ->where('local', 0)
                        ->where('daily', 0)
                        ->where('is_posted', 1)
                        ->where('location', $this->getStationCode())
                        ->where('position', '>', 0)
                        ->orderBy('position')
                        ->get();

                    return view('_cms.system-views.music._chart.charts', compact('chart'));
                }

                if($chart_type === 'draft') {
                    $chart = Chart::where('dated', $request['date'])
                        ->whereNull('deleted_at')
                        ->where('local', 0)
                        ->where('daily', 0)
                        ->where('is_posted', 0)
                        ->where('location', $this->getStationCode())
                        ->where('position', '>', 0)
                        ->orderBy('position')
                        ->get();

                    return view('_cms.system-views.music._chart.charts', compact('chart'));
                }
            }

            $charts = Chart::where('dated', $request['date'])
                ->whereNull('deleted_at')
                ->where('local', 0)
                ->where('daily', 0)
                ->where('is_posted', 1)
                ->where('location', $this->getStationCode())
                ->where('position', '>', 0)
                ->orderBy('dated', 'desc')
                ->orderBy('position')
                ->get();

            return view('_cms.system-views.music._chart.charts', compact('charts'));
        }

        $chart = Chart::where('dated',$request['dates'])
            ->where('daily', 0)
            ->where('position', '>', 0)
            ->orderBy('dated','desc')
            ->orderBy('position')
            ->paginate(45);

        $dated = DB::table('charts')
            ->where('daily', 0)
            ->select('dated')
            ->groupBy('dated')
            ->orderBy('dated','desc')
            ->get();

        $drop = Chart::where('dated', $request['dates'])
            ->where('daily', 0)
            ->where('is_dropped', 1)
            ->orderBy('dated','desc')
            ->orderBy('position')
            ->get();

        $data = array('drop' => $drop);

        $level = Auth::user()->Employee->Designation->level;
        if ($level === 1 || $level === 2 || $level === 6 || $level === 7)
        {
            return view('_cms.system-views.music._chart.index',compact('chart','dated', 'data'));
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function dailyCharts(Request $request) {
        $options = '';
        $charts = '';
        $chart_type = '';
        $is_posted = $request->get('is_posted') ?? 0;
        $dated = $request->get('dated');
        $now = Carbon::now();

        $latestSurveyDate = Chart::query()
            ->select('dated')
            ->where('daily', 1)
            ->where('local', 0)
            ->where('throwback', 0)
            ->whereNull('deleted_at')
            ->max('dated');

        $latestSurveyThrowbackDate = Chart::query()
            ->select('dated')
            ->where('daily', 1)
            ->where('local', 0)
            ->where('throwback', 1)
            ->whereNull('deleted_at')
            ->max('dated');
        
        $surveyDates = Chart::query()
            ->where('daily', 1)
            ->where('local', 0)
            ->where('throwback', 0)
            ->select('dated')
            ->groupBy('dated')
            ->orderBy('dated','desc')
            ->get();

        // Get the date based from the request or load the latest survey date. 
        $dailyChartQuery = Chart::query()
            ->with('Song.Album.Artist')
            ->where('daily', 1)
            ->where('local', 0)
            ->where('throwback', 0)
            ->where('dated', $dated)
            ->whereNull('deleted_at')
            ->orderBy('position')
            ->get();

        $chartedSongCount = count($dailyChartQuery);
        
        if ($chartedSongCount <= 0) {
            $dailyChartQuery = Chart::query()
                ->with('Song.Album.Artist')
                ->where('daily', 1)
                ->where('local', 0)
                ->where('throwback', 0)
                ->where('dated', $latestSurveyDate)
                ->whereNull('deleted_at')
                ->orderBy('position')
                ->get();
        }

        if($request->ajax()) {
            $daily = $request->get('daily');
            $forSurveyDates = $request->get('surveyDates');
            $isPosted = $request->get('isPosted');
            $datatable = $request->get('datatable');
            $chartType = $request->get('chartType');
            $dated = $request->get('dated') ?? $latestSurveyDate;
            $date = $request->get('date') ?? $latestSurveyThrowbackDate;
            $type = $request->get('type');
            $is_posted = $type === 'official' ? 1 : 0;
            $throwback = $request->get('throwback');

            $charts = Chart::query()
                ->with('Song.Album.Artist')
                ->where('daily', 1)
                ->where('local', 0)
                ->where('throwback', 0)
                ->where('dated', $dated)
                ->where('is_posted', '=', $is_posted)
                ->whereNull('deleted_at')
                ->orderBy('position')
                ->get();

            $songs = Song::query()
                ->with('Album.Artist')
                ->orderBy('votes', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->take(5);

            if (isset($daily) && $forSurveyDates) {
                if (count($dailyChartQuery) > 0) {
                    if ($dailyChartQuery->first()->dated !== date('Y-m-d')) {
                        $isPosted = $dailyChartQuery->first()->is_posted;
                    }
                } else {
                    $isPosted = 0;
                }
                
                if ($throwback === 'true') {
                    $surveyDates = Chart::query()
                        ->where('daily', 1)
                        ->where('local', 0)
                        ->where('throwback', 1)
                        ->select('dated')
                        ->whereNull('deleted_at')
                        ->groupBy('dated')
                        ->orderBy('dated','desc')
                        ->get();
                }

                foreach ($surveyDates as $date) {
                    $options.= "<option value='".$date->dated."' data-value='daily'>".date('F d, Y', strtotime($date->dated))."</option>";
                }

                return response()->json([
                    'surveyDates' => $options,
                    'chartCount' => $chartedSongCount,
                    'isPosted' => $isPosted,
                ]);
            }

            // daily.blade.php when edit button modal has been clicked get the id to be set in update_song_id
            if($request->has('song_id')) {
                $id = Chart::with('Song.Album.Artist')->where('id', $request['song_id'])->first();

                return response()
                    ->json($id);
            }

            if (isset($daily)) {
                foreach ($dailyChartQuery as $chart) {
                    $options = '' .
                        '<div class="btn-group">' .
                            '<a href="#new-chart" data-toggle="modal" data-position="'.$chart->position.'" data-value="'.$chart->id.'" data-date="'.$chart->dated.'" class="btn btn-outline-dark"><i class="fa fa-edit"></i></a>' .
                            '<a href="#update-chart" data-toggle="modal" data-position="'.$chart->position.'" data-value="'.$chart->id.'" data-date="'.$chart->dated.'" class="btn btn-outline-dark"><i class="fa fa-search"></i></a>' .
                        '</div>';
                }

                foreach ($dailyChartQuery as $chart) {
                    $existingTally = Tally::query()
                        ->where('chart_id', $chart->id)
                        ->whereDate('dated', $chart->dated)
                        ->exists();

                    if ($existingTally) {
                        // Use stored tally result
                        $chart->total_votes = $chart->LatestDailyTally->result ?? 0;
                    } else {
                        // Use calculated total votes
                        $chart->total_votes = floatval($chart->online_votes) + floatval($chart->phone_votes) + floatval($chart->social_votes);
                    }

                    $chart->dated = '<div class="text-danger font-weight-bold">'.date('M d Y', strtotime($chart->dated)).'</div>';
                    $chart->options = $options;
                }

                $charts = $dailyChartQuery;

                if (isset($datatable)) {
                    // Commented due to digital direction // 09-15-2025
                    // if ($type === 'draft') {
                    //     foreach ($songs as $key => $song) {
                    //         $song->position = $key + 1;
                    //         $song->votes = $song->votes ?? 0;
                    //     }

                    //     return response()->json([
                    //         'songs' => $songs
                    //     ]);
                    // }

                    $charts = Chart::query()
                        ->with('Song.Album.Artist')
                        ->where('daily', 1)
                        ->where('local', 0)
                        ->where('throwback',0)
                        ->where('is_posted', 1)
                        ->where('dated', $dated)
                        ->orderBy('position')
                        ->get();

                    if ($type === 'draft') {
                        $charts = Chart::query()
                        ->with('Song.Album.Artist')
                        ->where('daily', 1)
                        ->where('local', 0)
                        ->where('throwback',0)
                        ->orderBy('position')
                        ->get();
                    }

                    foreach ($charts as $chart) {
                        $existingTally = Tally::query()
                            ->where('chart_id', $chart->id)
                            ->whereDate('dated', $chart->dated)
                            ->exists();

                        if ($existingTally) {
                            // Use stored tally result
                            $chart->total_votes = $chart->LatestDailyTally->result ?? 0;
                        } else {
                            // Use calculated total votes
                            $chart->total_votes = floatval($chart->online_votes) + floatval($chart->phone_votes) + floatval($chart->social_votes);
                        }

                        // $chart->total_votes = (floatval($chart->online_votes) + floatval($chart->phone_votes) + floatval($chart->social_votes)) : $chart->Tally->latest('dated')->result;
                        $chart->options = '' .
                            '<div class="btn-group">' .
                                '<a href="#new-chart" data-toggle="modal" data-position="'.$chart->position.'" data-value="'.$chart->id.'" data-date="'.$chart->dated.'" class="btn btn-outline-dark"><i class="fa fa-edit"></i></a>' .
                                '<a href="#update-chart" data-toggle="modal" data-position="'.$chart->position.'" data-value="'.$chart->id.'" data-date="'.$chart->dated.'" class="btn btn-outline-dark"><i class="fa fa-search"></i></a>' .
                            '</div>';
                    }

                    if ($type === 'throwback') {
                        $throwbacks = Chart::query()
                            ->with('Song.Album.Artist')
                            ->where('daily', 1)
                            ->where('local', 0)
                            ->where('throwback',1)
                            ->where('is_posted', 1)
                            ->where('dated', $date)
                            ->orderBy('position')
                            ->get();

                        foreach ($throwbacks as $chart) {
                            $existingTally = Tally::query()
                                ->where('chart_id', $chart->id)
                                ->whereDate('dated', $chart->dated)
                                ->exists();

                            if ($existingTally) {
                                // Use stored tally result
                                $chart->total_votes = $chart->LatestDailyTally->result ?? 0;
                            } else {
                                // Use calculated total votes
                                $chart->total_votes = floatval($chart->online_votes) + floatval($chart->phone_votes) + floatval($chart->social_votes);
                            }

                            // $chart->total_votes = (floatval($chart->online_votes) + floatval($chart->phone_votes) + floatval($chart->social_votes)) : $chart->Tally->latest('dated')->result;
                            $chart->options = '' .
                                '<div class="btn-group">' .
                                    '<a href="#new-chart" data-toggle="modal" data-position="'.$chart->position.'" data-value="'.$chart->id.'" data-date="'.$chart->dated.'" class="btn btn-outline-dark"><i class="fa fa-edit"></i></a>' .
                                    '<a href="#update-chart" data-toggle="modal" data-position="'.$chart->position.'" data-value="'.$chart->id.'" data-date="'.$chart->dated.'" class="btn btn-outline-dark"><i class="fa fa-search"></i></a>' .
                                '</div>';
                        }

                        return response()->json([
                            'charts' => $throwbacks
                        ]);
                    }

                    return response()->json([
                        'charts' => $charts
                    ]);
                }

                // if ($chartType === 'draft') {
                //     return view('_cms.system-views.music.daily.songs-table', compact('songs'));
                // }

                return view('_cms.system-views.music.daily.table', compact('charts'));
            }

            $songs = Song::with('Album.Artist')
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'dailyCharts' => $charts, 
                'songs' => $songs, 
                'surveyDates' => $options
            ]);
        }

        // Survey dates for determining what will be displayed.
        $surveyDates = DB::table('charts')
                ->where('daily', 1)
                ->where('local', 0)
                ->where('location', $this->getStationCode())
                ->whereNull('deleted_at')
                ->orderBy('dated','desc')
                ->get();

        $jock_id = Jock::where('employee_id', Auth::user()->Employee->id)->pluck('id')->first();
        $jock_name = Jock::where('employee_id', Auth::user()->Employee->id)->pluck('name');
        $country = $this->getCountries();

        $artists = Artist::whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $genres = Genre::whereNull('deleted_at')->orderBy('name')->get();

        $show = DB::table('jock_show')
            ->join('jocks', 'jock_show.jock_id', '=', 'jocks.id')
            ->join('shows', 'jock_show.show_id', '=', 'shows.id')
            ->select('title', 'shows.id')
            ->where('jock_show.jock_id', $jock_id)
            ->get();

        if (count($surveyDates) > 0) {
            if ($surveyDates->first()->is_posted === 1) {
                $chart_type = 'Official';
            } 
            else {
                $chart_type = 'Draft';
            }
        } else {
            $chart_type = 'Draft';
        }

        $data = [
            'jock_id' => $jock_id,
            'jock_name' => $jock_name,
            'show' => $show,
            'country' => $country,
            'genres' => $genres,
            'artists' => $artists,
            'latestSurveyDate' => $latestSurveyDate,
            'chart_type' => $chart_type
        ];

        return view('_cms.system-views.music.daily.index', compact('data'));
    }

    public function removeDailyChart(Request $request) {
        if($request->ajax()) {
            $chartedSong = Chart::findOrFail($request['delete_song_id']);

            $chartedSong->delete();

            return response()->json(['status' => 'success', 'message' => 'Charted song has been removed from the list'], 200);
        }

        return redirect()
            ->back()
            ->withErrors('No direct script access!');
    }
 }
