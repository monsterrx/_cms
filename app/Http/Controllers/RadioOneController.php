<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentJock;
use App\Models\StudentJockBatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RadioOneController extends Controller {

    public function batches()
    {
        $batches = StudentJockBatch::orderBy('batch_number')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->get();

        return view('_cms.system-views.radioOne.batches.index', compact('batches'));
    }

    public function showBatch($id)
    {
        $batch = StudentJockBatch::findOrFail($id);

        $jocks = StudentJock::whereNull('deleted_at')->get();

        foreach ($batch->Student as $studentJock)
        {
            $studentJock['image'] = $this->verifyPhoto($studentJock['image'], 'studentJocks');
        }

        return view('_cms.system-views.radioOne.batches.show', compact('batch', 'jocks'));
    }

    public function storeBatch(Request $request){
        $validator = Validator::make($request->all(), [
            'batch_number' => 'required',
            'start_year' => 'required',
            'end_year' => 'required'
        ]);

        if($validator->passes()) {
            $batch = new StudentJockBatch($request->all());

            $batch->save();

            Session::flash('success', 'RadioOne batch Added!');
            return redirect()->route('radioOne.batches');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function updateBatch($id, Request $request)
    {
        $batch = StudentJockBatch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'batch_number' => 'required',
            'start_year' => 'required',
            'end_year' => 'required'
        ]);

        if($validator->passes()) {
            $batch->update($request->all());

            Session::flash('success', 'Batch has been successfully updated!');
            return redirect()->route('radioOne.batches');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function deleteBatch($id)
    {
        $batch = StudentJockBatch::findOrFail($id);

        DB::table('student_jock_student_jock_batch')->where('student_jock_batch_id', $id)->delete();

        $batch->delete();

        Session::flash('success', 'RadioOne batch deleted!');
        return redirect()->route('radioOne.batches');
    }
}
