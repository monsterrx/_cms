<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\WebsiteMessageReply;
use App\Models\Message;
use App\Support\DesignationNavigation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageReplyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Message $message): JsonResponse
    {
        abort_unless(app(DesignationNavigation::class)->canView($request->user(), 'utilities', 'messages'), 403);
        $validated = $request->validate(['reply' => ['required', 'string', 'max:10000']]);

        Mail::to($message->email)->send(new WebsiteMessageReply($message, $validated['reply']));
        $message->update(['is_seen' => 1]);

        return $this->successResponse(null, 'Reply sent successfully.');
    }
}
