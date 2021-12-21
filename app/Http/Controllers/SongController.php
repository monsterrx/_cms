<?php namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Song;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller {

	public function index()
	{
		$song = Song::whereNull('deleted_at')
            ->orderBy('name')
            ->get();
		$album = Album::whereNull('deleted_at')
            ->orderBy('name')
            ->get();
        $genre = Genre::whereNull('deleted_at')
            ->orderBy('name')
            ->get();
		$artist = Artist::whereNull('deleted_at')
            ->orderBy('name')
            ->get();
        $country = $this->getCountries();

        return view('_cms.system-views.music.songs.index',compact('song','album','artist', 'genre', 'country'));
    }

	public function create()
    {
		// Obsolete
    }


	public function store(Request $request)
	{
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'album_id' => 'required',
        ]);

		if($validator->passes()) {
            $request['is_charted'] = '0';

            $song_file = $request->file('track_link');
            $path = 'audios';

            $song = new Song($request->except('artist'));

            if($song_file) {
                $song_name = date('Ymd') . '-' . mt_rand(1000, 9999) . '.' . $song_file->getClientOriginalExtension();

                $song_file->move($path, $song_name);

                $song['track_link'] = $song_name;

                $song->save();

                $file = 'audios/' . $song_name;
                $this->storeAsset('songs', $song_name, $file);

                return response()->json(['status' => 'success', 'message' => 'New song with file has been added!']);
            }

            $song['track_link'] = $request['track_link'];

            $song->save();

            return response()->json(['status' => 'success', 'message' => 'New song has been added!']);
        }

		return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
	}

	public function show($id, Request $request)
	{
		$song = Song::with('Album.Artist')->findOrfail($id);

		if($request->ajax()) {
            return response()->json($song);
        }

        return redirect()->back()->withErrors('No direct script access!');
    }

	public function update($id, Request $request)
	{
	    $validator = Validator::make($request->all(), [
            'name' => 'required',
            'album_id' => 'required',
        ]);

		if($validator->passes()) {
            $song_file = $request->file('track_link');
            $path = 'audios';

            $song = Song::with('Album.Artist')->findOrfail($id);

            if($song_file) {
                $song_name = date('Ymd') . '-' . mt_rand() . '.' . $song_file->getClientOriginalExtension();
                $song_file->move($path, $song_name);

                $song['track_link'] = $song_name;

                $song->save();

                $file = 'audios/' . $song_name;
                $this->storeAsset('songs', $song_name, $file);

                Session::flash('success', 'Song with file has been successfully updated');
                return redirect()->route('songs.index');
            }

            $song->update($request->all());

            Session::flash('success', 'Song successfully updated');
            return redirect()->route('songs.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function destroy($id)
	{
		$song = Song::findOrfail($id);

		$song->delete();

		Session::flash('success', 'Song Successfully Deleted');
		return redirect()->action('SongController@index');
	}

	public function selectSongInTable(Request $request){
		$song = Album::where('id', $request['album_id'])->get();

		return response()->json($song, 200);
	}

	public function filterArtistAlbums(Request $request){
		if ($request->ajax()){
			$queryResults = Album::with('Artist')
                ->where('artist_id', $request['search'])
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();

            $output = "";

			if($queryResults){
				foreach ($queryResults as $album) {
					$output.= "" .
                        "<tr data-value='".$album->id."'>" .
                        "    <td>".$album->name."</td>" .
                        "    <td>".$album->year."</td>" .
                        "    <td>".$album->type."</td>" .
                        "    <td>".$album->Genre->name."</td>" .
                        "</tr>";
				}
				return response()->json($output);
			}
		}

		return redirect()->back()->withErrors('No direct script access!');
	}


	public function reloadSongsTable() {
        $songs = Song::with('Album.Artist')
            ->whereNull('deleted_at')
            ->get();

        foreach ($songs as $song) {
            if($song->track_link) {
                $song->track_link = '<p class="text-success" title="Available"><i class="fas fa-check-circle"></i></p>';
            } else {
                $song->track_link = '<p class="text-danger" title="Not Available"><i class="fas fa-exclamation-circle"></i></p>';
            }

            $song->options = '' .
                '<div class="btn-group">' .
                '   <a href="#update-song" id="update-song-modal" data-id="'.$song->id.'" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal"><i class="fas fa-search"></i></a>' .
                '   <a href="#delete-song" id="delete-song-modal" data-id="'.$song->id.'" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal"><i class="fas fa-trash"></i></a>' .
                '</div>';
        }

        return response()->json($songs);
    }
}
