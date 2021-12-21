<?php namespace App\Http\Controllers;

use App\Models\Jock;
use App\Models\Show;
use App\Models\Timeslot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Time;

class TimeslotController extends Controller {

	public function index(Request $request)
	{
		$day = date('l');

		if($request->ajax()) {
            if($request->has('day')) {
                return $day;
            }

            $timeslots = Timeslot::with('Show')
                ->whereNull('deleted_at')
                ->where('day', $day)
                ->where('location', $this->getStationCode())
                ->orderBy('start')
                ->get();

            return view('_cms.system-views.programs.timeslot.showTable', compact('timeslots'));
        }

        $jocks = Jock::with('Employee')->whereHas('Employee', function(Builder $query) {
            $query->where('location', $this->getStationCode());
        })->whereNull('deleted_at')
            ->where('is_active', '=', 1)
            ->orderByDesc('name')
            ->get();

        $shows = Show::with('Jock')
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('title')
            ->get();

		$timeslots = Timeslot::with('Show')
            ->whereNull('deleted_at')
            ->where('day', $day)
            ->where('location', $this->getStationCode())
            ->orderBy('start')
            ->get();

		// Getting current user's level
		$level = Auth::user()->Employee->Designation->level;
		if ($level === '1' || $level === '2') {
			return view('_cms.system-views.programs.timeslot.index', compact('shows', 'jocks', 'timeslots', 'day'));
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
		$time = Timeslot::findOrfail($id);

		$time->delete();

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

            return view('_cms.system-views.programs.timeslot.showTable', compact('timeslots'));
		}

        return redirect()->route('timeslots.index')->withErrors(trans('response.restricted'));
	}
}
