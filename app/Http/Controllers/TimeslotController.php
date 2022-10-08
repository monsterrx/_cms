<?php namespace App\Http\Controllers;

use App\Models\Jock;
use App\Models\Show;
use App\Models\Timeslot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Time;

class TimeslotController extends Controller {

	public function index(Request $request)
	{
		$day = date('l');
        $station = $this->getStationCode();

        $jocks = Jock::with('Employee')->whereHas('Employee', function(Builder $query) {
            $query->where('location', $this->getStationCode());
        })->whereNull('deleted_at')
            ->where('is_active', '=', 1)
            ->orderBy('name')
            ->get();

		if($request->ajax()) {
            if($request->has('day')) {
                return $day;
            }

            $timeslots = Timeslot::with('Show', 'Jock')
                ->whereNull('deleted_at')
                ->where('day', $day)
                ->where('location', $this->getStationCode())
                ->orderBy('start')
                ->get();

            return view('_cms.system-views.programs.timeslot.showTable', compact('timeslots', 'jocks', 'station'));
        }

        $jocks = Jock::with('Employee')->whereHas('Employee', function(Builder $query) {
            $query->where('location', $this->getStationCode());
        })->whereNull('deleted_at')
            ->where('is_active', '=', 1)
            ->orderBy('name')
            ->get();

        $shows = Show::with('Jock')
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('title')
            ->get();

		$timeslots = Timeslot::with('Show', 'Jock')
            ->whereNull('deleted_at')
            ->where('day', $day)
            ->where('location', $this->getStationCode())
            ->orderBy('start')
            ->get();

		// Getting current user's level
		$level = Auth::user()->Employee->Designation->level;
		if ($level === 1 || $level === 2) {
			return view('_cms.system-views.programs.timeslot.index', compact('shows', 'jocks', 'timeslots', 'day', 'station'));
		}

        return redirect()->back()->withErrors(trans('response.restricted'));
    }

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'day' => 'required',
            'start' => 'required',
            'end' => 'required'
        ]);

		$request['location'] = $this->getStationCode();

		if($validator->passes()) {
            $timeslot = new Timeslot($request->all());
            $timeslot->save();

            Session::flash('success', 'Timeslot has been successfully Added');
            return redirect()->route('timeslots.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id, Request $request)
	{
		if($request->ajax()) {
            $timeslots = Timeslot::with('Show', 'Jock')->findOrfail($id);

            return response()->json($timeslots);
        }

		return response()->json(['message' => 'Bad request'], 400);
    }

	public function update($id, Request $request)
	{
		if($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'show_id' => 'required',
                'day' => 'required',
                'start' => 'required',
                'end' => 'required'
            ]);

            if($validator->passes()) {
                $timeslot = Timeslot::findOrfail($id);
                $timeslot->update($request->all());

                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'erorr', 'message' => $validator->errors()->all()], 403);
        }

        return redirect()->back()->withErrors('No direct script access!');
	}

	public function destroy($id)
	{
		$timeslot = Timeslot::with('Show', 'Jock')->findOrfail($id);
		$timeslot->delete();

		return response()->json(['status' => 'success']);
	}

	public function selectDay(Request $request)
    {
        $timeslots = Timeslot::with('Show')
            ->has('Show')
            ->whereNull('deleted_at')
            ->where('day', $request['day'])
            ->where('location', $this->getStationCode())
            ->orderBy('start')
            ->get();

        $jocks = Jock::with('Employee')->whereHas('Employee', function(Builder $query) {
            $query->where('location', $this->getStationCode());
        })->whereNull('deleted_at')
            ->where('is_active', '=', 1)
            ->orderBy('name')
            ->get();

        $station = $this->getStationCode();

		if($request->ajax()) {
            if($request['type'] === 'jock') {
                $timeslots = Timeslot::with('Jock')
                    ->has('Jock')
                    ->whereNull('deleted_at')
                    ->where('day', $request['day'])
                    ->where('location', $this->getStationCode())
                    ->orderBy('start')
                    ->get();

                return view('_cms.system-views.programs.timeslot.jockTable', compact('timeslots'));
            }

            return view('_cms.system-views.programs.timeslot.showTable', compact('timeslots', 'jocks', 'station'));
		}

        return redirect()->route('timeslots.index')->withErrors(trans('response.restricted'));
	}

    public function addJock($timeslot_id, $jock_id) {
        $timeslot = Timeslot::with('Show', 'Jock')->findOrFail($timeslot_id);

        $count = DB::table('jock_timeslot')
            ->where('timeslot_id', $timeslot_id)
            ->where('jock_id', $jock_id)
            ->count();

        if ($count > 0) {
            return redirect()->back()->withErrors(['The jock is already on the timeslot']);
        }

        $timeslot->Jock()->attach($jock_id);

        return redirect()->back()->with('success', 'Jock has been added to the show\'s timeslot');
    }

    public function removeJock($timeslot_id, $jock_id) {
        $timeslot = Timeslot::with('Show', 'Jock')->findOrFail($timeslot_id);

        $timeslot->Jock()->detach($jock_id);

        return redirect()->back()->with('success', 'Jock has been removed to the show\'s timeslot');
    }
}
