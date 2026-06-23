<?php

namespace App\Http\Controllers;

use App\Models\Wallpaper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WallpapersController extends Controller {

    public function index(Request $request)
    {
        $wallpapers = Wallpaper::whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->get();

        if($request->ajax()) {
            foreach ($wallpapers as $wallpaper) {
                switch ($wallpaper->location) {
                    case 'mnl':
                        $wallpaper->location = 'Manila';
                        break;
                    case 'cbu':
                        $wallpaper->location = 'Cebu';
                        break;
                    case 'dav':
                        $wallpaper->location = 'Davao';
                        break;
                    default:
                }

                switch ($wallpaper->device) {
                    case 'web':
                        $wallpaper->device = 'Desktop';
                        break;
                    case 'mobile':
                        $wallpaper->device = 'Mobile';
                        break;
                    default:
                }

                $wallpaper->options.= '<a href="'.route('wallpapers.show', $wallpaper->id).'" class="btn btn-outline-dark"><i class="fas fa-search"></i>  View</a>';
            }

            return response()->json($wallpapers);
        }

        return view('_cms.system-views.digital.wallpaper.index', compact('wallpapers'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|file|max:2048',
            'device' => 'required',
        ]);

        if($validator->passes()) {
            $path = 'images/wallpapers';
            $request['location'] = $this->getStationCode();

            $wallpaper = new Wallpaper($request->all());
            $wallpaper['image'] = $this->storePhoto($request, $path, 'wallpapers', false);
            $wallpaper->save();

            return response()->json(['status' => 'success', 'message' => 'A wallpaper has been added']);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
    }

    public function show($id)
    {
        $wallpaper = Wallpaper::findOrFail($id);

        return view('_cms.system-views.digital.wallpaper.show', compact('wallpaper'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'image|file|max:2048',
            'location' => 'required',
            'device' => 'required',
        ]);

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/wallpapers';

            $wallpaper = Wallpaper::findOrFail($id);

            if($img) {
                $wallpaper['image'] = $this->storePhoto($request, $path, 'wallpapers', false);
                $wallpaper->save();

                return response()->json(['status' => 'success', 'message' => 'A wallpaper has been updated', 'wallpaper' => $wallpaper]);
            }

            $wallpaper['name'] = $request['name'];;
            $wallpaper['device'] = $request['device'];

            $wallpaper->update($request->except('image'));

            return response()->json(['status' => 'success', 'message' => 'A wallpaper has been updated', 'wallpaper' => $wallpaper]);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
    }

    public function destroy($id)
    {
        $wallpaper = Wallpaper::findOrFail($id);

        $wallpaper->delete();

        return response()->json(['status' => 'success', 'message' => 'A wallpaper has been deleted'], 200);
    }
}
