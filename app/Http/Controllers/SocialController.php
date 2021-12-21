<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Validator;

class SocialController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'website' => 'required|string|min:4',
            'url' => 'required|string'
        ]);

        if ($validator->fails()) {
            session()->flash('errors', $validator->errors());
            return redirect()->back();
        }

        $social = new Social($request->all());
        $social->save();

        session()->flash('success', 'Social link has been added!');
        return redirect()->back();
    }

    public function update($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'website' => 'string|min:6',
            'url' => 'string'
        ]);

        if ($validator->fails()) {
            session()->flash('errors', $validator->errors());
            return redirect()->back();
        }

        $social = Social::with('Jock', 'Article', 'Show')->findOrFail($id);
        $social->update($request->all());

        session()->flash('success', 'Social link has been updated!');
        return redirect()->back();
    }

    public function destroy($id) {
        $social = Social::with('Jock', 'Article', 'Show')->findOrFail($id);

        $social->delete();

        session()->flash('success', 'Social link has been removed!');
        return redirect()->back();
    }
}
