<?php namespace App\Http\Controllers;

use App\Models\Fact;
use App\Models\Jock;
use App\Models\Photo;
use App\Models\Social;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class JockController extends Controller {

	public function index(Request $request)
	{
		$employees = Employee::whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->where('designation_id', 9)
            ->orderBy('first_name')
            ->get();

		$jocks = Jock::whereNull('deleted_at')
            ->orderBy('created_at')
            ->get();

		foreach ($jocks as $jock) {
		    $jock->employee_name = $jock->Employee->first_name . ' ' . $jock->Employee->last_name;

            if($jock->Employee->location == "mnl") {
                $jock->location = '<div class="badge badge-primary">Manila</div>';
            } else if($jock->Employee->location == "cbu") {
                $jock->location = '<div class="badge badge-warning">Cebu</div>';
            } else if($jock->Employee->location == "dav") {
                $jock->location = '<div class="badge badge-dark">Davao</div>';
            }

		    if($jock->moniker == null) {
		        $jock->moniker = "--";
            }

		    $jock->options = '' .
                '<div class="btn-group">' .
                '   <a href="'.route('jocks.show', $jock->id).'" class="btn btn-outline-dark"><i class="fas fa-search"></i></a>' .
                '   <a href="#delete-jock-modal" id="delete-jock-modal" data-id="'.$jock->id.'" data-route="'.route('jocks.update', $jock->id).'" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-trash-alt"></i></a>' .
                '</div>';
        }

		$level = Auth::user()->Employee->Designation->level;

        if ($level >= 1 && $level <= 4) {
            if($request->ajax()) {
                return response()->json($jocks);
            }

            return view('_cms.system-views.employees.jocks.index',compact('employees','jocks'));
        }

        if ($level === 5 || $level === 8) {
            $jock = Jock::with('Fact', 'Image', 'Link', 'Show')
                ->where('employee_id', Auth::user()->Employee->id)
                ->first();

            if (!$jock) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Restricted Access'
                ], 400);
            }

            return view('_cms.system-views.employeeUI.Jocks.index', compact('jock'));
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function create()
	{
        // Obsolete
    }

	public function store(Request $request)
	{
        // Obsolete
    }

	public function show($id, Request $request)
	{
		$jock = Jock::with('Fact', 'Link', 'Image', 'Show', 'Employee.User', 'Employee.Designation')->findOrfail($id);

		$jock['designation_name'] = $jock->Employee->Designation->name;

		$employees = Employee::with('Jock')->whereNull('deleted_at')->get();

		if(!$jock['moniker']) {
		    $jock['moniker'] = "--";
        }

		$level = Auth::user()->Employee->Designation->level;

        foreach ($jock->Image as $image) {
            // Todo: this is how we convert created_at, updated_at and deleted_at
            $image->date_created = Carbon::createFromFormat('Y-m-d H:i:s', $image->created_at)->format('F d, Y h:i A');
            $image->options = '' .
                '<div class="btn-group">' .
                '    <a href="#view-jock-image-modal" data-toggle="modal" data-id="'.$image->id.'" data-link="'.route('jocks.show.image').'" data-open="jocks.image" class="btn btn-outline-dark"><i class="fas fa-eye"></i></a>' .
                '    <a href="#delete-jock-image-modal" data-toggle="modal" data-id="'.$image->id.'" data-link="'.route('jocks.show.image').'" data-open="jocks.image" class="btn btn-outline-dark"><i class="fas fa-trash-alt"></i></a>' .
                '</div>';
        }

        foreach($jock->Link as $link) {
            $link->options = '' .
                '<div class="btn-group">' .
                '    <a href="#view-jock-link-modal" data-toggle="modal" data-id="'.$link->id.'" data-link="'.route('jocks.show.link').'" data-open="jocks.link" class="btn btn-outline-dark"><i class="fas fa-eye"></i></a>' .
                '    <a href="#delete-jock-link-modal" data-toggle="modal" data-id="'.$link->id.'" data-link="'.route('jocks.show.link').'" data-open="jocks.link" class="btn btn-outline-dark"><i class="fas fa-trash-alt"></i></a>' .
                '</div>';
        }

        foreach ($jock->Fact as $fact) {
            $fact->options = '' .
                '<div class="btn-group">' .
                '    <a href="#view-jock-fact-modal" data-toggle="modal" data-id="'.$fact->id.'" data-link="'.route('facts.show', $fact->id).'" data-open="jocks.fact" class="btn btn-outline-dark"><i class="fas fa-eye"></i></a>' .
                '    <a href="#delete-jock-fact-modal" data-toggle="modal" data-id="'.$fact->id.'" data-link="'.route('facts.show', $fact->id).'" data-open="jocks.fact" class="btn btn-outline-dark"><i class="fas fa-trash-alt"></i></a>' .
                '</div>';
        }

        $jock['profile_image'] = $this->verifyPhoto($jock['profile_image'], 'jocks');

        $jock['background_image'] = $this->verifyPhoto($jock['background_image'], 'jocks', true);
        $jock['main_image'] = $this->verifyPhoto($jock['main_image'], 'jocks');

        if ($level >= 1 && $level <= 4) {
            if($request->ajax()) {
                if($request->has('jock_info')) {
                    return response()->json(['images' => $jock->Image, 'links' => $jock->Link, 'facts' => $jock->Fact]);
                }
            }

            return view('_cms.system-views.employees.jocks.update', compact('jock', 'employees'));
        }

        return redirect()->back()->withErrors(trans('response.restricted'));
    }

	public function update($id, Request $request) {
		$jock = Jock::with('Employee', 'Fact', 'Image', 'Link')->findOrfail($id);

		$level = Auth::user()->Employee->Designation->level;

        $jock_id = Jock::where('employee_id', Auth::user()->Employee->id)
            ->pluck('id')
            ->first();

		if ($level >= 1 && $level <= 4) {
			$this->validate($request, [
			    'employee_id' => 'required',
			    'name' => 'required|min:2',
			]);
		} elseif ($level === 5) {
		    $this->validate($request, [
                'name' => 'required|min:2',
            ]);
		}

        $request['slug_string'] = Str::studly($request['name']);

        $img = $request->file('image'); // file for Profile Image
        $img1 = $request->file('image1'); // file for Cover Image
        $img2 = $request->file('image2'); // file for the Main Image

        $path = 'images/jocks';

        if($img) {
            $jock['profile_image'] = $this->storePhoto($request, $path, 'jocks', true, true);
            Session::flash('success', 'Jock\'s profile image has been successfully updated');
        }

        if($img1) {
            $jock['background_image'] = $this->storePhoto($request, $path, 'jocks', true, false, true);
            Session::flash('success', 'Jock\'s header image has been successfully updated');
        }

        if($img2) {
            $jock['main_image'] = $this->storePhoto($request, $path, 'jocks', true, false, false, true);
            Session::flash('success', 'Jock\'s main image has been successfully updated');
        }

        $jock->update($request->all());

        if ($level === 5) {
            Session::flash('success', 'Your jock profile has been successfully updated');
            return redirect()->route('jocks.profile', $jock_id);
        }

        Session::flash('success', 'Jock information has been updated');
        return redirect()->route('jocks.show', $id);
	}

	public function destroy($id)
	{
		$jock = Jock::with('Image', 'Link', 'Award')->findOrfail($id);

		$jock->is_active = 0;

		Session::flash('success', 'A jock has been successfully set to inactive');
		return redirect()->route('jocks.index');
	}

	public function delete(Request $request){

		$jock = Jock::findOrfail($request['id']);

		$jock->delete();

		Session::flash('success', 'Jock data successfully deleted');
		return redirect()->route('jocks.index');
	}

	public function profile($id) {
		$jock_id = Jock::where('employee_id', Auth::user()->Employee->id)
            ->pluck('id')
            ->first();

        $employee = Employee::findOrFail(Auth::user()->Employee->id);
		$jock = Jock::findOrfail($jock_id);

        $show = DB::table('jock_show')
            ->join('jocks', 'jock_show.jock_id', '=', 'jocks.id')
            ->join('shows', 'jock_show.show_id', '=', 'shows.id')
            ->select('title', 'shows.id')
            ->where('jock_show.jock_id', $jock_id)
            ->get();

		$link = Social::whereNull('deleted_at')
            ->where('jock_id', $jock_id)
            ->get();

		$fact = Fact::whereNull('deleted_at')
            ->where('jock_id', $jock_id)
            ->get();

        $image = Photo::whereNull('deleted_at')
            ->where('jock_id', $jock_id)
            ->get();

        $jock['profile_image'] = $this->verifyPhoto($jock['profile_image'], 'jocks');
        $jock['background_image'] = $this->verifyPhoto($jock['background_image'], 'jocks', true);

		// Getting current user's level
		$level = Auth::user()->Employee->Designation->level;
		if ($level === 5 || $level === 8) {
			return  view('_cms.system-views.employeeUI.Jocks.profile', compact('jock','employee', 'jock_id', 'image','link', 'fact', 'show'));
		}

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function showImage(Request $request) {
        $image = Photo::with('Jock')->findOrFail($request['id']);

        $image->file = $this->verifyPhoto($image->file, 'jocks');

        return response()->json(['image' => $image]);
    }

    // obsolete
	public function addImage(Request $request) {

		$this->validate($request, [
			'file' => ['image', 'required', 'file|size:2048'],
			'name' => 'required'
        ]);

		$img = $request->file('image');
		$path = 'images/jocks';
        $image = new Photo($request->all());

		$imgname = date('Ymd') . '-' . mt_rand() . '.' . $img->getClientOriginalExtension();
		$img->move($path, $imgname);

		$file = 'images/jocks/'.$imgname;

		$this->storeAsset('jocks', $imgname, $file);

		$request['file'] = $imgname;

		$image->save();

		Session::flash('success', 'Image successfully added');
		return redirect()->back();

	}

	public function updateImage(Request $request){
	    $image = Photo::findOrFail($request['id']);

	    $new = $request->file('image');

        $this->validate($request, [
            'file' => ['image', 'file|size:2048']
        ]);

        $path = 'images/jocks';

        if($new) {
            $imgname = date('Ymd') . '-' . mt_rand() . '.' . $new->getClientOriginalExtension();
            $new->move($path, $imgname);

            $file = 'images/jocks/'.$imgname;

            $this->storeAsset('jocks', $imgname, $file);

            $request['file'] = $imgname;

            $image->update($request->all());

            Session::flash('success', 'Image successfully updated');
            return redirect()->back();
        }

        $image->update($request->except('file'));

        Session::flash('success', 'Image name successfully updated');
        return redirect()->back();
    }

	public function removeImage(Request $request){
		$image = Photo::findOrfail($request['id']);

		$image->delete();

		Session::flash('success', 'Image Successfully Removed');
        return redirect()->back();
	}

	public function showLink(Request $request) {
	    $link = Social::with('Jock')->findOrFail($request['id']);

	    return response()->json(['link' => $link]);
    }

	public function addLink(Request $request){

		$this->validate($request, [
		    'jock_id' => 'required',
			'website' => 'required',
			'url' => 'required'
        ]);

		$social = new Social($request->all());

		$social->save();

		Session::flash('success', 'Link successfully added');
        return redirect()->back();
	}

    public function updateLink(Request $request, $id){

	    $link = Social::findOrFail($id);

        $this->validate($request, [
            'jock_id' => 'required',
            'url' => 'required'
        ]);

        $link['url'] = $request->get('url');

        $link->update($request->all());

        Session::flash('success', 'Link successfully updated');
        return redirect()->back();
    }

	public function removeLink($id){
		$link = Social::findOrfail($id);

		$link->delete();

        Session::flash('success', 'Link successfully deleted');
        return redirect()->back();
	}
}
