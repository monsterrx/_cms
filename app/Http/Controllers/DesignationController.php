<?php namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DesignationController extends Controller {

	public function index()
	{
		$designation = Designation::whereNull('deleted_at')
            ->orderBy('name')
            ->get();

		// Getting current user's level
		$level = Auth::user()->Employee->Designation->level;
		if ($level === 1 || $level === 2) {
			return view('_cms.system-views.employees.designations.index', compact('designation'));
		}

        return redirect()->back();
    }

	public function create()
	{
		//
	}

	public function store(Request $request)
	{
		$this->validate($request, [
		    'name' => 'required',
            'description' => 'required',
            'level' => 'required']);

		$designation = new Designation($request->all());

		$designation->save();

		Session::flash('success', 'A new designation has been successfully added');
		return redirect('designations');
	}

	public function show($id)
	{
		$designation = Designation::findOrfail($id);

		//getting current user's level
		$level = Auth::user()->Employee->Designation->level;
		if ($level === '1' || $level === '2') {
			return view('_cms.system-views.employees.designations.index', compact('designation'));
		}

        return redirect()->back();

    }

	public function edit($id)
	{
		//
	}

	public function update($id, Request $request)
	{
		$this->validate($request, [
		    'name' => 'required',
            'description' => 'required',
            'level' => 'required'
        ]);

		$designation = Designation::findOrfail($id);

		$designation->update($request->all());

		Session::flash('success', 'A designation has been successfully updated');
		return redirect()->back();
	}

	public function destroy($id)
	{
		$data = Designation::findOrfail($id);

        $data->delete();

		Session::flash('success', 'A designation has been successfully deleted');
		return redirect()->route('designations.index');
	}

}
