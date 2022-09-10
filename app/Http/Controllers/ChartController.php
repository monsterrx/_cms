<?php namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Chart;
use App\Models\Genre;
use App\Models\Jock;
use App\Models\Song;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller {

    public function index(Request $request)
    {
        if($request->ajax())
        {
            // switching the charts
            if($request['action']) {
                $action = $request['action'];

                if($request->has('data-local')) {
                    $type = $request['data-local'];

                    if($action === 'official') {
                        $chart = Chart::where('dated', $request['date'])
                            ->whereNull('deleted_at')
                            ->where('local', '=', 1)
                            ->where('is_posted', 1)
                            ->where('location', $this->getStationCode())
                            ->where('position', '>', 0)
                            ->orderBy('position')
                            ->get();

                        return view('_cms.system-views.music._chart.charts', compact('chart'));
                    }

                    if($action === 'draft') {
                        $chart = Chart::where('dated', $request['date'])
                            ->whereNull('deleted_at')
                            ->where('local', '=', 1)
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
                            ->where('local', '=', 1)
                            ->where('is_posted', 0)
                            ->where('location', $this->getStationCode())
                            ->update(['is_posted' => 1]);

                        return response()->json(['status' => 'success', 'message' => 'The chart has been posted']);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'Unknown chart']);
                    }
                }

                if($action === 'official') {
                    $chart = Chart::where('dated', $request['date'])
                        ->whereNull('deleted_at')
                        ->where('local', 0)
                        ->where('is_posted', 1)
                        ->where('location', $this->getStationCode())
                        ->where('position', '>', 0)
                        ->orderBy('position')
                        ->get();

                    return view('_cms.system-views.music._chart.charts', compact('chart'));
                }

                if($action === 'draft') {
                    $chart = Chart::where('dated', $request['date'])
                        ->whereNull('deleted_at')
                        ->where('local', 0)
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

            // for the main charts obviously
            $latestChartDate = DB::table('charts')
                ->whereNull('deleted_at')
                ->where('daily', 0)
                ->where('local', 0)
                ->where('location', $this->getStationCode())
                ->select('dated')
                ->Max('dated');

            $chart = Chart::where('dated', $latestChartDate)
                ->whereNull('deleted_at')
                ->where('local', 0)
                ->where('position', '>', 0)
                ->where('location', $this->getStationCode())
                ->orderBy('position')
                ->get();

            return view('_cms.system-views.music._chart.charts', compact('chart'));
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

        if($chart->first()->is_posted === 0) {
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

            return response()->json(['dates' => $options, 'latest' => $latestChart]);
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            if($request['daily'] === "1") {
                $verifyCharts = Chart::whereNull('deleted_at')->where('daily', '1')->where('dated', $request['dated'])->count();

                if($verifyCharts === 5) {
                    return response()->json(['status' => 'error', 'message' => "The Daily Survey Charts can only be five songs per day."]);
                }

                $request['location'] = $this->getStationCode();
                Chart::create($request->all());

                return response()->json(['status' => 'success', 'message' => "A new charted song has been uploaded"]);
            }
        }

        $level = Auth::user()->Employee->Designation->level;

        $validator = Validator::make($request->all(), [
            'song_id' => 'required',
            'position' => 'required',
            'dated' => 'required'
        ]);

        if($validator->passes()) {
            $request['last_position'] = '0';
            $request['re_entry'] = '0';
            $request['is_dropped'] = '0';
            $request['location'] = $this->getStationCode();

            $chart = new Chart($request->all());
            $chart->save();

            $charted = Song::find($request['song_id']);
            $charted['is_charted'] = '1';
            $charted->update();

            if ($request['local']) {
                return response()->json(['status' => 'success', 'message' => "A new charted song has been uploaded"]);
            }

            if ($level === 5) {
                return response()->json(['status' => 'success', 'message' => "A new charted song has been uploaded"]);
            }

            if ($level === 1 || $level === 2 || $level === 6 || $level === 7) {
                return response()->json(['status' => 'success', 'message' => "A new charted song has been uploaded"]);
            }

            Session::flash('error', 'Restricted Access!');
            return redirect()->back();
        }

        return redirect()->back()->withErrors($validator->errors()->all());
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

        if($validator->passes()) {
            $chart = Chart::findOrfail($id);
            $chart->update($request->all());

            Session::flash('success', 'A charted song position has been updated');
            return redirect()->back();
        }

        return redirect()->back()->withErrors($validator->errors()->all());
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

        return response()->json(['dated' => $option], 200);
    }

    public function DropChart(Request $request)
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
            if($request['data-payload']) {
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

            if($request['chart_type']) {
                $chart_type = $request['chart_type'];

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

            $chart = Chart::where('dated', $request['date'])
                ->whereNull('deleted_at')
                ->where('local', 0)
                ->where('daily', 0)
                ->where('is_posted', 1)
                ->where('location', $this->getStationCode())
                ->where('position', '>', 0)
                ->orderBy('dated', 'desc')
                ->orderBy('position')
                ->get();

            return view('_cms.system-views.music._chart.charts', compact('chart'));
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
        $options = "";
        $charts = "";

        if($request->ajax())
        {
            // daily.blade.php when edit button modal has been clicked get the id to be set in update_song_id
            if($request->has('song_id')) {
                $id = Chart::with('Song.Album.Artist')->where('id', $request['song_id'])->first();

                return response()->json($id);
            }

            // Daily Chart Select By Date
            if($request->has('daily')) {
                $dailyChartQuery = Chart::where('dated', $request['date'])
                    ->where('daily', 1)
                    ->whereNull('deleted_at')
                    ->orderBy('position')
                    ->get();

                foreach ($dailyChartQuery as $chart) {
                    $charts.= '' .
                        '<tr>'.
                        '   <td>'.$chart->position.'</td>'.
                        '   <td>'.$chart->Song->name.'</td>'.
                        '   <td>'.$chart->Song->Album->Artist->name.'</td>'.
                        '   <td>'.$chart->Song->Album->name.'</td>'.
                        '   <td style="color: red;"><strong>'.date('M d Y', strtotime($chart->dated)).'</strong></td>'.
                        '   <td>
                                <div class="btn-group">
                                    <a href="#updateChart" id="updateDailyChart" data-toggle="modal" class="btn btn-outline-dark" data-id="'.$chart->id.'">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#promptModal" id="removeDailyChart" data-toggle="modal" class="btn btn-outline-dark" data-id="'.$chart->id.'">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>'.
                        '</tr>';
                }

                return response()->json($charts);
            }

            $surveyDates = DB::table('charts')
                ->where('daily', 1)
                ->select('dated')
                ->whereNull('deleted_at')
                ->groupBy('dated')
                ->orderBy('dated','desc')
                ->get();

            $latestSurveyDate = DB::table('charts')
                ->select('dated')
                ->where('daily', 1)
                ->whereNull('deleted_at')
                ->max('dated');


            $dailyCharts = Chart::whereNull('deleted_at')
                ->where('dated', $latestSurveyDate)
                ->where('daily', 1)
                ->orderBy('position')
                ->get();

            $songs = Song::with('Album.Artist')
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->get();

            foreach ($surveyDates as $date) {
                $options.= "<option value='".$date->dated."' data-value='daily'>".date('F d, Y', strtotime($date->dated))."</option>";
            }

            foreach ($dailyCharts as $chart) {
                $charts.= '<tr>'.
                    '   <td>'.$chart->position.'</td>'.
                    '   <td>'.$chart->Song->name.'</td>'.
                    '   <td>'.$chart->Song->Album->Artist->name.'</td>'.
                    '   <td>'.$chart->Song->Album->name.'</td>'.
                    '   <td style="color: red;"><strong>'.date('M d Y', strtotime($chart->dated)).'</strong></td>'.
                    '   <td>
                            <div class="btn-group">
                                <a href="#newChart" id="newDailyChart" data-toggle="modal" class="btn btn-outline-dark" data-id="'.$chart->id.'">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="#updateChart" id="updateDailyChart" data-toggle="modal" class="btn btn-outline-dark" data-id="'.$chart->id.'">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#deleteChart" id="deleteDailyChart" data-toggle="modal" class="btn btn-outline-dark" data-id="'.$chart->id.'">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>'.
                    '</tr>';
            }

            return response()->json(['dailyCharts' => $charts, 'songs' => $songs, 'surveyDates' => $options]);
        }

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

        return view('_cms.system-views.employeeUI.Jocks.daily', compact('jock_id', 'jock_name', 'show', 'country', 'genres', 'artists'));
    }

    public function removeDailyChart(Request $request) {
        if($request->ajax()) {
            $chartedSong = Chart::findOrFail($request['delete_song_id']);

            $chartedSong->delete();

            return response()->json(['status' => 'success', 'message' => 'Charted song has been removed from the list'], 200);
        }

        return redirect()->back()->withErrors('No direct script access!');
    }
}
