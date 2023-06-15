<?php namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller {

	public function index(Request $request)
	{
	    if($request->ajax()) {
            $albums = Album::whereNull('deleted_at')
                ->get();

            return response()->json($albums);
        }

		$albums = Album::whereNull('deleted_at')
            ->get();
	    $artists = Artist::whereNull('deleted_at')
            ->orderBy('name')
            ->get();
		$genres = Genre::whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return view('_cms.system-views.music.album.index', compact('albums','genres', 'artists'));
    }

    public function create(){
        //
    }


	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'year' => 'required',
            'artist_id' => 'required',
            'genre_id' => 'required',
        ]);

		if($validator->passes()) {
            $img = $request->file('image'); //file for Album Image
            $path = 'images/albums';

            $album = new Album($request->all());

            if($img){
                $album['image'] = $this->storePhoto($request, $path, 'albums', true);
                $album->save();

                return response()->json(['status' => 'success', 'message' => 'New Album with Image has been added!']);
            }

            $album['image'] = 'default.png';
            $album->save();

            return response()->json(['status' => 'success', 'message' => 'New Album has been added!'], 200);
        }

		return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
	}

	public function show($id, Request $request)
	{
        $album = Album::with('Artist', 'Genre', 'Song')->findOrfail($id);

        $album['image'] = $this->verifyPhoto($album['image'], 'albums');

		if($request->ajax()) {
            return response()->json($album);
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function update($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'year' => 'required',
            'artist_id' => 'required',
            'genre_id' => 'required',
        ]);

        if($validator->passes()) {
            $img = $request->file('image'); //file for Album Image
            $path = 'images/albums';

            $album = Album::with('Artist')->findOrfail($id);

            if($img) {
                $album['image'] = $this->storePhoto($request, $path, 'albums', true);
                $album->save();

                return response()->json(['status' => 'success', 'message' => 'An album has been successfully updated']);
            }

            $album->update($request->except('image'));

            return response()->json(['status' => 'success', 'message' => 'An album has been successfully updated']);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
	}

	public function destroy($id)
	{
        $album  = Album::with('Song', 'Artist')->findOrfail($id);

		$album->delete();

		Session::flash('success', 'Album successfully deleted');
		return redirect()->route('albums.index');
	}

	public function reloadAlbums(){
	    $albums = Album::with('Artist')->whereNull('deleted_at')->get();

        foreach ($albums as $album) {
            $album->options = '' .
                '<div class="btn-group">' .
                '   <a href="#update-album" id="update-album-modal" data-id="'.$album->id.'" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal"><i class="fas fa-search"></i></a>' .
                '   <a href="#delete-album" id="delete-album-modal" data-id="'.$album->id.'" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal"><i class="fas fa-trash"></i></a>' .
                '</div>';
        }

	    return response()->json($albums);
    }
}
