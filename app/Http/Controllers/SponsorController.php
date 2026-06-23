<?php namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SponsorController extends Controller {

	public function index()
	{
		$sponsor = Sponsor::whereNull('deleted_at')->get();

		return view('_cms.system-views.education.monsterScholar.sponsors.index', compact('sponsor'));
	}

	public function create()
	{
		//
	}

	public function store(Request $request)
	{
		$this->validate($request, [
		    'name' => 'required'
        ]);

		$sponsor = new Sponsor($request->all());

		$sponsor->save();

		Session::flash('success', 'New Sponsor successfully inserted!');
		return redirect()->route('sponsors.index');
	}

	public function show($id)
	{
		//
	}

	public function edit($id)
	{
		//
	}

	public function update($id, Request $request)
	{
        $sponsor = Sponsor::findOrfail($id);

		$this->validate($request, [
		    'name' => 'required'
        ]);

        $sponsor->update($request->all());

		Session::flash('success', 'A sponsor has been successfully updated');
		return redirect()->route('sponsors.index');
	}

	public function destroy($id)
	{
        $sponsor = Sponsor::findOrfail($id);

        $sponsor->delete();

		Session::flash('success', 'A sponsor has been successfully deleted');
		return redirect()->route('sponsors.index');
	}

}
