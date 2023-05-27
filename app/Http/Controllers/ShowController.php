<?php namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Photo;
use App\Models\Jock;
use App\Models\Show;
use App\Models\StudentJock;
use App\Models\Timeslot;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShowController extends Controller
{

    public function index()
    {
        $show = Show::where('deleted_at')
            ->orderBy('title')
            ->get();

        $level = Auth::user()->Employee->Designation->level;

        if ($level >= 1 && $level <= 4) {
            return view('_cms.system-views.programs.show.index', compact('show'));
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'image|file|max:2048',
            'background_image' => 'image|file|max:2048',
            'header_image' => 'image|file|max:2048'
        ]);

        if ($request['is_special'] === 2 || $request['is_special'] === '2') {
            $request['slug_string'] = Str::studly($request['title']);

            $show = new Show($request->all());
            $show->save();

            Session::flash('success', 'Show successfully Added');
            return redirect()->route('shows.index');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'front_description' => 'max:255',
            'description' => 'required',
            'is_special' => 'required',
        ]);

        if ($validator->passes()) {
            $img = $request->file('background_image');
            $path = 'images/shows';
            $request['location'] = $this->getStationCode();
            $request['slug_string'] = Str::studly($request['title']);
            $request['is_active'] = 0;

            $show = new Show($request->all());

            if ($img) {
                $show->background_image = $this->storePhoto($request, $path, 'shows', true, false, true);
                $show->save();
            } else {
                if ($request['is_special'] === '2' || $request['is_special'] === 2) {
                    $show->icon = $this->storePhoto($request, $path, 'shows', true);
                    $show->save();

                    Session::flash('success', 'Show successfully added');
                    return redirect()->back();
                }

                return redirect()->back()->withErrors('There is no provided header picture');
            }
            $show->save();

            $show = Show::latest()->first();

            Session::flash('success', 'Show has been successfully added');
            return redirect()->route('shows.show', $show->id);
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function show($id)
    {
        $show = Show::with('Jock.Employee', 'Timeslot', 'Image')->findOrfail($id);

        $jock = Employee::with('Jock')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->get();

        $jock_show = DB::table('jock_show')
            ->join('jocks', 'jock_show.jock_id', '=', 'jocks.id')
            ->join('employees', 'jocks.employee_id', '=', 'employees.id')
            ->select('employees.first_name', 'employees.last_name', 'jock_show.jock_id')
            ->where('jock_show.show_id', $id)
            ->orderBy('employees.first_name', 'asc')
            ->get();

        $timeslot = Timeslot::where('show_id', $id)
            ->where('location', $this->getStationCode())
            ->get();

        $image = Photo::where('show_id', $id)->get();

        $level = Auth::user()->Employee->Designation->level;

        if ($level >= 1 && $level <= 4) {
            return view('_cms.system-views.programs.show.show', compact('show', 'jock', 'jock_show', 'timeslot', 'image'));
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'front_description' => 'max:255|required',
        ]);

        if ($validator->passes()) {
            $img = $request->file('image'); // file for header Image

            $this->validate($request, [
                'image' => 'image|file|max:2048'
            ]);

            $path = 'images/shows';

            $show = Show::with('Timeslot')->findOrfail($id);

            if ($img) {
                $show['slug_string'] = Str::studly($request['title']);
                $show->icon = $this->storePhoto($request, $path, 'shows', true);
                $show->save();

                Session::flash('success', 'Show has been successfully Updated');
                return redirect()->route('shows.show', $show['id']);
            }

            $show['slug_string'] = Str::studly($request['title']);

            $show->update($request->all());

            Session::flash('success', 'Show has been successfully Updated');
            return redirect()->route('shows.show', $show['id']);
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function destroy($id)
    {
        $show = Show::with('Timeslot')->findOrfail($id);

        $show->delete();

        Session::flash('success', 'Show has been successfully Deleted');
        return redirect()->route('shows.index');
    }

    public function addJock($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jock_id' => 'required',
        ]);

        $show = Show::with('Jock')->findOrfail($id);

        $count = DB::table('jock_show')
            ->where('jock_id', $request['jock_id'])
            ->where('show_id', $id)
            ->count();

        if ($count > 0) {
            return redirect()->back()->withErrors(['The jock is already on the show']);
        }

        if($validator->passes()) {
            $show->Jock()->attach($request['jock_id']);

            Session::flash('success', 'Jock has been successfully added to the Show');
            return redirect()->route('shows.show', $show['id']);
        }

        return redirect()->route('shows.show', $show['id'])->withErrors($validator->errors()->all());
    }

    public function removeJock($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jock_id' => 'required',
        ]);

        $show = Show::with('Jock')->findOrfail($id);

        if($validator->passes()) {
            $show->Jock()->detach($request['jock_id']);

            Session::flash('success', 'Jock has been successfully added to the Show');
            return redirect()->route('shows.show', $show['id']);
        }

        return redirect()->route('shows.show', $show['id'])->withErrors($validator->errors()->all());
    }

    public function storeBackgroundImage($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'image|file|max:2048|required',
        ]);

        if($validator->passes()) {
            $show = Show::with('Timeslot')->findOrFail($id);
            $path = 'images/shows';

            $show->background_image = $this->storePhoto($request, $path, 'shows', true);
            $show->save();

            return response()->json(['status' => 'success', 'message' => 'Background image has been uploaded successfully']);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
    }

    public function storeHeaderImage($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'image|file|max:2048|required',
        ]);

        if($validator->passes()) {
            $show = Show::with('Timeslot')->findOrFail($id);
            $path = 'images/shows';

            $show->header_image = $this->storePhoto($request, $path, 'shows', true);
            $show->save();

            return response()->json(['status' => 'success', 'message' => 'Header image has been uploaded successfully']);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
    }
}
