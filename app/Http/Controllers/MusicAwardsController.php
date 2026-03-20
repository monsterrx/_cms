<?php

namespace App\Http\Controllers;

use App\Models\MusicAward;
use Exception;
use Illuminate\Http\Request;

class MusicAwardsController extends Controller
{
    public function index()
    {
        $musicAwards = MusicAward::query()
            ->all();

        return response()->json([
            'awards' => $musicAwards
        ]);
    }
    
    public function store(Request $request)
    {
        $rules = [
            'award_name' => 'required|min:6',
            'award_type' => 'required',
            'image' => 'required'
        ];

        $award_type = $request->get('award_type');

        if ($award_type == 'artist') {
            $artist_rules = [
                'artist_id' => 'required'
            ];

            array_push($rules, $artist_rules);
        }

        if ($award_type == 'album') {
            $artist_rules = [
                'album_id' => 'required'
            ];

            array_push($rules, $artist_rules);
        }

        if ($award_type == 'song') {
            $artist_rules = [
                'song_id' => 'required'
            ];

            array_push($rules, $artist_rules);
        }

        $request->validate($rules);

        MusicAward::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Award has been added'
        ], 201);
    }

    public function show($id)
    {
        try {
            $award = MusicAward::find($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No award found'
            ], 422);
        }

        return response()->json([
            'award' => $award
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $award = MusicAward::find($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No award found'
            ], 422);
        }

        $rules = [
            'award_name' => 'required|min:6',
            'award_type' => 'required',
            'image' => 'required'
        ];

        $award_type = $request->get('award_type');

        if ($award_type == 'artist') {
            $artist_rules = [
                'artist_id' => 'required'
            ];

            array_push($rules, $artist_rules);
        }

        if ($award_type == 'album') {
            $artist_rules = [
                'album_id' => 'required'
            ];

            array_push($rules, $artist_rules);
        }

        if ($award_type == 'song') {
            $artist_rules = [
                'song_id' => 'required'
            ];

            array_push($rules, $artist_rules);
        }

        $request->validate($rules);

        $award->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Award has been updated'
        ]);
    }

    public function destroy($id)
    {
        try {
            $award = MusicAward::find($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No award found'
            ], 422);
        }

        $award->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Award has been deleted'
        ]);
    }
}
