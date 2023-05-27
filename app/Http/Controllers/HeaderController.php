<?php namespace App\Http\Controllers;

use App\Models\Header;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class HeaderController extends Controller {

    public function index()
    {
        $slider = Header::whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('number')
            ->get();

        $data = array('slider' => $slider);
        $level = Auth::user()->Employee->Designation->level;

        if ($level === 1 || $level === 2 || $level === 4) {
            return view('_cms.system-views.digital.slider.index',compact('data'));
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function create()
    {
        $level = Auth::user()->Employee->Designation->level;

        if ($level === 1 || $level === 2 || $level === 4) {
            return view('_cms.system-views.digital.slider.create');
        }

        return redirect()->back()->withErrors(trans('response.restricted'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'file|max:2048|image|required'
        ]);

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/headers';
            $request['location'] = $this->getStationCode();

            $sliders_count = Header::whereNull('deleted_at')->where('location', '=', $this->getStationCode())->count();

            if ($sliders_count === 0) {
                $request['number'] = 0;
            }

            $recent = Header::whereNull('deleted_at')
                ->where('location', '=', $this->getStationCode())
                ->max('number');

            $request['number'] = $recent + 1;

            $header = new Header($request->all());

            if($img) {
                $header['image'] = $this->storePhoto($request, $path, 'headers', false);
                $header->save();

                Session::flash('success', 'Slider has been successfully Added');
                return redirect()->route('sliders.index');
            }

            $header['image'] = 'header-lg-missing.png';
            $header->save();

            Session::flash('success', 'Slider has been successfully Added');
            return redirect()->route('sliders.index');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function show($id)
    {
        $slider = Header::findOrfail($id);

        $data = array('slider' => $slider);

        $level = Auth::user()->Employee->Designation->level;

        if ($level === 1 || $level === 2 || $level === 4) {
            return view('_cms.system-views.digital.slider.show',compact('data'));
        }

        return redirect()->back()->withErrors(trans('response.restricted'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/headers';

            $header = Header::findOrFail($id);

            if($img) {
                $header['image'] = $this->storePhoto($request, $path, 'headers', false);
                $header->save();

                Session::flash('success', 'Slider has been successfully Added');
                return redirect()->route('sliders.show', $header->id);
            }

            $header->update($request->except(['image', 'number']));

            Session::flash('success', 'Slider has been successfully Updated');
            return redirect()->route('sliders.show', $header->id);
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function destroy($id)
    {
        $slider = Header::findOrfail($id);

        $next_slider = Header::where('number', '>',  $slider['number'])
            ->orderBy('number')
            ->get();

        $slider->delete();

        if ($next_slider) {
            foreach ($next_slider as $sliders) {
                --$sliders->number;
                $sliders->save();
            }
        }

        Session::flash('success', "Slider successfully Deleted");
        return redirect()->route('sliders.index');
    }
}
