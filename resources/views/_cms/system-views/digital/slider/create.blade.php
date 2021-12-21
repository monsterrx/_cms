@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Add an Image as Header
            </div>
            <div class="my-3"></div>
            @include('_cms.system-views._feedbacks.error')
            @include('_cms.system-views._feedbacks.success')
            <hr class="my-4">
            <form action="{{ route('sliders.store') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image">
                            <label for="image" class="custom-file-label">Recommended 1024x355 Image Size 2MB Below</label>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-info"></i>
                                </div>
                            </div>
                            <input type="text" id="slider_title" class="form-control" name="slider_title" placeholder="Slider Title">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-font"></i>
                                </div>
                            </div>
                            <input type="text" id="slider_sub_title" class="form-control" name="slider_sub_title" placeholder="Slider Subtitle">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="link" class="label">Link</label>
                            <input type="text" id="link" name="link" class="form-control" placeholder="If you want a redirecting page on a slider">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="btn-group fa-pull-right">
                            <a href="{{ route('sliders.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                            <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
