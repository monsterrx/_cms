@extends('layouts.base')

@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.app.styles.css') }}">
@endsection

@section('scripts')
    <script type="text/javascript">
        // When changing the value from the file input, submit the form.
        $('#main-logo, #chart-logo, #article-logo, #podcast-logo, #article-page-logo, #youtube-logo').on('change', function(e) {
            let id = $(this).attr('id');

            if (id == 'main-logo') {
                $('#logo_submit').click();
            }

            if (id == 'chart-logo') {
                $('#chart_submit').click();
            }

            if (id == 'article-logo') {
                $('#article_submit').click();
            }

            if (id == 'podcast-logo') {
                $('#podcast_submit').click();
            }

            if (id == 'article-page-logo') {
                $('#article_main_submit').click();
            }

            if (id == 'youtube-logo') {
                $('#youtube_submit').click();
            }
        });
    </script>
@endsection

@section('content')
    <div class="my-4">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('asset.index') }}" class="btn btn-outline-dark float-left">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('asset.show', [$monster_asset->id, 'refresh' => true]) }}" class="btn btn-outline-dark float-right">
                    Refresh <i class="fas fa-redo-alt"></i>
                </a>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12">
                            @include('_cms.system-views._feedbacks.success')
                            @include('_cms.system-views._feedbacks.error')
                        </div>
                    </div>

                    <div class="row justify-content-center my-4">
                        <div class="col-12 text-center">
                            <form id="mainLogoForm" action="{{ route('asset.upload-image') }}" method="POST" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-body">
                                        @csrf
                                        <div class="row justify-content-center">
                                            <div class="col-6">
                                                <img src="{{ $monster_asset->logo }}" alt="main-logo" width="300px">
                                            </div>
                                        </div>
                                        <div class="my-4"></div>
                                        <div class="row justify-content-center">
                                            <div class="col-6">
                                                <input type="hidden" name="id" value="{{ $monster_asset->id }}">
                                                <input type="hidden" name="asset_type" value="main logo">
                                                <input type="file" id="main-logo" name="image" accept="image/*">
                                            </div>
                                        </div>
                                        <button id="logo_submit" type="submit" hidden></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card my-4">
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="panel-content">
                                    <div class="panel-header charts-header avatar">
                                        <img src="{{ $monster_asset->chart_icon }}" alt="chart-icon" class="circle charts-logo">
                                        <p class="panel-title charts-title">{{ $monster_asset->title->chart_title }}</p>
                                        <p class="charts-subtitle">{{ $monster_asset->title->chart_sub_title }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="chartLogoForm" action="{{ route('asset.upload-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $monster_asset->id }}">
                                            <input type="hidden" name="asset_type" value="charts">

                                            <div class="row justify-content-center">
                                                <div class="col-12">
                                                    <img src="{{ $monster_asset->chart_icon }}" alt="chart-icon" width="100px">
                                                </div>

                                                <div class="my-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <input type="file" id="chart-logo" name="image" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button id="chart_submit" type="submit" hidden></button>
                                        </form>

                                        <div class="row justify-content-center">
                                            <form id="chartTitleForm" action="{{ route('asset.update', $monster_asset->id) }}" method="POST" class="col-10">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="asset_type" value="charts">

                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="chart_title" name="chart_title" placeholder="Charts Title" value="{{ $monster_asset->title->chart_title }}">
                                                </div>
                                                <div class="form-group">
                                                    <textarea type="text" class="form-control" id="chart_sub_title" name="chart_sub_title" placeholder="Charts Sub Title">{{ $monster_asset->title->chart_sub_title }}</textarea>
                                                </div>

                                                <button id="chart_title_submit" type="submit" class="btn btn-outline-dark">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card my-4">
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="panel-content">
                                    <div class="panel-header articles-header avatar">
                                        <img src="{{ $monster_asset->article_icon }}" alt="chart-icon" class="circle articles-logo">
                                        <p class="panel-title articles-title">{{ $monster_asset->title->article_title }}</p>
                                        <p class="articles-subtitle">{{ $monster_asset->title->article_sub_title }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="articleLogoForm" action="{{ route('asset.upload-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $monster_asset->id }}">
                                            <input type="hidden" name="asset_type" value="articles">

                                            <div class="row justify-content-center">
                                                <div class="col-12">
                                                    <img src="{{ $monster_asset->article_icon }}" alt="article-icon" width="100px">
                                                </div>

                                                <div class="my-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <input type="file" id="article-logo" name="image" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button id="article_submit" type="submit" hidden></button>
                                        </form>

                                        <div class="row justify-content-center">
                                            <form id="articleTitleForm" action="{{ route('asset.update', $monster_asset->id) }}" method="POST" class="col-10">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="asset_type" value="articles">

                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="article_title" name="article_title" placeholder="Article Title" value="{{ $monster_asset->title->article_title }}">
                                                </div>
                                                <div class="form-group">
                                                    <textarea type="text" class="form-control" id="article_sub_title" name="article_sub_title" placeholder="Article Sub Title">{{ $monster_asset->title->article_sub_title }}</textarea>
                                                </div>

                                                <button id="article_title_submit" type="submit" class="btn btn-outline-dark">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card my-4">
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="panel-content">
                                    <div class="panel-header podcast-header avatar">
                                        <img src="{{ $monster_asset->podcast_icon }}" alt="podcasts-icon" class="circle shows-logo">
                                        <p class="panel-title show-title">{{ $monster_asset->title->podcast_title }}</p>
                                        <p class="shows-subtitle">{{ $monster_asset->title->podcast_sub_title }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="podcastLogoForm" action="{{ route('asset.upload-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $monster_asset->id }}">
                                            <input type="hidden" name="asset_type" value="podcasts">

                                            <div class="row justify-content-center">
                                                <div class="col-12">
                                                    <img src="{{ $monster_asset->podcast_icon }}" alt="podcast-icon" width="100px">
                                                </div>

                                                <div class="my-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <input type="file" id="podcast-logo" name="image" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button id="podcast_submit" type="submit" hidden></button>
                                        </form>

                                        <div class="row justify-content-center">
                                            <form id="podcastTitleForm" action="{{ route('asset.update', $monster_asset->id) }}" method="POST" class="col-10">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="asset_type" value="podcasts">

                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="podcast_title" name="podcast_title" placeholder="Podcast Title" value="{{ $monster_asset->title->podcast_title }}">
                                                </div>
                                                <div class="form-group">
                                                    <textarea type="text" class="form-control" id="podcast_sub_title" name="podcast_sub_title" placeholder="Podcast Sub Title">{{ $monster_asset->title->podcast_sub_title }}</textarea>
                                                </div>

                                                <button id="podcast_title_submit" type="submit" class="btn btn-outline-dark">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card my-4">
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="panel-content">
                                    <div class="panel-header articles-header avatar">
                                        <img src="{{ $monster_asset->article_page_icon }}" class="circle articles-icon" alt="articles-icon">
                                        <div class="panel-title large-title">{{ $monster_asset->title->articles_main_page_title }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="articlePageLogoForm" action="{{ route('asset.upload-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $monster_asset->id }}">
                                            <input type="hidden" name="asset_type" value="articlesMain">

                                            <div class="row justify-content-center">
                                                <div class="col-12">
                                                    <img src="{{ $monster_asset->article_page_icon }}" alt="podcast-icon" width="100px">
                                                </div>

                                                <div class="my-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <input type="file" id="article-page-logo" name="image" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button id="article_main_submit" type="submit" hidden></button>
                                        </form>

                                        <div class="row justify-content-center">
                                            <form id="articlePageTitleForm" action="{{ route('asset.update', $monster_asset->id) }}" method="POST" class="col-10">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="asset_type" value="articlesMain">

                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="articles_main_page_title" name="articles_main_page_title" placeholder="Articles Page Title" value="{{ $monster_asset->title->articles_main_page_title }}">
                                                </div>

                                                <button id="article_main_title_submit" type="submit" class="btn btn-outline-dark">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card my-4">
                        <div class="card-body row justify-content-center">
                            <div class="col-8">
                                <div class="panel-content">
                                    <div class="panel-header plainHeader no-logo">
                                        <div class="panel-title large-title">{{ $monster_asset->title->podcast_main_page_title }}</div>
                                    </div>
                                </div>

                                <div class="card my-4">
                                    <div class="card-body row justify-content-center">
                                        <form id="podcastPageTitleForm" action="{{ route('asset.update', $monster_asset->id) }}" method="POST" class="col-10 row justify-content-center">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="asset_type" value="podcastsMain">

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="podcast_main_page_title" name="podcast_main_page_title" placeholder="Podcast Page Title" value="{{ $monster_asset->title->podcast_main_page_title }}">
                                                </div>
                                            </div>

                                            <div class="col-6 text-center">
                                                <button id="articles_main_submit" type="submit" class="btn btn-outline-dark">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card my-4">
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="panel-content">
                                    <div class="panel-header youtube avatar">
                                        <img src="{{ $monster_asset->youtube_page_icon }}" class="circle youtube-logo" alt="youtube-icon">
                                        <div class="panel-title large-title">{{ $monster_asset->title->youtube_main_page_title }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="youtubeLogoForm" action="{{ route('asset.upload-image') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $monster_asset->id }}">
                                            <input type="hidden" name="asset_type" value="youtube">

                                            <div class="row justify-content-center">
                                                <div class="col-12">
                                                    <img src="{{ $monster_asset->youtube_page_icon }}" alt="youtube-icon" width="100px">
                                                </div>

                                                <div class="my-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <input type="file" id="youtube-logo" name="image" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button id="youtube_submit" type="submit" hidden></button>
                                        </form>

                                        <div class="row justify-content-center">
                                            <form id="youtubeTitleForm" action="{{ route('asset.update', $monster_asset->id) }}" method="POST" class="col-10">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="asset_type" value="youtube">

                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="youtube_main_page_title" name="youtube_main_page_title" placeholder="YouTube Page Title" value="{{ $monster_asset->title->youtube_main_page_title }}">
                                                </div>

                                                <button id="youtube_main_title_submit" type="submit" class="btn btn-outline-dark">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
