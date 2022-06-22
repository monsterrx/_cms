<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\StudentJock;
use App\Models\StudentJockBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentJockController extends Controller
{
    public function studentJocks(Request $request)
    {
        if($request->ajax()) {
            $student = StudentJock::with('Batch')
                ->findOrFail($request['student_jock_id']);

            if($request['remove']) {
                return $student;
            }

            $schools = School::whereNull('deleted_at')
                ->where('id', '!=', $student['school_id'])
                ->get();

            return view('_cms.system-views.radioOne.batches.modal.studentJock', compact('student', 'schools'));
        }

        $studentJocks = StudentJock::whereNull('deleted_at')->get();

        $school = School::orderBy('name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($studentJocks as $studentJock)
        {
            $studentJock['image'] = $this->verifyPhoto($studentJock['image'], 'studentJocks');
        }

        return view('_cms.system-views.radioOne.studentJocks.index', compact('studentJocks', 'school'));
    }

    public function storeJock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_id' => 'required',
            'image' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/studentJocks';

            $studentJock = new StudentJock($request->all());

            if($img) {
                $studentJock['image'] = $this->storePhoto($request, $path, 'studentJocks', false);
                $studentJock->save();

                Session::flash('success', 'Successfully saved with Image');
                return redirect()->route('radioOne.jocks');
            }

            $studentJock['image'] = 'default.png';
            $studentJock->save();

            Session::flash('success', 'Successfully saved without Image');
            return redirect()->route('radioOne.jocks');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function updateStudentJock($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/studentJocks';

            $studentJock = StudentJock::with('Batch', 'School')->findOrFail($id);

            if($img) {
                $studentJock['image'] = $this->storePhoto($request, $path, 'studentJocks', false);;
                $studentJock->save();

                Session::flash('success', 'Student jock has been updated');
                return redirect()->back();
            }

            $studentJock->update($request->except('image'));

            session()->flash('success', 'Student jock has been updated');
            return redirect()->back();
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function deleteJock($id){
        $student_jock = StudentJock::findOrFail($id);

        DB::table('student_jock_student_jock_batch')->where('student_jock_id', $id)->delete();

        $student_jock->delete();

        Session::flash('success', 'Student Jock has been deleted!');
        return redirect()->route('radioOne.jocks');
    }

    public function addStudentToBatch($id, Request $request) {
        $batch = StudentJockBatch::with('Student')->findOrFail($id);

        $hits = DB::table('student_jock_student_jock_batch')
            ->where('student_jock_batch_id', $id)
            ->where('student_jock_id', $request['student_jock_id'])
            ->count();

        if($hits > 0) {
            return redirect()->back()->withErrors('The student jock is already in the batch');
        }

        $studentJock = StudentJock::with('Batch')->findOrFail($request['student_jock_id']);

        $studentJock['position'] = $request['position'];

        $studentJock->save();

        $batch->Student()->attach($studentJock['id']);

        session()->flash('success', 'A student jock has been added to the batch');
        return redirect()->route('radioOne.batch', $id);
    }

    public function removeStudentFromBatch($id, Request $request) {
        $batch = StudentJockBatch::findOrFail($id);

        $batch->Student()->detach($request['remove_student_jock_id']);

        session()->flash('success', 'A student jock has been removed to the batch');
        return redirect()->route('radioOne.batch', $id);
    }
}
