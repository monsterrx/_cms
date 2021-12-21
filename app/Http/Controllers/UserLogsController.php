<?php namespace App\Http\Controllers;

use App\Models\UserLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLogsController extends Controller {

	public function index(Request $request)
	{
        $employees = UserLogs::with('User', 'Employee')
            ->whereYear('created_at', date('Y'))
            ->where('location', $this->getStationCode())
            ->orderBy('created_at', 'desc')
            ->get();

        if($request->ajax()) {
            if($request['process'] === 'load') {
                $level = Auth::user()->Employee->Designation->level;
                if($level === '1' || $level === '2') {
                    return view('_cms.system-views.logs.table', compact('employees'));
                }
            }

            return response()->json($employees);
        }

		return view('_cms.system-views.logs.index');
	}
}
