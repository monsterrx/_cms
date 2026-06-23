<?php namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\Podcast;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class PodcastController extends Controller {

	public function index(Request $request)
	{
	    if($request->ajax()) {
            $podcast = Podcast::with('Show')
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->orderBy('date', 'desc')
                ->get();

            $show = Show::orderBy('title')
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->get();

            $options = "";

            foreach ($show as $shows) {
                $options.= '<option value="'.$shows->id.'">'.$shows->title.'</option>';
            }

            foreach ($podcast as $podcasts) {
                $podcasts->options = '' .
                    '<div class="btn-group">' .
                    '   <a href="#update_podcast_modal" id="update-podcast-modal" data-id="'.$podcasts->id.'" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-search"></i></a>' .
                    '   <a href="#delete_podcast_modal" id="delete-podcast-modal" data-id="'.$podcasts->id.'" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-trash-alt"></i></a>' .
                    '</div>';
            }

            return response()->json(['podcasts' => $podcast, 'shows' => $options]);
        }

		$podcast = Podcast::orderBy('date', 'desc')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->get();

		$show = Show::orderBy('title')->get();
		$user = Auth::user()->Employee->Designation;

		$data = array('podcast' => $podcast, 'show' => $show, 'user' => $user);

		return view('_cms.system-views.programs.podcast.index', compact('data'));
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
		    'show_id' => 'required',
            'episode' => 'required',
            'date' => 'required',
            'link' => 'required'
        ]);

		if($validator->passes()) {
            $img = $request->file('image'); //file for image
            $path = 'images/podcasts';

            $podcast = new Podcast($request->all());

            if($img) {
                $podcast['image'] = $this->storePhoto($request, $path, 'podcasts', false);
            } else {
                $podcast['image'] = 'tmr-default.png';
            }

            $podcast->save();

            return response()->json(['status' => ' success', 'message' => 'A podcast episode has been updated'], 200);
        }

		return response()->json(['status' => 'error' ,'errors' => $validator->errors()->all()], 403);
	}

	public function show($id)
	{
		try {

            $podcast = Podcast::with('Show')->findOrfail($id);

        } catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

        $podcast['image'] = $this->verifyPhoto($podcast['image'], 'podcasts');

		return response()->json(['podcast' => $podcast]);
	}

	public function update($id, Request $request)
	{
		try {
			$podcast = Podcast::findOrfail($id);
		} catch(ModelNotFoundException $e) {
			return redirect()->back()->withErrors(trans('response.model.not.found'));
		}

		$this->validate($request, [
		    'show_id' => 'required',
            'episode' => 'required',
            'date' => 'required',
            'link' => 'required'
        ]);

		$img = $request->file('image'); //file for image
		$path = 'images/podcasts';

		if($img) {
            $podcast['image'] = $this->storePhoto($request, $path, 'podcasts', false);
            $podcast->save();
		} else {
			$podcast->update($request->except('image'));
		}

		Session::flash('success', 'Podcast has been successfully updated');
		return redirect()->route('podcasts.index');
	}

	public function destroy($id)
	{
		try {
			$podcast = Podcast::findOrfail($id);
		} catch(ModelNotFoundException $e) {
			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$podcast->delete();

		Session::flash('success', 'Podcast episode has been successfully removed!');
		return redirect()->route('podcasts.index');
	}
}
