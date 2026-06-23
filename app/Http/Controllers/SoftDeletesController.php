<?php namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Support\Facades\Session;

class SoftDeletesController extends Controller {

    public function index()
    {
        return view('_cms.system-views.recovery.index');
    }

    public function restoreData($model = [], $id = null, $retrieve = true, $recover = false) {
        if($model) {
            $data = $retrieve ? Award::onlyTrashed()->get() : Award::withTrashed()->findOrFail($id);
        } else {
            return redirect()->route('home')->with('errors', 'Something went wrong, please contact IT - Developer');
        }

        if($retrieve) {
            return $data;
        }

        $data->restore();

        session()->flash('success', $model . 'data has been successfully stored');
        return view();
    }
}
