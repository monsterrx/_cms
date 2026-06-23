<?php namespace App\Http\Controllers;

use App\Models\Contestant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ContestantController extends Controller {

	public function index()
	{
        $contestant = Contestant::with('Contest')
            ->whereNull('deleted_at')
            ->get();

		return view('_cms.system-views.monsterGiveaway.contestants.index', compact( 'contestant'));
	}

	public function create()
	{
		//
	}

	public function store()
	{
		//
	}

	public function show($id)
	{
	    $contestant = Contestant::findOrFail($id);

	    return view('_cms.system-views.monsterGiveaway.contestants.show', compact('contestant'));
	}

	public function edit($id)
	{
		//
	}

	public function update($id)
	{
		return redirect()->back()->withErrors('Feature is not ready yet');
	}

	public function destroy($id)
	{
        $contestant = Contestant::findOrFail($id);

        $contestant->delete();

        Session::flash('success', 'A contestant has been deleted');
        return redirect()->route('contestants.index');
	}
}
