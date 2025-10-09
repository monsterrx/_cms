<?php

namespace App\Http\Controllers;

use App\Traits\AssetProcessors;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Title;
use Exception;
use Illuminate\Support\Facades\Validator;

class MobileAppAssetController extends Controller
{
    use AssetProcessors, MediaProcessors, SystemFunctions;

    public function index(Request $request) {
        $monster_assets = Title::with('Asset')
            ->get();

        if ($request->ajax()) {
            return response()->json($monster_assets);
        }

        return view('_cms.system-views.digital.mobileApp.index', compact('monster_assets'));
    }

    public function show($id, Request $request) {
        try {
            $title = Title::with('Asset')
                ->findOrFail($id);

            foreach ($title->Asset as $asset) {
                $asset->logo = $this->verifyMobileAsset($asset->logo);
                $asset->chart_icon = $this->verifyMobileAsset($asset->chart_icon);
                $asset->article_icon = $this->verifyMobileAsset($asset->article_icon);
                $asset->podcast_icon = $this->verifyMobileAsset($asset->podcast_icon);
                $asset->article_page_icon = $this->verifyMobileAsset($asset->article_page_icon);
                $asset->youtube_page_icon = $this->verifyMobileAsset($asset->youtube_page_icon);
            }
        } catch (ModelNotFoundException $exception) {
            return redirect()
                ->back()
                ->withErrors($exception->getMessage());
        }

        if (isset($request['refresh']) && $request['refresh'] == 1) {
            return redirect()
                ->back()
                ->with('success', 'Assets has been successfully refreshed!');
        }

        return view('_cms.system-views.digital.mobileApp.show', compact('title'));
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
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors()->all());
        }

        $new_monster_asset = new Title($request->all());
        $new_monster_asset->save();

        return view('_cms.system-views.digital.mobileApp.show', compact('monster_asset'));
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
            'youtube_main_page_title' => 'required',
            'asset_type' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors()->all());
        }

        try {
            $monster_asset = Title::query()
                ->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return redirect()
                ->back()
                ->withErrors($exception->getMessage());
        }

        if ($request['asset_type'] === 'charts') {
            // TODO: Image upload has been separate, see public function upload
            $monster_asset->fill($request->only('chart_title', 'chart_subtitle'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return redirect()->back()->with('success', 'Mobile application chart assets for this station has been updated');
        }

        if ($request['asset_type'] === 'articles') {
            $monster_asset->fill($request->only('article_title', 'article_sub_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return redirect()->back()->with('success', 'Mobile application article assets for this station has been updated');
        }

        if ($request['asset_type'] === 'podcasts') {
            $monster_asset->fill($request->only('podcast_title', 'podcast_sub_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return redirect()->back()->with('success', 'Mobile application podcast assets for this station has been updated');
        }

        if ($request['asset_type'] === 'articlesMain') {
            $monster_asset->fill($request->only('articles_main_page_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return redirect()->back()->with('success', 'Mobile application main article assets for this station has been updated');
        }

        if ($request['asset_type'] === 'podcastsMain') {
            $monster_asset->fill($request->only('articles_main_page_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return redirect()->back()->with('success', 'Mobile application main podcast assets for this station has been updated');
        }

        if ($request['asset_type'] === 'youtube') {
            $monster_asset->fill($request->only('youtube_main_page_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return redirect()->back()->with('success', 'Mobile application youtube assets for this station has been updated');
        }

        return redirect()->back()->withErrors('Unknown asset type, please coordinate to the IT - Developer');
    }

    public function destroy($id) {
        try {
            $monster_asset = Title::with('Asset')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return redirect()
                ->back()
                ->withErrors($exception->getMessage());
        }

        $monster_asset->delete();

        return view('_cms.system-views.digital.mobileApp.index')->with('success', 'Mobile asset has been deleted!');
    }

    public function uploadImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'asset_type' => 'required',
            'image' => ['mimes:jpg,jpeg,png,webp', 'file']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all());
        }

        $path = 'images/_assets/mobile';
        $directory = '_assets/mobile';

        // For icon upload.
        $icon = $this->storePhoto($request, $path, $directory);
        $asset_type = $request['asset_type'];

        try {
            $asset = Title::with('Asset')->findOrFail($request['id']);

            if ($asset_type == "main logo") {
                $asset['logo'] = $icon;
                $asset->save();
            } else if ($asset_type == "charts") {
                $asset['chart_icon'] = $icon;
                $asset->save();
            } else if ($asset_type == "articles") {
                $asset['article_icon'] = $icon;
                $asset->save();
            } else if ($asset_type == "podcasts") {
                $asset['podcast_icon'] = $icon;
                $asset->save();
            } else if ($asset_type == "articlesMain") {
                $asset['article_page_icon'] = $icon;
                $asset->save();
            } else if ($asset_type == "youtube") {
                $asset['youtube_page_icon'] = $icon;
                $asset->save();
            }
        } catch (Exception $exception) {
            return redirect()
                ->back()
                ->withErrors($exception->getMessage());
        }

        return redirect()
            ->back()
            ->with('success', 'Icon for '. $request['asset_type'] . ' has been uploaded!');
    }
}
