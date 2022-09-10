<?php namespace App\Http\Controllers;

use App\Models\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class DropoutsController extends Controller {

	public function index()
	{
		$dropout = Chart::where('is_dropped', '1')
            ->where('re_entry', '0')
            ->orderBy('created_at', 'desc')
            ->get();

		$level = Auth::user()->Employee->Designation->level;
		if ($level === 1 || $level === 2) {
			return view('_cms.system-views.music._chart.dropouts', compact('dropout'));
		}

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function store(Request $request)
	{
		$this->validate($request, [
			'song_id' => 'required',
			'position' => 'required',
			'dated' => 'required'
        ]);

		$request['last_position'] = '0';
		$request['re_entry'] = '1';
		$request['is_dropped'] = '0';

		$dropout = new Chart($request->all());
		$dropout->save();

		Session::flash('success', 'A song has been added to the dropouts');
		return redirect()->route('dropouts.index');
	}

	public function show($id)
	{
		$dropout = Chart::findOrfail($id);

		// Getting current user's level
		$level = Auth::user()->Employee->Designation->level;
		if ($level === 1 || $level === 2) {
			return view('chart_pages.chart_dropouts_show', compact('dropout'));
		}

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function update($id, Request $request)
	{
		$dropout = Chart::findOrfail($id);

		$dropout->update($request->all());

		Session::flash('success', 'A song has been successfully updated');
		return redirect()->route('dropouts.index');
	}
}
