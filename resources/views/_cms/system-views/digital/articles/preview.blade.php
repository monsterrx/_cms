@extends('layouts.base')

@section('content')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed&family=Rubik&display=swap">
    <style>
        [role="main"] {
            background-color: #181818!important;
        }
    </style>

    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-monster-blue my-4 rubik" ><i class="fa fa-chevron-circle-left"></i>  Back</a>
            <div class="row">
                <div class="col-12">
                    <div class="card bg-medium-gray text-light">
                        <img src="{{ asset('images/articles/'.$article->image) }}" alt="{{ $article->title }}">
                        <div class="card-body m-4">
                            <div class="my-3 text-monster-yellow rubik fs-4">{{ $article->title }}</div>
                            <div class="h3 my-3 barlow fs-5">{{ $article->heading }}</div>
                            <div class="row mb-2 text-muted barlow">
                                <div class="col-md-6">Category: {{ $article->Category->name }}</div>
                                <div class="col-md-6">Published Date: {{ !$article->published_at ? 'Unpublished' : date('F d, Y', strtotime($article->published_at)) }}</div>
                                <div class="col-md-6">Last Update: {{ date('F d, Y', strtotime($article->updated_at)) }}</div>
                                <div class="col-md-6">Author: {{ $article->Employee->first_name }} {{ $article->Employee->last_name }}</div>
                            </div>
                            <div class="row mb-2 barlow">
                                <div class="col-md-12">
                                    @foreach($article->Content as $contents)
                                        {!! $contents->content !!}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
