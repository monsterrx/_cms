@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Mobile App Assets
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-lg-12">
                    <a href="#new-asset" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Asset</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="mobileAppAssetTable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Logo</th>
                                        <th>Chart Icon</th>
                                        <th>Articles Icon</th>
                                        <th>Podcast Icon</th>
                                        <th>Article Page Icon</th>
                                        <th>YouTube Page Icon</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monster_assets as $asset)
                                        <tr data-href="{{ route('asset.show', $asset->id) }}" onclick="viewData()">
                                            <td>{{ $asset->id }}</td>
                                            <td>
                                                <img src="{{ $asset->logo }}" class="img-fluid" width="100px" alt="main-logo">
                                            </td>
                                            <td>
                                                <img src="{{ $asset->chart_icon }}" class="img-fluid" width="100px" alt="chart-icon">
                                            </td>
                                            <td>
                                                <img src="{{ $asset->article_icon }}" class="img-fluid" width="100px" alt="article-icon">
                                            </td>
                                            <td>
                                                <img src="{{ $asset->podcast_icon }}" class="img-fluid" width="100px" alt="podcast-icon">
                                            </td>
                                            <td>
                                                <img src="{{ $asset->article_page_icon }}" class="img-fluid" width="100px" alt="article-page-icon">
                                            </td>
                                            <td>
                                                <img src="{{ $asset->youtube_page_icon }}" class="img-fluid" width="100px" alt="youtube-page-icon">
                                            </td>
                                            <td>
                                                @if($asset->location === "mnl")
                                                    <div class="badge badge-primary">Manila</div>
                                                @elseif($asset->location === "cbu")
                                                    <div class="badge badge-warning">Cebu</div>
                                                @else
                                                    <div class="badge badge-dark">Davao</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="new-asset" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Assets" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Mobile App Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="wallpaperForm" method="POST" action="{{ route('asset.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="wallpaperName">Wallpaper Name</label>
                                    <input type="text" id="wallpaperName" name="name" placeholder="Wallpaper Name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="device">Device Type</label>
                                    <select id="device" name="device" class="custom-select">
                                        <option value>--</option>
                                        <option value="mobile">Mobile</option>
                                        <option value="web">Desktop</option>
                                    </select>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="image" id="image" class="custom-file-input">
                                    <label class="custom-file-label" for="image">Wallpaper Image</label>
                                </div>
                            </div>
                        </div>
                        <div class="my-3"></div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
