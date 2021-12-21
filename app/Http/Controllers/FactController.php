<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use App\Models\Jock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FactController extends Controller {

    public function index()
    {
        $jock_id = Jock::where('employee_id', Auth::user()->Employee->id)->pluck('id');

        $facts = Fact::where('jock_id', $jock_id)->get();

        // Getting current user's level
        $level = Auth::user()->Employee->Designation->level;
        if ($level === '5') {
            return response()->json(['jock_id' => $jock_id, 'facts' => $facts]);
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function show(Request $request) {
        $facts = Fact::findOrFail($request['id']);

        return response()->json(['fact' => $facts]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'jock_id' => 'required',
            'content' => 'required|max:255'
        ]);

        $fact = new Fact($request->all());
        $fact->save();

        session()->flash('success', 'Fact has been successfully added!');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'jock_id' => 'required',
            'content' => 'required|max:255'
        ]);

        $fact = Fact::with('Jock')->findOrfail($id);

        $fact->update($request->all());

        Session::flash('success', 'Fact has been successfully updated!');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $fact = Fact::with('Jock')->findOrfail($id);

        $fact->delete();

        Session::flash('success', 'Fact has been successfully removed!');
        return redirect()->back();
    }
}
