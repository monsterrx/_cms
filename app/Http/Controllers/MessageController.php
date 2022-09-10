<?php namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MessageController extends Controller {

	public function index(Request $request)
	{
	    if($request->ajax()) {
	        if($request['message_id']) {
                $message = Message::findOrFail($request['message_id']);

                $status = $request['is_seen'] = 1;

                $message['is_seen'] = $status;
                $message->save();

                return response()->json(['status' => 'success']);
            }

	        $newMessage = Message::where('location', $this->getStationCode())
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();

	        foreach ($newMessage as $message) {
	            if($message->is_seen === 1) {
	                $message->is_seen = '<div class="text-success"><i class="fas fa-check-double"></i>  Seen</div>';
                } else {
	                $message->is_seen = '<div class="text-info"><i class="fas fa-circle"></i>  Unseen</div>';
                }
	            $message->options = "<a href='#view-".$message->id."' id='openMessage' data-id='".$message->id."' class='btn btn-outline-dark' data-toggle='modal'><i class='fas fa-eye'></i></a>";
            }

	        return response()->json($newMessage);
        }

	    $user = Auth::user()->Employee->Designation->level;

        $message = Message::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

		if ($user === 1 || $user === 2 || $user === 6 || $user === 7) {
			return view('_cms.system-views.employeeUI.receptionist.messages', compact('message'));
		}

        return redirect()->back()->withErrors('Restricted Access!');
    }
}
