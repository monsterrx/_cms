<?php namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller {

	public function index()
	{
		$genres = Genre::whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return view('_cms.system-views.music.genre.index', compact('genres'));
    }

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

		if($validator->passes()) {
            $genre = new Genre($request->all());

            $genre->save();

            Session::flash('success', 'A new genre has been successfully added!');
            return redirect()->route('genres.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id, Request $request)
	{
		$genre = Genre::findOrfail($id);

		if($request->ajax()) {
            $level = Auth::user()->Employee->Designation->level;
            if ($level === 1 || $level === 2 || $level === 6 || $level === 7) {
                return response()->json($genre);
            }
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function edit($id)
	{
		//
	}

	public function update($id, Request $request)
	{
	    $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

		if($validator->passes()) {
            $genre = Genre::findOrfail($id);

            $genre->update($request->all());

            Session::flash('success', 'A genre has been successfully updated');
            return redirect()->route('genres.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function destroy($id)
	{
		$genre = Genre::findOrfail($id);

		$genre->delete();

		Session::flash('success', 'A genre has been successfully deleted');
		return redirect()->route('genres.index');
	}

}
