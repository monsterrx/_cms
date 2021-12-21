<?php

namespace App\Traits;

use App\Models\UserLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait LogsUsers {
    public function userLog($action, $id, Request $request) {
        $request['user_id'] = $id;
        $request['action'] = $action;
        $request['employee_id'] = Auth::user()->Employee->id;
        $request['location'] = env('STATION_CODE');

        $log = new UserLogs($request->all());
        $log->save();
    }
}
