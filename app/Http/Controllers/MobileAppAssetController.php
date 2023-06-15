<?php

namespace App\Http\Controllers;

use App\Traits\AssetProcessors;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Asset;
use Exception;
use Illuminate\Support\Facades\Validator;

class MobileAppAssetController extends Controller
{
    use AssetProcessors, MediaProcessors, SystemFunctions;

    public function index(Request $request) {
        $monster_assets = Asset::all();

        foreach ($monster_assets as $monster_asset) {
            $monster_asset['logo'] = $this->verifyMobileAsset($monster_asset['logo']);
            $monster_asset['chart_icon'] = $this->verifyMobileAsset($monster_asset['chart_icon']);
            $monster_asset['article_icon'] = $this->verifyMobileAsset($monster_asset['article_icon']);
            $monster_asset['podcast_icon'] = $this->verifyMobileAsset($monster_asset['podcast_icon']);
            $monster_asset['article_page_icon'] = $this->verifyMobileAsset($monster_asset['article_page_icon']);
            $monster_asset['youtube_page_icon'] = $this->verifyMobileAsset($monster_asset['youtube_page_icon']);
        }

        return view('_cms.system-views.digital.mobileApp.index', compact('monster_assets'));
    }

    public function show($id, Request $request) {
        try {
            $monster_asset = Asset::with('Title')->findOrFail($id);

            $monster_asset->logo = $this->verifyMobileAsset($monster_asset['logo']);
            $monster_asset->chart_icon = $this->verifyMobileAsset($monster_asset['chart_icon']);
            $monster_asset->article_icon = $this->verifyMobileAsset($monster_asset['article_icon']);
            $monster_asset->podcast_icon = $this->verifyMobileAsset($monster_asset['podcast_icon']);
            $monster_asset->article_page_icon = $this->verifyMobileAsset($monster_asset['article_page_icon']);
            $monster_asset->youtube_page_icon = $this->verifyMobileAsset($monster_asset['youtube_page_icon']);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }

        if (isset($request['refresh']) && $request['refresh'] == 1) {
            return redirect()->back()->with('success', 'Assets has been successfully refreshed!');
        }

        return view('_cms.system-views.digital.mobileApp.show', compact('monster_asset'));
    }

    public function store(Request $request) {
        $request['location'] = $this->getStationCode();

        $validator = Validator::make($request->all(), [
            'logo' => 'required',
            'chart_icon' => 'required',
            'article_icon' => 'required',
            'podcast_icon' => 'required',
            'article_page_icon' => 'required',
            'youtube_page_icon' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all());
        }

        $website_entry = Asset::all()
            ->where('location', '=', $this->getStationCode())
            ->count();

        if ($website_entry > 0) {
            return redirect()->back()->withErrors('This website already has an existing entry.');
        }

        $new_monster_asset = new Asset($request->all());

        $new_monster_asset->save();

        $monster_asset = Asset::with('Title')->latest()->get()->take(1);

        return view('_cms.system-views.digital.mobileApp.show', compact('monster_asset'));
    }

    public function update($id, Request $request) {
        $request['location'] = $this->getStationCode();

        $validator = Validator::make($request->all(), [
            'asset_type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all());
        }

        try {
            $monster_asset = Asset::with('Title')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
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

        return redirect()->back()->withErrors('Unknown asset type, contact your IT Developer');
    }

    public function destroy($id) {
        try {
            $monster_asset = Asset::with('Title')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
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

        // for icon upload.
        $icon = $this->storePhoto($request, $path, $directory);
        $asset_type = $request['asset_type'];

        try {
            $asset = Asset::with('Title')->findOrFail($request['id']);

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
            return redirect()->back()->withErrors($exception->getMessage());
        }

        return redirect()->back()->with('success', 'Icon for '. $request['asset_type'] . ' has been uploaded!');
    }
}
