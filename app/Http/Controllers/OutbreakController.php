<?php namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Outbreak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OutbreakController extends Controller {

	public function index(Request $request)
	{
	    if($request->ajax())
        {
            $songs = Song::with('Album.Artist')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();

            $outbreaks = Outbreak::with('Song.Album.Artist')
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->orderBy('dated', 'desc')
                ->get();

            $options = "";

            foreach ($songs as $song)
            {
                $options.= '<option value="'.$song->id.'">'.$song->name.' &mdash; '.$song->Album->Artist->name.'</option>';
            }

            foreach ($outbreaks as $outbreak)
            {
                $outbreak->options.= ''.
                    '<div class="btn-group">' .
                    '    <a href="#updateOutbreak" id="update-outbreak-modal" data-id="'.$outbreak->id.'" class="btn btn-outline-dark" data-toggle="modal">' .
                    '        <span class="fas fa-search"></span>' .
                    '    </a>' .
                    '    <a href="#deleteOutbreak" id="delete-outbreak-modal" data-id="'.$outbreak->id.'" class="btn btn-outline-dark" data-toggle="modal">' .
                    '        <span class="fas fa-trash"></span>' .
                    '    </a>' .
                    '</div>';
            }

            return response()->json(['outbreaks' => $outbreaks, 'songs' => $songs, 'options' => $options]);
        }

        $where = DB::table('outbreaks')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->Max('dated');

		$outbreak = Outbreak::where('dated', $where)
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('created_at','desc')
            ->get();

		$songs = Song::orderBy('created_at','desc')->get();

		$recentOutbreak = Outbreak::where('dated', $where)
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('created_at','desc')
            ->get();

        $data = array('song' => $songs, 'outbreak' => $outbreak);

        return view('_cms.system-views.music._chart.outbreak',compact('data', 'recentOutbreak', 'where'));
    }

	public function create()
	{
		//
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
		    'song_id' => 'required',
            'dated' => 'required'
        ]);

		$request['location'] = $this->getStationCode();

		if($validator->passes()) {
            $songLink = Song::where('id', $request['song_id'])
                ->select('track_link')
                ->get()
                ->first();

            $request['track_link'] = $songLink->track_link;
            $request['location'] = $this->getStationCode();

            Outbreak::create($request->all());

            Session::flash('success', 'A new outbreak song has been added');
            return redirect()->route('outbreaks.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id, Request $request)
	{
	    if($request->ajax()) {
            if($request['song_id']) {
                $song = Song::where('id', $request['song_id'])
                    ->get()
                    ->first();

                if($song['track_link'] === null || $song['track_link'] === "")
                {
                    return "false";
                }

                return "true";
            }
        }

        $outbreaks = outbreak::with('Song.Album.Artist')->findOrfail($id);

	    return response()->json($outbreaks);
	}

	public function edit($id)
	{
		//
	}

	public function update($id, Request $request)
	{
        $validator = Validator::make($request->all(), [
            'song_id' => 'required',
            'dated' => 'required'
        ]);

        $outbreak  = Outbreak::findOrfail($id);

        if($validator->passes()) {
            $outbreak->update($request->all());

            Session::flash('success','Outbreak song has been successfully updated!');
            return redirect()->route('outbreaks.index');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function destroy($id, Request $request)
	{
        $outbreak  = Outbreak::findOrfail($id);

        $outbreak->delete();

		Session::flash('success', 'Outbreak song removed');
		return redirect()->action('OutbreakController@index');
	}
}
