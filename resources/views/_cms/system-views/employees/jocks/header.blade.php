@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Update {{ $jock->name }}'s Header
            </div>
            <div class="my-3">
                @include('_cms.system-views._feedbacks.success')
                @include('_cms.system-views._feedbacks.error')
            </div>
            <div class="row justify-content-center my-4">
                <div class="col-12 col-sm-12 col-md-8">
                    <div class="card-img">
                        <img src="{{ asset('images/jocks/'.$jock->background_image) }}" class="img-thumbnail" alt="{{ $jock->background_image }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div id="croppingHeaderImage" class="cropperHeader img-fluid"></div>
    </div>
    <div class="container">
        <div class="card">
            <form id="headerForm" method="POST" action="{{ route('users.header.update') }}">
                @csrf
                <div class="card-body">
                    <div id="row" class="row">
                        <div class="col-md-12 my-3">
                            <input type="text" id="jock_id" name="jock_id" value="{{ $jock->id }}" style="display: none;">
                            <input type="text" id="headerImageName" name="headerImageName" style="display: none;" />
                            <div class="form-group">
                                <div class="custom-file" id="headerCustom">
                                    <input type="file" id="header" name="header" class="custom-file-input" accept="image/*">
                                    <label for="header" class="custom-file-label">Click Here</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-right">
                                <button id="cropHeaderButton" type="button" class="btn btn-outline-dark" hidden>Crop</button>
                                <button id="saveHeaderButton" type="submit" class="btn btn-outline-dark" hidden>Save</button>
                                <button id="cancelHeaderButton" type="button" class="btn btn-outline-dark" data-role="none" hidden>Cancel</button>
                                <a href="{{ route('jocks.show', $jock->id) }}" id="doneHeaderButton" type="button" class="btn btn-outline-dark">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
