<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use App\Models\Jock;
use App\Models\Tally;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function index(Request $request) {
        $latestChartDate = DB::table('charts')
            ->whereNull('deleted_at')
            ->where('daily', 0)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->Max('dated');

        $chart = Chart::where('dated', $latestChartDate)
            ->whereNull('deleted_at')
            ->where('daily', 0)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get();

        if($request->ajax()) {
            $vote = Vote::with('Chart.Song','Employee')
                ->get();

            return view('_cms.system-views.music._chart.charts_voting', compact('latestChartDate', 'chart', 'vote'));
        }

        $jock_id = Jock::where('employee_id', Auth::user()->Employee->id)
            ->pluck('id')
            ->first();

        $show = DB::table('jock_show')
            ->join('jocks', 'jock_show.jock_id', '=', 'jocks.id')
            ->join('shows', 'jock_show.show_id', '=', 'shows.id')
            ->select('title', 'shows.id')
            ->where('jock_show.jock_id', $jock_id)
            ->get();

        $userLevel = Auth::user()->Employee->Designation->level;

        if($userLevel === '5' || $userLevel === '8') {
            return view('_cms.system-views.employeeUI.Jocks.survey', compact('chart', 'show', 'jock_id', 'latestChartDate'));
        }

        return view('_cms.system-views.music._chart.voting');
    }

    public function increaseDecreaseVotes(Request $request)
    {
        $userId = Auth::user()->Employee->id;

        $chartId = $request['chart_id'];
        $device = $request['device'];

        $this->voteLog('Added votes from '.$device, $chartId, $userId);

        if($device === 'phone')
        {
            $vote = Chart::findOrFail($chartId);

            ++$vote->phone_votes;
            $vote->voted_at = date('Y-m-d H:i:s');

            $vote->save();

            return response()->json(['status' => 'success', 'message' => 'Phone call vote added'], 201);
        }

        if($device === 'socmed') {

            $vote = Chart::findOrFail($chartId);

            ++$vote->social_votes;
            $vote->voted_at = date('Y-m-d H:i:s');

            $vote->save();

            return response()->json(['status' => 'success', 'message' => 'Social Media vote added'], 201);
        }

        return response()->json(['status' => 'warning', 'message' => 'Request Unknown'], 302);
    }

    public function refreshLogsTable(Request $request) {
        if($request->ajax()) {
            $vote = Vote::join('charts', 'votes.chart_id', '=', 'charts.id')
                ->join('songs', 'charts.song_id', '=', 'songs.id')
                ->join('employees', 'votes.employee_id', '=', 'employees.id')
                ->whereNull('votes.deleted_at')
                ->select('action', 'votes.dated', 'name', DB::raw('concat(first_name," ",last_name) as name'))
                ->get();

            return response()->json($vote);
        }

        return back('403')->withErrors('Restricted Access!');
    }

    public function refreshTallyLogsTable(Request $request) {
        if($request->ajax()) {
            $tally = Tally::with('Chart.Song')
                ->get();

            return response()->json($tally);
        }

        return back('403')->withErrors('Restricted Access!');
    }

    public function refreshVotesTable(Request $request) {
        // TODO: You need to plan this one out.
    }

    public function voteLog($action, $chartId, $userId)
    {
        $date = date('Y-m-d H:i:s');

        $create = new Vote([
            'action' => $action,
            'chart_id' => $chartId,
            'employee_id' => $userId,
            'dated' => $date
        ]);

        $create->save();
    }
}
