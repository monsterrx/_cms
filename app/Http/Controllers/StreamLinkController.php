<?php

namespace App\Http\Controllers;

use App\Models\StreamLink;
use Exception;
use Illuminate\Http\Request;

class StreamLinkController extends Controller
{
    public function index(Request $request)
    {
        $streamLinks = StreamLink::query()
            ->where('location', $this->getStationCode())
            ->get();

        return response()->json($streamLinks);
    }

    public function show(Request $request)
    {
        $streamLinks = StreamLink::query()
            ->where('location', $this->getStationCode())
            ->get();

        return response()->json($streamLinks);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'url' => 'required',
        ]);

        $request['location'] = $this->getStationCode();

        try {
            $streamingLink = new StreamLink($request->all());
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

        $streamingLink->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Streaming link created successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'url' => 'required',
        ]);

        try {
            $streamingLink = StreamLink::query()->findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Streaming link not found',
            ], 404);
        }


        $streamingLink->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Streaming link updated successfully',
        ]);
    }

    public function destroy($id)
    {
        try {
            $streamingLink = StreamLink::query()->findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Streaming link not found',
            ], 404);
        }

        $streamingLink->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Streaming link deleted successfully',
        ]);
    }
}
