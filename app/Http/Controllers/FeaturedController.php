<?php namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Indie;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FeaturedController extends Controller {

	public function index(Request $request)
	{
        $indieArtists = Indie::with('Artist')
            ->orderBy('id')
            ->get();

		if($request->ajax()) {
            $featuredIndieArtists = Feature::with('Indie.Artist')
                ->latest()
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($featuredIndieArtists as $artists) {
                $month = $artists->month;
                $monthName = date('F', mktime(0, 0 ,0, $month, 10));

                $artists->date_featured = $monthName . ' ' . date('Y', strtotime($artists->year));
                $artists->option = '' .
                    '<div class="btn-group">'.
                    '    <a href="#update-featured-indie" id="update-featured-artist" data-toggle="modal" data-id="'.$artists->id.'" class="btn btn-outline-dark"><i class="fas fa-search"></i></a>'.
                    '    <a href="#delete-featured-indie" id="delete-featured-artist" data-toggle="modal" data-id="'.$artists->id.'" class="btn btn-outline-dark"><i class="fas fa-trash"></i></a>'.
                    '</div>';
            }

            if($request->has('refresh')) {
                return response()->json($featuredIndieArtists);
            }

            return view('_cms.system-views.music.indiegrounds.featured.table', compact('featuredIndieArtists', 'indieArtists'));
        }

		return view('_cms.system-views.music.indiegrounds.featured.index', compact('indieArtists'));
	}

	public function create()
	{
		//
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'independent_id' => 'required',
            'content' => 'required'
        ]);

		if($validator->passes()) {
		    $request['month'] = date('m', strtotime($request['date']));
		    $request['year'] = date('Y', strtotime($request['date']));

            $featured_indieground = new Feature($request->all());

            $featured_indieground->save();

            return response()->json(['status' => 'success', 'message' => 'A featured indieground has been added!']);
        }

		return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
	}

	public function show($id, Request $request)
	{
		$featuredIndieArtist = Feature::with('Indie.Artist.Album.Song')->findOrFail($id);

        if($request['albums']) {
            return view('_cms.system-views.music.indiegrounds.featured.albums', compact('featuredIndieArtist'));
        }

        return response()->json($featuredIndieArtist);
	}

	public function edit($id)
	{
		//
	}

	public function update($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
		    'independent_id' => 'required',
            'content' => 'required'
        ]);

		$featuredIndieArtist = Feature::findOrFail($id);

		if($validator->passes()) {
            $featuredIndieArtist->update($request->all());

            Session::flash('success', 'Featured indieground artist has been updated!');
            return redirect()->route('featured.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function destroy($id)
	{
		$featuredIndieArtist = Feature::findOrFail($id);

		$featuredIndieArtist->delete();

        return response()->json(['status' => 'success', 'message' => 'Featured indieground artist has been removed']);
	}

}
