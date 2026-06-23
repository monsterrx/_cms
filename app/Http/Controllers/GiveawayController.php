<?php namespace App\Http\Controllers;

use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class GiveawayController extends Controller {

	public function index()
	{
		$giveaways = Contest::with('Contestant')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->get();

		return view('_cms.system-views.monsterGiveaway.giveaway.index', compact('giveaways'));
	}

	public function create()
	{
		//
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'image' => 'image|required|max:2048',
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'is_restricted' => 'required',
            'code' => 'required'
        ]);

		if($validator->passes()) {
		    $img = $request->file('image');
            $path = 'images/giveaways';

            $giveaway = new Contest($request->all());

            if($img) {
                $giveaway['image'] = $this->storePhoto($request, $path, 'giveaways', false);
            } else {
                $giveaway['image'] = 'default.png';
            }

            $giveaway->save();

            return redirect()->route('giveaways.show', $giveaway['id']);
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id)
	{
        $giveaway = Contest::findOrFail($id);

        $giveaway['eventImage'] = url('images/giveaways/'.$giveaway['eventImage']);

		return view('_cms.system-views.monsterGiveaway.giveaway.show', compact('giveaway'));
	}

	public function edit(Request $request, $id)
	{
		//
	}

	public function update(Request $request, $id)
	{
	    $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'is_restricted' => 'required',
            'code' => 'required'
        ]);

        if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/giveaways';

            $giveaway = Contest::with('Contestant')->findOrFail($id);

            if($img){
                $giveaway['image'] = $this->storePhoto($request, $path, 'giveaways', false);
                $giveaway->save();
            } else {
                $giveaway->update($request->except('image'));
            }

            return redirect()->back()->with('success', 'Giveaway has been updated successfully');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function destroy($id, Request $request)
	{
		$giveaway = Contest::findOrFail($id);

		$status = $request->get('active');
		$giveaway->active = $status;
		$giveaway->save();

		return redirect()->back()->with('success', 'Giveaway has been deactivated!');
	}

	public function activate($id, Request $request)
    {
        $giveaway = Contest::findOrFail($id);

        $status = $request->get('active');
        $giveaway->active = $status;
        $giveaway->save();

        return redirect()->back()->with('success', 'Giveaway has been activated!');
    }
}
