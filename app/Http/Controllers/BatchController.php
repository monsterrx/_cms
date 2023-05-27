<?php namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Photo;
use App\Models\Scholar;
use App\Models\Sponsor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller {

	public function index(Request $request)
	{
		$batch = Batch::with('Student')->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('number')->get();

		$scholar = Scholar::whereNull('deleted_at')->get();

		$data = array('batch' => $batch, 'scholar' => $scholar);

        return view('_cms.system-views.education.monsterScholar.batch.index', compact('data'));
	}

	public function store(Request $request)
	{
		if ($request['batch_id'] && $request['student_id']) {
			$students = $request->get('student_id');
			$batch = Batch::findOrfail($request['batch_id']);

			$ctr = 0;

			for ($i = 0; $i < sizeof($students); $i++) {

				$check = DB::table('batch_student')
                    ->where('batch_id', '=', $request['batch_id'])
                    ->where('student_id', '=', $students[$ctr])
                    ->count();

				if ((!$check) >= 1) {

					$batch->Student()->attach($students[$ctr]);

                    $scholar = new Scholar([
                        'student_id' => $students[$ctr],
                        'batch_id' => $request['batch_id'],
                        'scholar_type' => $request['scholar_type'][$ctr]
                    ]);

					$scholar->save();

				} else {

					return redirect()->back()->withErrors('Student is already in the batch!');
				}

				$ctr++;
			}

			Session::flash('success', 'A new Monster Scholar has been Added');
			return redirect()->route('batch.index');
		}

		if ($request['batch_id'] && $request['sponsor_id']) {

			$sponsors = $request->get('sponsor_id');
			$sponsor = Batch::findOrfail($request['batch_id']);

			$ctr = 0;

			for ($i = 0; $i < sizeof($sponsors); $i++) {

				$check = DB::table('batch_sponsor')
                    ->where('batch_id', '=', $request['batch_id'])
                    ->where('sponsor_id', '=', $sponsors[$ctr])
                    ->count();

				if ((!$check) >= 1) {

					$sponsor->Sponsor()->attach($sponsors[$ctr]);

				} else {

					return redirect()->back()->withErrors('Sponsor is already in the batch!');
				}

				$ctr++;
			}

			Session::flash('success', 'A sponsor has been successfully added!');
			return redirect()->route('batch.index');
		}

		$validator = Validator::make($request->all(), [
            'semester' => 'required',
            'number' => 'required|integer',
            'start_year' => 'required',
            'end_year' => 'required',
            'image' => ['image','file|size:2048','mimes:jpeg, png, bmp, gif, svg, or webp'],
        ]);

		$request['location'] = $this->getStationCode();

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/scholarBatch';
            $request['location'] = $this->getStationCode();

            $batch = new Batch($request->all());

            $school_year_start = $request['start_year'];
            $school_year_end = $request['end_year'];

            if ($school_year_start > $school_year_end || $school_year_start === $school_year_end) {
                return redirect()->back()->withErrors(['Error attained', 'school year start must be earlier than school year end and not equal']);
            }

            if($img) {
                $batch['image'] =  $this->storePhoto($request, $path, 'scholarBatch');
            } else {
                $batch['image'] = 'default.png';
            }

            $batch->save();

            Session::flash('success', 'A new batch has been successfully added!');
            return redirect()->route('batch.index');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id)
	{
		try {

			$batch = Batch::with('Student')->findOrfail($id);

		} catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$student = Student::select(DB::raw('CONCAT(first_name, " ",last_name) as student_name'), 'id')
            ->orderBy('student_name')
            ->get();

		$sponsor = Sponsor::orderBy('name')->get();
		$image = Photo::where('batch_id', $batch['id'])
            ->orderBy('created_at', 'desc')
            ->get();

        $batch['image'] = $this->verifyPhoto($batch['image'], 'scholarBatch', true);

		$data = array('image' => $image, 'student' => $student, 'sponsor' => $sponsor);

		return view('_cms.system-views.education.monsterScholar.batch.show', compact('batch','data'));
	}

	public function update($id, Request $request)
	{
        $validator = Validator::make($request->all(), [
            'semester' => 'required',
            'number' => 'required|integer',
            'start_year' => 'required',
            'end_year' => 'required',
            'image' => ['image', 'file|size:2048']
        ]);

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/scholarBatch';

            $batch = Batch::findOrfail($id);

            $school_year_start = $request['start_year'];
            $school_year_end = $request['end_year'];

            if ($school_year_start > $school_year_end || $school_year_start === $school_year_end) {
                return redirect()->back()->withErrors(['Error attained', 'School year start must be earlier than school year end and not equal']);
            }

            if($img) {
                $batch['image'] = $this->storePhoto($request, $path, 'scholarBatch');
                $batch->save();

                Session::flash('success', 'Scholar batch has been successfully updated!');
                return redirect()->route('batch.update', $batch['id']);
            } else {
                $batch->update($request->except('image'));
            }

            Session::flash('success', 'Scholar batch has been successfully updated! No batch image has been saved.');
            return redirect()->route('batch.update', $batch['id']);
        }

        return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function destroy($id, Request $request)
	{
		$image = '';

		try {

			if ($request['image_id']) {
                $batch = Photo::findOrfail($request['image_id']);
				$image = '1';
			} else {

                $batch  = Batch::findOrfail($id);
			}

		} catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

        $batch->delete();

		Session::flash('success', 'A Scholar batch has been deleted!');

		if ($image) {
            return redirect()->back();
		}

        return redirect()->route('batch.index');
    }

	public function addImage(Request $request){

		$this->validate($request, ['image' => 'required']);

		$img = $request->file('image');

		if ($img) {

			foreach ($img as $images) {

				$path = 'images/schools';
				$imgname = date('Ymd') . '-' . mt_rand() . '.' . $images->getClientOriginalExtension();

				$images->move($path, $imgname);

				$file = 'images/schools/'.$imgname;
				Storage::disk('schools')->put($imgname, file_get_contents($file));

				$request['file'] = $imgname;
				$request['name'] = $imgname;

				$image = new Photo($request->all());
				$image->save();
			}

		}  else {

			return redirect()->back()->withErrors(['Error Occurred', 'Upload failed']);
		}

		Session::flash('success', 'Images successfully added');
		return redirect()->route('batch.index');
	}

	public function addParam(Request $request){

		$student = '';
		$sponsor = '';

		if ($request['param'] === 'student') {
			$student = Student::select(DB::raw('CONCAT(first_name, last_name) as student_name'), 'id')
                ->orderBy('student_name')
                ->get();

			$data = array('student' => $student, 'sponsor' => $sponsor);
			return view('_cms.system-views.education.monsterScholar.batch.html.add_student', compact('data'));
		}

		if ($request['param'] === 'sponsor') {
			$sponsor = Sponsor::orderBy('sponsor_name')->get();

			$data = array('student' => $student, 'sponsor' => $sponsor);
			return view('_cms.system-views.education.monsterScholar.batch.html.add_sponsor', compact('data'));
		}

        return 'no data found';
	}

	public function delParam($id, Request $request){

		$student = '';
		$sponsor = '';

		try {
			$param  = Batch::findOrfail($id);
		} catch (ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error', 'Data not Found!']);
		}

		if ($request['param'] === 'student') {

            $param->Student()->detach($request['id']);

            Scholar::where('student_id', '=', $request['id'])->delete();

            $message = 'Student has been Successfully removed from the batch';

            Session::flash('success', $message);
            return redirect()->route('batch.update', $param['id']);
		}

		if ($request['param'] === 'sponsor'){

            $param->Sponsor()->detach($request['id']);

            $message = 'Sponsor has been Successfully removed from the batch';

            Session::flash('success', $message);
            return redirect()->route('batch.update', $param['id']);
		}

        return redirect()->back()->withErrors(['Error occurred, Please contact IT developer']);
    }
}
