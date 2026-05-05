<?php

namespace App\Http\Controllers;

use App\Models\MusicAward;
use App\Models\MusicAwardsReleases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MusicAwardsController extends Controller
{
    public function view()
    {
        return view('_cms.system-views.digital.music_awards.index');
    }

    public function index()
    {
        $releases = MusicAwardsReleases::query()
            ->withCount('MusicAward')
            ->latest('release')
            ->get()
            ->map(function ($release) {
                $bannerSrc = ($release->banner_image && $release->banner_image !== 'default.png')
                    ? $this->verifyPhoto($release->banner_image, 'music_awards')
                    : asset('images/_assets/default.png');

                $release->banner_preview = '<img src="'.$bannerSrc.'" alt="banner-image" width="120" class="img-thumbnail">';

                $release->live_status = $release->is_live
                    ? '<span class="badge badge-success">Live</span>'
                    : '<span class="badge badge-secondary">Hidden</span>';

                $release->options = '
                    <div class="btn-group">
                        <a href="#music-award-release-modal"
                           data-toggle="modal"
                           data-action="edit"
                           data-id="'.$release->id.'"
                           data-url="'.route('mma-releases.update', $release->id).'"
                           class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="'.route('mma-awards.page', $release->id).'"
                        class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="#delete-mma-release"
                           data-toggle="modal"
                           data-id="'.$release->id.'"
                           data-name="'.e($release->release).'"
                           data-url="'.route('mma-releases.destroy', $release->id).'"
                           class="btn btn-outline-dark btn-sm"
                           id="delete-mma-release-toggler">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                ';

                return $release;
            });

        return response()->json([
            'releases' => $releases
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'release' => 'required|string|max:255|unique:music_awards_releases,release',
            'banner_image' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'is_live' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 422);
        }

        $payload = [
            'release' => $request->release,
            'is_live' => $request->boolean('is_live'),
        ];

        if ($request->hasFile('banner_image')) {
            $payload['banner_image'] = $this->storePhoto($request, 'images/music_awards', 'music_awards', true);
        } else {
            $payload['banner_image'] = 'default.png';
        }

        MusicAwardsReleases::create($payload);

        return response()->json([
            'status' => 'success',
            'message' => 'Music awards release has been added'
        ], 201);
    }

    public function show($id)
    {
        $release = MusicAwardsReleases::with('MusicAward')->find($id);

        if (!$release) {
            return response()->json([
                'status' => 'error',
                'message' => 'No release found'
            ], 422);
        }

        if ($release->banner_image && $release->banner_image !== 'default.png') {
            $release->banner_image = $this->verifyPhoto($release->banner_image, 'music_awards');
        }

        return response()->json([
            'release' => $release
        ]);
    }

    public function update($id, Request $request)
    {
        $release = MusicAwardsReleases::find($id);

        if (!$release) {
            return response()->json([
                'status' => 'error',
                'message' => 'No release found'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'release' => 'required|string|max:255|unique:music_awards_releases,release,'.$id,
            'banner_image' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'is_live' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 422);
        }

        $payload = [
            'release' => $request->release,
            'is_live' => $request->boolean('is_live'),
        ];

        if ($request->hasFile('banner_image')) {
            $payload['banner_image'] = $this->storePhoto($request, 'images/music_awards', 'music_awards');
        }

        $release->update($payload);

        return response()->json([
            'status' => 'success',
            'message' => 'Music awards release has been updated'
        ]);
    }

    public function destroy($id)
    {
        $release = MusicAwardsReleases::find($id);

        if (!$release) {
            return response()->json([
                'status' => 'error',
                'message' => 'No release found'
            ], 422);
        }

        $release->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Music awards release has been deleted'
        ]);
    }

    public function releaseAwards($id)
    {
        $release = MusicAwardsReleases::with([
            'MusicAward',
            'MusicAward.Artist',
            'MusicAward.Album.Artist',
            'MusicAward.Song.Album.Artist'
        ])->find($id);

        if (!$release) {
            return response()->json([
                'status' => 'error',
                'message' => 'No release found'
            ], 422);
        }

        $awards = $release->MusicAward->map(function ($award) {
            $awardee = '';

            if ($award->artist_id && $award->Artist) {
                $awardee = $award->Artist->name;
                $award->award_type = 'artist';
            } elseif ($award->album_id && $award->Album) {
                $awardee = $award->Album->name;
                $award->award_type = 'album';
            } elseif ($award->song_id && $award->Song) {
                $awardee = $award->Song->name;
                $award->award_type = 'song';
            } else {
                $award->award_type = '';
            }

            $imageSrc = ($award->image && $award->image !== 'default.png')
                ? $this->verifyPhoto($award->image, 'music_awards')
                : asset('images/_assets/default.png');

            $award->awardee = $awardee;
            $award->featured_status = $award->is_featured
                ? '<span class="badge badge-primary">Yes</span>'
                : '<span class="badge badge-light">No</span>';
            $award->image_preview = '<img src="'.$imageSrc.'" alt="award-image" width="70" class="img-thumbnail">';
            $award->options = '
                <div class="btn-group">
                    <a href="#music-award-modal"
                    data-toggle="modal"
                    data-action="edit"
                    data-id="'.$award->id.'"
                    data-url="'.route('mma-awards.update', $award->id).'"
                    class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="#delete-mma"
                    data-toggle="modal"
                    data-id="'.$award->id.'"
                    data-name="'.e($award->award_name).'"
                    data-url="'.route('mma-awards.destroy', $award->id).'"
                    class="btn btn-outline-dark btn-sm"
                    id="delete-mma-toggler">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            ';

            return $award;
        });

        return response()->json([
            'awards' => $awards,
            'release' => $release->release
        ]);
    }

    public function releaseAwardsPage($id)
    {
        $release = MusicAwardsReleases::find($id);

        if (!$release) {
            abort(404);
        }

        return view('_cms.system-views.digital.music_awards.awards', compact('release'));
    }

    public function storeAward($id, Request $request)
    {
        $release = MusicAwardsReleases::find($id);

        if (!$release) {
            return response()->json([
                'status' => 'error',
                'message' => 'No release found'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'award_name' => 'required|string|min:3|max:255',
            'artist_id' => 'nullable|integer',
            'album_id' => 'nullable|integer',
            'song_id' => 'nullable|integer',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 422);
        }

        $payload = $this->buildAwardPayload($request, false);
        $payload['music_awards_releases_id'] = $release->id;

        if ($request->hasFile('image')) {
            $payload['image'] = $this->storePhoto($request, 'images/music_awards', 'music_awards', true);
        } else {
            $payload['image'] = 'default.png';
        }

        MusicAward::create($payload);

        return response()->json([
            'status' => 'success',
            'message' => 'Award has been added'
        ], 201);
    }

    public function showAward($awardId)
    {
        $award = MusicAward::with(['Artist', 'Album.Artist', 'Song.Album.Artist'])->find($awardId);

        if (!$award) {
            return response()->json([
                'status' => 'error',
                'message' => 'No award found'
            ], 422);
        }

        if ($award->image && $award->image !== 'default.png') {
            $award->image = $this->verifyPhoto($award->image, 'music_awards');
        }

        if ($award->artist_id) {
            $award->award_type = 'artist';
        } elseif ($award->album_id) {
            $award->award_type = 'album';
        } elseif ($award->song_id) {
            $award->award_type = 'song';
        } else {
            $award->award_type = '';
        }

        return response()->json([
            'award' => $award
        ]);
    }

    public function updateAward($awardId, Request $request)
    {
        $award = MusicAward::find($awardId);

        if (!$award) {
            return response()->json([
                'status' => 'error',
                'message' => 'No award found'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'award_name' => 'required|string|min:3|max:255',
            'artist_id' => 'nullable|integer',
            'album_id' => 'nullable|integer',
            'song_id' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 422);
        }

        $payload = $this->buildAwardPayload($request, true);

        if ($request->hasFile('image')) {
            $payload['image'] = $this->storePhoto($request, 'images/music_awards', 'music_awards', true);
        }

        $award->update($payload);

        return response()->json([
            'status' => 'success',
            'message' => 'Award has been updated'
        ]);
    }

    public function destroyAward($awardId)
    {
        $award = MusicAward::find($awardId);

        if (!$award) {
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

    private function buildAwardPayload(Request $request, bool $isUpdate = false): array
    {
        return [
            'award_name' => $request->award_name,
            'artist_id' => blank($request->artist_id) ? null : $request->artist_id,
            'album_id' => blank($request->album_id) ? null : $request->album_id,
            'song_id' => blank($request->song_id) ? null : $request->song_id,
            'is_featured' => $request->boolean('is_featured'),
        ];
    }
}