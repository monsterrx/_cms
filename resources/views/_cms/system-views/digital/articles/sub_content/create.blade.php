@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="lead">
                Add Content Form
            </div>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <hr class="my-2">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <form method="post" action="{{ route('sub_contents.store', $data['article_id']) }}">
                        @csrf
                        <input type="text" id="article_id" name="article_id" value="{{ $data['article_id'] }}" style="display: none;">
                        <div class="row">
                            <label for="content" class="label lead">Content</label>
                            <textarea id="content" name="content"></textarea>
                        </div>
                        <div class="my-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark">Save</button>
                                    <a href="{{ route('articles.show', [$data['article_id']]) }}" class="btn btn-outline-dark">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
