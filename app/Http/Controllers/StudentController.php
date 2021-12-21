<?php namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller {

	public function index(Request $request)
	{
	    if($request->ajax()) {
            if($request['location']) {
                $school = School::whereNull('deleted_at')->where('location', $request['location'])->get();

                $options = "";

                foreach($school as $schools) {
                    $options.= '<option value="'.$schools->id.'">'.$schools->name.'</option>';
                }

                return response()->json($options);
            }

            $student = Student::with('School')->whereNull('deleted_at')->where('location', $this->getStationCode())->get();

            foreach ($student as $students) {
                $students['name'] = $students['first_name'] . ' ' . $students['last_name'];
                $students['options'] = "<a href='#openStudentData' id='openStudentModal' student-id='".$students->id."' data-toggle='modal' class='btn btn-outline-dark'><i class='fas fa-search'></i></a>";
            }

            return response()->json($student);
        }

		$student = Student::with('School', 'Batch')->whereNull('deleted_at')->get();

		$data = array('student' => $student);

		return view('_cms.system-views.education.monsterScholar.students.index', compact('data'));
	}

	public function create()
    {
	    //
    }

	public function store(Request $request)
	{
		$this->validate($request, [
		    'school_id' => 'required|integer',
            'first_name' => 'required',
            'last_name' => 'required',
            'course' => 'required',
            'year_level' => 'required',
            'image' => ['image', 'file|size:2048']
        ]);

		$img = $request->file('image'); // file for Student Image
		$path = 'images/schools';

		$request['location'] = $this->getStationCode();

		$student = new Student($request->all());

		if($img) {
            $student['image'] = $this->storePhoto($request, $path, 'schools', false);
            $student->save();

            Session::flash('success', 'A new student has been successfully added');
            return redirect()->route('students.index');
		}

        $request['image'] = 'default.png';

        $student->save();

		Session::flash('success', 'A new student has been successfully added without an image');
		return redirect()->route('students.index');
	}

	public function show($id, Request $request)
	{
        if($request->ajax()) {
            $student = Student::with('School')->findOrFail($id);

            $schools = School::with('Student')->whereNull('deleted_at')->get();

            $student['image'] = $this->verifyPhoto($student['image'], 'schools');

            return view('_cms.system-views.education.monsterScholar.students.show', compact('student', 'schools'));
        }

        return redirect()->route('students.index')->withErrors(trans('response.restricted'));
	}

	public function edit($id)
	{
		//
	}

	public function update($id, Request $request)
	{
		try {

			$student = Student::findOrfail($id);

		} catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$this->validate($request, [
		    'school_id' => 'required|integer',
            'first_name' => 'required',
            'last_name' => 'required',
            'course' => 'required',
            'year_level' => 'required',
            'image' => ['image', 'file|size:2048']
        ]);

		$img = $request->file('image'); // file for Student Image
		$path = 'images/schools';

		if($img) {
			$student['image'] = $this->storePhoto($request, $path, 'schools', false);
			$student->save();

            Session::flash('success', 'Student data successfully updated');
            return redirect()->route('students.index');
		}

        $old_image = $student['image'];
        $request['image'] = $old_image;

        $student->update($request->except('image'));

        Session::flash('success', 'Student data successfully updated');
        return redirect()->route('students.index');
	}

	public function destroy($id)
	{
		try {

			$student  = Student::findOrfail($id);

		}  catch(ModelNotFoundException $e){

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

        $student->delete();

		Session::flash('success', 'A student has been deleted!');
		return redirect()->route('students.index');
	}
}
