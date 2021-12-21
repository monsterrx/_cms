<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SouthsidesController extends Controller
{
    public function index(Request $request)
    {
        // Southside sounds are the local songs in Monster Cebu
        if($request->ajax()) {
            if($request->has('load')) {
                $load = $request['load'];

                if($load === 'charts') {
                    // for the local charts obviously
                    $latestChartDate = DB::table('charts')
                        ->whereNull('deleted_at')
                        ->where('daily', 0)
                        ->where('local', 1)
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
                        ->where('local', 1)
                        ->where('position', '>', 0)
                        ->where('location', $this->getStationCode())
                        ->orderBy('position')
                        ->get();

                    return view('_cms.system-views.music._chart.charts', compact('chart'));
                }
            }

            $songs = Artist::with('Album.Song')
                ->whereNull('deleted_at')
                ->get();

            return response()->json(['artist' => $songs]);
        }

        // for the main charts obviously
        $latestChartDate = DB::table('charts')
            ->whereNull('deleted_at')
            ->where('daily', 0)
            ->where('local', 1)
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->max('dated');

        $chart = Chart::where('dated', $latestChartDate)
            ->whereNull('deleted_at')
            ->where('position', '>', 0)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get();

        return view('_cms.system-views.music._chart.southsides', compact('latestChartDate', 'chart'));
    }

    public function create(Request $request)
    {
        if($request->ajax()) {
            // for southside charts date
            $latestChartDates = DB::table('charts')
                ->whereNull('deleted_at')
                ->where('daily', 0)
                ->where('local', 1)
                ->where('location', $this->getStationCode())
                ->select('dated')
                ->groupBy('dated')
                ->orderBy('dated', 'desc')
                ->get();

            $options = "";

            foreach ($latestChartDates as $dates) {
                $options.= '<option value="'.$dates->dated.'">'.date('M d Y', strtotime($dates->dated)).'</option>';
            }

            $latestChart = $latestChartDates->first()->dated;

            return response()->json(['dates' => $options, 'latest' => $latestChart]);
        }
    }
}
