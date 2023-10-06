<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Employee;
use App\Models\Jock;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller {

    public function index(Request $request)
    {
        if($request->ajax()) {
            $users = User::with('Employee.Designation')
                ->whereNull('deleted_at')
                ->where('id', '!=', Auth::user()->id)
                ->get();

            foreach ($users as $user) {
                switch($user->Employee->location) {
                    case 'dav':
                        $user->Employee->location = '<div class="badge badge-dark">Davao</div>';
                        break;
                    case 'cbu':
                        $user->Employee->location = '<div class="badge badge-warning">Cebu</div>';
                        break;
                    default:
                        $user->Employee->location = '<div class="badge badge-primary">Manila</div>';
                        break;
                }

                $user->updated_at = date('Y-m-d', strtotime($user->updated_at));

                $user->name = $user->Employee->first_name . ' ' . $user->Employee->last_name;
            }

            return response()->json($users);
        }

        $designation = Designation::orderBy('level')->get();

        return view('_cms.system-views.users.index', compact( 'designation'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('_cms.system-views.users.show', compact('user'));
    }

    public function profile($employeeNumber, Request $request)
    {
        if($request->ajax()) {
            $employee = Employee::where('employee_number', '=', $employeeNumber)
                ->get()
                ->first();

            return view('_cms.system-views.employeeUI.userInformation', compact('employee'));
        }

        $employee = Employee::where('employee_number', '=', $employeeNumber)->get()->first();
        $designation = Designation::where('id', '!=', Auth::user()->Employee->Designation->id)->orderBy('level')->get();

        return view('_cms.system-views.employeeUI.profile', compact('employee', 'designation'));
    }

    public function changeHeader($jock_id) {
        $jock = Jock::with('Show', 'Employee')->findOrFail($jock_id);

        return view('_cms.system-views.employees.jocks.header', compact('jock'));
    }

    public function changePassword(Request $request){
        $user = User::findOrfail(Auth::user()->id);

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => 'required'
        ]);

        $current = $request['current_password'];

        if(Hash::check($current, $user['password'])) {
            $user->update([
                'password' => Hash::make($request['password'])
            ]);

            Session::flash('success', 'Password successfully changed');
            return redirect()->route('users.profile', Auth::user()->Employee->employee_number);
        }

        return redirect()->back()->withErrors('Incorrect Password');
    }

    public function saveProfileImageToDatabase(Request $request)
    {
        $level = Auth::user()->Employee->Designation->level;

        try {
            $jock = Jock::findOrFail($request['jock_id']);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json('error', 404);
        }

        $imageName = $request['imageName'];

        $file = 'images/jocks/'. $imageName;

        $this->storeAsset('jocks', $imageName, $file);

        $jock['profile_image'] = $imageName;
        $jock->save();

        Session::flash('success', 'Profile picture has been successfully saved!');
        if ($level === 1 || $level === 2) {
            return redirect()->route('jocks.show', $jock['id']);
        }

        return redirect()->route('jocks.profile', $jock['id']);
    }

    public function addProfile(Request $request) {
        $data = $request['image'];

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);
        $imageName = date('Ymd').'-'. mt_rand() . '.png';

        $path = 'images/jocks/'. $imageName;

        file_put_contents($path, $data);

        return $imageName;
    }

    public function saveHeaderImageToDatabase(Request $request)
    {
        $level = Auth::user()->Employee->Designation->level;

        try {
            $jock = Jock::findOrFail($request['jock_id']);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json('error', 404);
        }

        $headerImageName = $request['headerImageName'];

        $file = 'images/jocks/'. $headerImageName;

        $this->storeAsset('jocks', $headerImageName, $file);

        $jock['background_image'] = $headerImageName;
        $jock->save();

        Session::flash('success', 'Header picture has been successfully saved!');

        if ($level === 1 || $level === 2) {
            return redirect()->route('users.header', $jock['id']);
            //return response()->json(['success' => 'Image Uploaded to Database'], 200);
        }

        return redirect()->route('jocks.profile', $jock['id']);
        //return response()->json(['success' => 'Image Uploaded to Database'], 200);
    }

    public function saveMainImageToDatabase(Request $request) {
        $level = Auth::user()->Employee->Designation->level;

        try {
            $jock = Jock::findOrFail($request['jock_id']);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json('error', 404);
        }

        $mainImageName = $request['mainImageName'];

        $file = 'images/jocks/' . $mainImageName;

        $this->storeAsset('jocks', $mainImageName, $file);

        $jock['main_image'] = $mainImageName;
        $jock->save();

        Session::flash('success', 'Main picture has been successfully saved!');
        if ($level === 1 || $level === 2) {

            return redirect()->route('jocks.show', $jock['id']);
            //return response()->json(['success' => 'Image Uploaded to Database'], 200);
        }

        return redirect()->route('jocks.profile', $jock['id']);
    }
}
