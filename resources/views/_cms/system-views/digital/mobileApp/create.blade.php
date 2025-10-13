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
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="display-4 mb-3">
                        New Mobile App Asset
                    </div>
                </div>

                
            </div>
        </div>
    </div>
@endsection