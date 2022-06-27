<?php namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller {

	public function index()
	{
		$school = School::whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->get();

		return view('_cms.system-views.education.schools.index',compact('school'));
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'image' => ['image', 'required']
        ]);

		if($validator->passes()) {
            $img = $request->file('image'); //file for School Seal
            $path = 'images/schools';
            $request['location'] = $this->getStationCode();

            if($img) {
                $imgname = date('Ymd') . '-' . mt_rand() . '.' .$img->getClientOriginalExtension();

                $img->move($path, $imgname);

                $request['seal'] = $imgname;

                $file = 'images/schools/'.$imgname;
                Storage::disk('schools')->put($imgname, file_get_contents($file));
            }

            $school = new School($request->all());
            $school->save();

            Session::flash('success', 'A new school has been added!');
            return redirect()->route('schools.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
        ]);

		if($validator->passes()) {
            $img = $request->file('image'); //file for School Seal
            $path = 'images/schools';

            $school = School::with('Student')->findOrfail($id);

            if($img) {
                $imgname = date('Ymd') . '-' . mt_rand() . '.' .$img->getClientOriginalExtension();
                $img->move($path, $imgname);

                $school['seal'] = $imgname;
                $school->update($request->all());

                $file = 'images/schools/'.$imgname;
                Storage::disk('schools')->put($imgname, file_get_contents($file));
            }

            $school->update($request->except('image'));

            Session::flash('success', 'School data updated!');
            return redirect()->route('schools.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function destroy($id)
	{
		$school = School::findOrfail($id);

		$school->delete();

		Session::flash('success', 'A school has been deleted!');
		return redirect()->route('schools.index');
	}

}
