<?php

namespace App\Traits;

use App\Models\UserLogs;
use App\Support\StationContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait LogsUsers
{
    public function userLog($action, $id, Request $request)
    {
        $request['user_id'] = $id;
        $request['action'] = $action;
        $request['employee_id'] = Auth::user()->Employee->id;
        $request['location'] = app(StationContext::class)->current($request);

        $log = new UserLogs($request->all());
        $log->save();
    }
}
