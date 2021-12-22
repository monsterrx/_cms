@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="lead">
                Article: {{ $data['article']->title }}
            </div>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <hr class="my-3">
            <a href="{{ route('articles.show', [$data['article']->id]) }}" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i>  Back</a>
            <div class="my-3">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <form id="sub_post" method="POST" action="{{ route('sub_contents.update', [$data['article']->id, $data['content']->id]) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <label class="active lead" for="content">Content</label>
                                <textarea id="content" name="content" class="form-control">{{ $data['content']->content }}</textarea>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark">Save</button>
                                    <a href="#deleteModal" class="btn btn-outline-dark" data-toggle="modal">Delete</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Warning!</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sub_contents.destroy', [$data['article']->id, $data['content']->id]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <h3 style="font-weight: 300;">Delete this Sub Content?</h3>
                        <br><br>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-outline-secondary btn-block">Yes</button>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-outline-dark btn-block" data-dismiss="modal">No</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
