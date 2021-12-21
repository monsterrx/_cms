@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                {{ $wallpaper->wallpaperName }}
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <a href="{{ route('wallpapers.index') }}" class="btn btn-outline-dark fa-pull-left"><i class="fas fa-arrow-left"></i>  Back</a>
                        </div>
                    </div>

                    <div class="my-3"></div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img id="wallpaperImage" src="{{ url('images/wallpapers/'. $wallpaper->image) }}" alt="{{ $wallpaper->name }}" class="img-fluid" width="450px">
                        </div>
                    </div>

                    <div class="my-3">
                        <form id="wallpaperUpdateForm" method="POST" action="{{ route('wallpapers.update', $wallpaper->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="wallpaperName">Wallpaper Name</label>
                                        <input type="text" id="wallpaperName" name="name" class="form-control" placeholder="{{ $wallpaper->name }}" value="{{ $wallpaper->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="device">Device Type</label>
                                        <select id="device" name="device" class="custom-select">
                                            <option value>--</option>
                                            @if($wallpaper->device === 'web')
                                                <option value="web" selected>Desktop</option>
                                                <option value="mobile">Mobile</option>
                                            @elseif($wallpaper->device === 'mobile')
                                                <option value="web">Desktop</option>
                                                <option value="mobile" selected>Mobile</option>
                                            @else
                                                <option value="web">Desktop</option>
                                                <option value="mobile">Mobile</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Station</label>
                                        <select id="location" name="location" class="custom-select">
                                            <option value>--</option>
                                            @if($wallpaper->location === 'mnl')
                                                <option value="mnl" selected>Manila</option>
                                                <option value="cbu">Cebu</option>
                                                <option value="dav">Davao</option>
                                            @elseif($wallpaper->location === 'cbu')
                                                <option value="mnl">Manila</option>
                                                <option value="cbu" selected>Cebu</option>
                                                <option value="dav">Davao</option>
                                            @elseif($wallpaper->location === 'dav')
                                                <option value="mnl">Manila</option>
                                                <option value="cbu">Cebu</option>
                                                <option value="dav" selected>Davao</option>
                                            @else
                                                <option value="mnl">Manila</option>
                                                <option value="cbu">Cebu</option>
                                                <option value="dav">Davao</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col"></div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="file" name="image" id="image" class="custom-file-input">
                                        <label class="custom-file-label" for="Image">Wallpaper Image</label>
                                    </div>
                                </div>
                                <div class="col"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group fa-pull-right">
                                        <button type="submit" class="btn btn-outline-dark">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
