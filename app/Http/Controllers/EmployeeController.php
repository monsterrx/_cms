<?php namespace App\Http\Controllers;

use App\Models\Jock;
use App\Models\User;
use App\Models\Message;
use App\Models\Employee;
use App\Models\Designation;
use Carbon\Carbon;
use App\Traits\LogsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Str;

class EmployeeController extends Controller {

    use LogsUsers;

	public function index(Request $request)
	{
		$employees = Employee::with('User', 'Designation')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('created_at')
            ->get();
		$designation = Designation::whereNull('deleted_at')
            ->orderBy('name')
            ->get();
		$newMessage = Message::where('is_seen', 0)->count();

		foreach ($employees as $employee) {
		    $employee->name = $employee->first_name . ' ' . $employee->last_name;

		    if($employee->location == "mnl") {
		        $employee->location = '<div class="badge badge-primary">Manila</div>';
            } else if($employee->location == "cbu") {
		        $employee->location = '<div class="badge badge-warning">Cebu</div>';
            } else if($employee->location == "dav") {
		        $employee->location = '<div class="badge badge-dark">Davao</div>';
            }

		    $employee->options = '' .
                '<div class="btn-group">' .
                '   <a href="#update_employee_modal" id="update-employee-modal" data-route="'.route('employees.show', $employee->id).'" data-update-route="'.route('employees.update', $employee->id).'" data-delete-route="'.route('employees.destroy', $employee->id).'" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-user-edit"></i></a>' .
                '   <a href="#delete_employee_modal" id="delete-employee-modal" data-route="'.route('employees.show', $employee->id).'" data-update-route="'.route('employees.update', $employee->id).'" data-delete-route="'.route('employees.destroy', $employee->id).'" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-trash-alt"></i></a>' .
                '</div>';
        }

		if($request->ajax()) {
		    return response()->json($employees);
        }

		//getting current user's level
		$level = Auth::user()->Employee->Designation->level;
		if ($level === 1 || $level === 2 || $level === 6) {
			return view('_cms.system-views.employees.index', compact('designation','newMessage'));
		}

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function store(Request $request)
	{
	    $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'designation_id' => 'required',
        ]);

		if($validator->passes()) {
            $employee_number = $this->IdGenerator(8);
            $request['employee_number'] = $employee_number;
            $request['location'] = $this->getStationCode();

            Employee::create($request->all());

            $employee = Employee::where('employee_number', $employee_number)->first();

            // Jock and Jock Admin
            if($request->designation_id === 9 || $request->designation_id === 19 || $request->designation_id === '9' || $request->designation_id === '19')
            {
                $jockName = $request['first_name'] . ' ' . $request['last_name'];

                $jock = new Jock([
                    'employee_id' => Employee::latest()->first()->id,
                    'slug_string' => Str::studly($jockName),
                    'name' => $jockName,
                    'profile_image' => 'default.png',
                    'background_image' => 'default-banner-sm.png',
                    'moniker' => '',
                    'is_active' => '1'
                ]);

                $jock->save();

                $this->userLog('Added a new jock', Auth::user()->id, $request);
            }

            $this->userLog('Added a new employee', Auth::user()->id, $request);

            Session::flash('success', 'A new employee has been successfully added');
            return redirect()->route('employees.index');
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id)
	{
        $employee = Employee::with('Designation')->findOrfail($id);

        $userid = User::with('Employee')
            ->where('employee_id', $employee->id)
            ->first();

        if ($userid) {
            $user = User::with('Employee')
                ->findOrfail($userid['id']);
        } else {
            Session::flash('error', 'User not registered');
            return response()->json($employee);
        }

		//getting current user's level
		$level = Auth::user()->Employee->Designation->level;
		if ($level === 1 || $level === 2 || $level === 6) {
			return response()->json($employee);
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
		    'first_name' => ['required', 'min:2'],
            'last_name' => ['required', 'min:2'],
            'gender' => 'required',
        ]);

		if($validator->passes()) {
            try {
                $employee = Employee::with('Designation')->findOrfail($id);
            } catch(ModelNotFoundException $e) {
                return redirect()->back()->withErrors('Model Error: Data not Found!');
            }

            $employee->update($request->except('employee_number'));

            $this->userLog("Updated an employee's data", Auth::user()->id, $request);

            return response()->json(['status' => 'success', 'message' => 'An employee\'s data has been updated!']);
        }

		return response()->json(['errors' => $validator->errors()->all()], 404);
	}

	public function destroy($id, Request $request)
	{
        $employee = Employee::findOrfail($id);

		$userId  = User::where('employee_number', $employee['employee_number'])->first();

		if ($userId) {
			$user = User::findOrfail($userId->id);
			$user->delete();
		}

        $this->userLog("Deleted an employee's data", Auth::user()->id, $request);

		$employee->delete();

		Session::flash('success', 'An employee has been successfully removed');
		return redirect()->route('employees.index');
	}
}
