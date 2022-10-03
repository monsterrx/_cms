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
            <div class="row">
                <div class="col-12 d-none d-lg-block d-xl-block">
                    <a href="#new-article" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Article</a>
                    <div class="btn-group fa-pull-right mx-2">
                        <button id="yearlyArticles" type="button" class="btn btn-outline-dark">{{ date('Y') }}&nbsp;Articles</button>
                        <button id="switch_pub" type="button" class="btn btn-outline-dark fa-pull-right" hidden data-article="published">Published Articles</button>
                        <button id="switch_unpub" type="button" class="btn btn-outline-dark fa-pull-right" data-article="unpublished">Unpublished Articles</button>
                        <button id="archives" class="btn btn-outline-dark">Archives</button>
                    </div>
                </div>
                <div class="col-12 d-block d-lg-none d-xl-none">
                    <a href="#new-article" data-toggle="modal" class="btn btn-outline-dark fa-pull-right"><i class="fas fa-plus-circle"></i>  New</a>
                    <div class="btn-group fa-pull-right mx-2">
                        <button id="yearlyArticles" type="button" class="btn btn-outline-dark"><i class="fas fa-calendar-alt"></i>&nbsp;Articles</button>
                        <button id="switch_pub" type="button" class="btn btn-outline-dark fa-pull-right" hidden data-article="published"><i class="fas fa-rss"></i>&nbsp;Published</button>
                        <button id="switch_unpub" type="button" class="btn btn-outline-dark fa-pull-right" data-article="unpublished"><i class="fas fa-file"></i>&nbsp;Unpublished</button>
                        <button id="archives" class="btn btn-outline-dark">Archives</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="articleStatus">
                        <div class="my-3"></div>
                        <div class="alert alert-warning" role="alert">
                            <p class="lead text-danger text-center">
                                {{ __('Something went wrong, report to the IT - Web Developer') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="new-article" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="article_post" method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Article Title">
                                </div>
                                <div class="form-group">
                                    <label class="label" for="category_id">Category</label>
                                    <select id="category_id" name="category_id" class="custom-select">
                                        <option value>--</option>
                                        @foreach($category as $categories)
                                            <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="heading">Article Heading</label>
                                    <textarea id="heading" name="heading" cols="30" rows="10" class="form-control" placeholder="Article Heading"></textarea>
                                </div>
                            </div>
                            <div class="my-4"></div>
                            <div class="col-md-12">
                                <div class="btn-group float-right">
                                    <button type="submit" class="btn btn-outline-dark">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
