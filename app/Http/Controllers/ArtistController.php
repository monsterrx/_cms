<?php namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Indie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller {

	public function index(Request $request)
	{
        if($request->ajax())
        {
            if($request['artistName']) {
                $name = $request['artistName'];

                $result = Artist::whereNull('deleted_at')
                    ->where('name', 'LIKE', '%'.$name.'%')
                    ->orderBy('name')
                    ->get()
                    ->take(5);

                return response()->json($result);
            }

            $artists = Artist::whereNull('deleted_at')
                ->orderBy('Name')
                ->get();

            $options = '';

            foreach($artists as $artist) {
                $options.= '<option value="'.$artist->id.'">'.$artist->name.'</option>';
            }

            return response()->json($options);
        }

        $artist = Artist::whereNull('deleted_at')->get();

        $countries = $this->getCountries();

        return view('_cms.system-views.music.artist.index', compact('artist', 'countries'));
    }

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'country' => 'required',
            'type' => 'required',
        ]);

        if($validator->passes()) {
            $img = $request->file('image'); //file for Artist Image
            $path = 'images/artists';

            $artist = new Artist($request->all());

            if($img) {
                $artist['image'] = $this->storePhoto($request, $path, 'artists', true);
                $artist->save();

                return response()->json(['status' => 'success', 'message' => 'New artist with image has been saved']);
            }

            $artist['image'] = 'default.png';
            $artist->save();

            return response()->json(['status' => 'success', 'message' => 'New artist has been saved']);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
	}

	public function show($id, Request $request)
	{
        $artist = Artist::with('Album.Song')->findOrfail($id);

        $artist['image'] = $this->verifyPhoto($artist['image'], 'artists');

        // for indiegrounds and albums
        if($request->ajax()) {
            return response()->json($artist);
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function update($id, Request $request)
	{
		$artist = Artist::findOrfail($id);

		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'country' => 'required',
            'type' => 'required',
        ]);

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/artists';

            if($img){
                $artist['image'] =  $this->storePhoto($request, $path, 'artists', true);
                $artist->save();

                return response()->json(['status' => 'success', 'message' => 'An artist has been updated']);
            }

            $artist->update($request->except('image'));

            return response()->json(['status' => 'success', 'message' => 'An artist has been updated']);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
	}

	public function destroy($id)
	{
		$artist = Artist::findOrfail($id);

		$artist->delete();

		session()->flash('success', 'Artist has been successfully deleted');
		return redirect()->route('artists.index');
	}

	public function delete(Request $request){

		$artist = Artist::findOrfail($request['id']);

		$artist->delete();

		session()->flash('success', 'Artist has been successfully deleted');
		return redirect()->route('artists.index');
	}

	public function reloadTable(){
	    $artists = Artist::whereNull('deleted_at')->get();

	    foreach ($artists as $artist) {
            $artist->options = '' .
                '<div class="btn-group">' .
                '    <a href="#update-artist" id="update-artist-modal" data-id="'.$artist->id.'" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal"><i class="fas fa-search"></i></a>' .
                '    <a href="#delete-artist" id="delete-artist-modal" data-id="'.$artist->id.'" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal"><i class="fas fa-trash"></i></a>' .
                '</div>';
        }

	    return response()->json($artists);
    }

    public function addArtistImage(Request $request) {
	    $data = $request['image'];

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);
        $imgname = date('Ymd').'-'. mt_rand() . '.png';

        $path = 'images/artists/'. $imgname;
        file_put_contents($path, $data);
        $this->storeAsset('artists', $imgname, $path);

        $indie_path = 'images/indie/'. $imgname;
        file_put_contents($indie_path, $data);
        $this->storeAsset('indie', $imgname, $indie_path);

        return $imgname;
    }
}
