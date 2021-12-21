@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Digital Content
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <div class="col-md-12 col-lg-12">
                <div class="btn-group fa-pull-right">
                    <a href="{{ action('ArticleController@articles') }}" class="btn btn-outline-dark">{{ date('Y') }}&nbsp;Articles</a>
                    <a href="{{ action('ArticleController@index') }}" class="btn btn-outline-dark">Published Articles</a>
                    <a href="#" class="btn btn-outline-dark active">Archives</a>
                </div>
            </div>
            <div id="CurrentArticles" class="mb-5">

            </div>
        </div>
    </div>
@endsection
