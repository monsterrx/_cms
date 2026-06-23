<?php namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Indie;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class IndieController extends Controller {

	public function index(Request $request)
	{
	    if($request->ajax()) {
	        if($request['independent_id']) {
	            $independent = Indie::with('Artist')->findOrFail($request['independent_id'])
                    ->first();

	            return response()->json($independent);
            }

	        $independent = Indie::with('Artist')
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->get();

	        foreach ($independent as $localArtists) {
	            $localArtists->introduction = Str::limit($localArtists->introduction, 120);
	            $localArtists->image = asset('images/indie/'.$localArtists->image);
	            $localArtists->option = '<div class="btn-group"><a href="#indie-artist-modal" id="indie-artist" data-toggle="modal" data-id="'.$localArtists->id.'" class="btn btn-outline-dark"><i class="fas fa-search"></i>  View</a><a href="#delete-indie-modal" id="delete-indie" data-toggle="modal" data-id="'.$localArtists->id.'" class="btn btn-outline-dark"><i class="fas fa-trash"></i>  Delete</a></div>';
            }

	        return response()->json(['indiegrounds' => $independent]);
        }

		$artist = Artist::orderBy('name')->whereNull('deleted_at')
            ->get();

		$indie = Indie::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

		$data = array('artist' => $artist, 'indie' => $indie);

		return view('_cms.system-views.music.indiegrounds.artists.index', compact('data'));
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'artist_id' => 'required',
            'introduction' => 'required',
        ]);

		if($validator->passes()) {
            $request['location'] = $this->getStationCode();

            $indieground = new Indie($request->all());

            /*if($img) {
                $indieground['image'] = $this->storePhoto($request, $path, 'indie', true);
                $indieground->save();

                return response()->json(['status' => 'success', 'message' => 'An indieground artist has been added!']);
            }

            $artistImage = Artist::with('Album.Song')->findOrFail($request['artist_id']);

            $indieground['image'] = $artistImage['image'];

            copy('images/artists/'.$artistImage['image'], 'images/indie/'.$artistImage['image']);

            $file = 'images/artists/'.$indieground['image'];
            $this->storeAsset('indie', $indieground->image, $file);*/

            // check if artist is already independent
            $indie = Indie::where('artist_id', $request['artist_id'])->count();

            if ($indie >= 1) {
                return response()->json(['status'=> 'error', 'message' => 'The artist is already an indieground artist'], 403);
            }

            $indieground->save();

            return response()->json(['status' => 'success', 'message' => 'An indieground artist has been added!']);
        }

		return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
	}

	public function show($id)
	{
		try {

			$indie = Indie::with('Artist')->findOrfail($id);

			$indie['image'] = $this->verifyPhoto($indie['image'], 'indie');

		} catch(ModelNotFoundException $e) {

			return response()->json(['status' => 'error', 'message' => 'Independent artist not found.'], 403);
		}

		return response()->json(['indieground' => $indie]);
	}

	public function update($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
            'artist_id' => 'required',
            'introduction' => 'required',
        ]);

        if($validator->passes()) {
            $img = $request->file('image'); // file for Artist Image
            $path = 'images/indie';

            $indie = Indie::with('Artist')->findOrfail($id);

            if($img) {
                $indie['image'] = $this->storePhoto($request, $path, 'indie', true);
                $indie->save();

                return response()->json(['status' => 'success', 'message' => 'An indieground artist have been successfully updated!']);
            }

            $indie->update($request->except('image'));

            return response()->json(['status' => 'success', 'message' => 'An indieground artist have been successfully updated!']);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
	}

	public function destroy($id)
	{
		try {

			$indie  = Indie::findOrfail($id);

		}  catch(ModelNotFoundException $e){

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$indie->delete();

		return response()->json(['status' => 'success', 'message' => 'Artist has been removed from the indiegrounds']);
	}

}
