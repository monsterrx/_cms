<?php namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Gimmick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GimikBoardController extends Controller {

	public function index()
	{
		$gimikboards = Gimmick::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->where('location', $this->getStationCode())
            ->get();

		$school = School::whereNull('deleted_at')
            ->orderBy('name')
            ->get();

		return view('_cms.system-views.education.gimikboards.index',compact('gimikboards','school'));
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'title' => 'required',
            'start_date' => 'required',
            'description' => 'required',
            'school_id' => 'required',
            'image' => 'required|image|file|max:2048',
        ]);

		if($validator->passes()) {
            $img = $request->file('image'); //file for School Seal
            $path = 'images/schools';
            $request['location'] = $this->getStationCode();

            $gimikboard = new Gimmick($request->all());

            if ($request['end_date']) {
                if($request['end_date'] <= $request['start_date']){
                    return redirect()->back()->withErrors(['Date is not valid.']);
                }
            }

            if($img) {
                $gimikboard['image'] = $this->storePhoto($request, $path, 'schools', false);
            } else {
                $gimikboard['image'] = 'default.png';
            }

            $gimikboard->save();

            Session::flash('success', 'A new gimikboard has been posted!');
            return redirect()->route('gimikboards.show', Gimmick::with('School')->latest()->whereNull('deleted_at')->first()->id);
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id)
	{
		$gimikboard = Gimmick::with('School')->findOrfail($id);
		$school = School::whereNull('deleted_at')->orderBy('name')->get();

		return view('_cms.system-views.education.gimikboards.show',compact('gimikboard','school'));
	}

	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
            'title' => 'required',
            'start_date' => 'required',
            'description' => 'required',
            'school_id' => 'required',
            'image' => 'image|file|max:2048',
        ]);

		if($validator->passes()) {
            $img = $request->file('image'); // file for School Seal
            $path = 'images/schools';

            $gimikboard = Gimmick::findOrfail($id);

            if($img) {
                $gimikboard['image'] = $this->storePhoto($request, $path, 'schools', false);
                $gimikboard->save();

                Session::flash('success', 'A gimikboard has been updated!');
                return redirect()->route('gimikboards.show', $id);
            }

            $gimikboard->update($request->except('image'));

            Session::flash('success', 'A gimikboard has been updated!');
            return redirect()->route('gimikboards.show', $id);
        }

		return redirect()->back()->withErrors($validator->errors()->all());
    }

	public function destroy($id)
	{
		$gimikboard = Gimmick::findOrfail($id);

		$gimikboard->delete();

		Session::flash('success', 'A gimikboard has been removed!');
		return redirect()->route('gimikboards.index');
	}
}
