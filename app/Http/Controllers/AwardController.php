<?php namespace App\Http\Controllers;

use App\Models\Jock;
use App\Models\Show;
use App\Models\Award;
use App\Traits\LogsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AwardController extends Controller {

    use LogsUsers;

	public function index(Request $request)
	{

        if($request->ajax())
        {
            $output = '';

            if ($request['awardee'] === 'jock') {
                $jock = Jock::orderBy('name')->get();
                if($jock){
                    foreach ($jock as $jocks) {
                        $output.='<option value="'.$jocks->id.'">'.$jocks->name.'</option>';
                    }
                } else {
                    $output.='<option value="">No Jocks Found</option>';
                }
            } elseif ($request['awardee'] === 'show') {
                $show = Show::orderBy('title')->get();
                if ($show) {
                    foreach ($show as $shows) {
                        $output.='<option value="'.$shows->id.'">'.$shows->title.'</option>';
                    }
                } else {
                    $output.='<option value>No Shows Found</option>';
                }
            } else {
                $output.='<option value>No Data Found</option>';
            }

            return response()->json($output);
        }

        $awards = Award::with('Jock', 'Show')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('created_at')
            ->get();

        return view('_cms.system-views.awards.index', compact('awards'));
	}

	public function create()
	{
	    //
	}

	public function store(Request $request)
	{
	    $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'awardee' => 'required',
            'year' => 'required',
            'special' => 'required'
        ]);

	    if($validator->passes()) {
            if ($request['select_awardee'] === 'show') {

                $request['show_id'] = $request['awardee'];

            } elseif ($request['select_awardee'] === 'jock') {

                $request['jock_id'] = $request['awardee'];

            } else {

                return redirect()->back()->withErrors(['Select Awardee must be filled']);
            }

            $request['year'] = date('Y',strtotime($request['year']));

            $request['location'] = $this->getStationCode();

            $award = new Award($request->all());
            $award->save();

            $this->userLog('Added an award', Auth::user()->id, $request);

            Session::flash('success', 'A new awardee has been successfully added');
            return redirect()->route('awards.index');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id, Request $request)
	{
        $award = Award::with('Jock', 'Show')->findOrfail($id);

        if($request->ajax()) {
            return response()->json($award);
        }

        return redirect()->back()->withErrors('No direct script access!');
	}

	public function update($id,Request $request)
	{
        $award  = Award::findOrfail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'awardee' => 'required',
            'year' => 'required',
            'special' => 'required'
        ]);

		if($validator->passes()) {
            if ($request['select_awardee'] === 'show') {
                $request['show_id'] = $request['awardee'];
            } elseif ($request['select_awardee'] === 'jock') {
                $request['jock_id'] = $request['awardee'];
            }

            $award->update($request->all());

            Session::flash('success', 'An award has been successfully updated');
            return redirect()->route('awards.index');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function destroy($id, Request $request)
	{
        $award  = Award::findOrfail($id);

		$award->delete();

        $this->userLog('Deleted an award', Auth::user()->id, $request);

		Session::flash('success', 'An award has been successfully removed');
		return redirect()->route('awards.index');
	}
}
