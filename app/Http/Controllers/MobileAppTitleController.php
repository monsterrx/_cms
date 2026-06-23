<?php

namespace App\Http\Controllers;

use App\Models\Title;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobileAppTitleController extends Controller
{
    use SystemFunctions;

    public function index() {
        $monster_titles = Title::all();

        return response()->json([
            'monster_titles' => $monster_titles
        ]);
    }

    public function show($id) {
        try {
            $monster_titles = Title::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'monster_title' => $monster_titles
        ]);
    }

    public function store(Request $request) {
        $request['location'] = $this->getStationCode();

        $validator = Validator::make($request->all(), [
            'chart_title' => 'required',
            'chart_sub_title' => 'required',
            'article_title' => 'required',
            'article_sub_title' => 'required',
            'podcast_title' => 'required',
            'podcast_sub_title' => 'required',
            'articles_main_page_title' => 'required',
            'podcast_main_page_title' => 'required',
            'youtube_main_page_title' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $website_entry = Title::all()
            ->where('location', '=', $this->getStationCode())
            ->count();

        if ($website_entry > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'This website already has an existing entry.'
            ], 400);
        }

        $monster_title = new Title($request->all());

        $monster_title->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Mobile app titles for ' . $this->getStationName() . ' has been created!'
        ], 201);
    }

    public function update($id, Request $request) {
        $request['location'] = $this->getStationCode();

        $validator = Validator::make($request->all(), [
            'chart_title' => 'required',
            'chart_sub_title' => 'required',
            'article_title' => 'required',
            'article_sub_title' => 'required',
            'podcast_title' => 'required',
            'podcast_sub_title' => 'required',
            'articles_main_page_title' => 'required',
            'podcast_main_page_title' => 'required',
            'youtube_main_page_title' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        try {
            $monster_title = Title::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        $monster_title->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Mobile app titles for ' . $this->getStationName() . ' has been updated!'
        ]);
    }

    public function destroy($id) {
        try {
            $monster_titles = Title::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        $monster_titles->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mobile app title for ' . $this->getStationName() . ' has been deleted!'
        ]);
    }
}
