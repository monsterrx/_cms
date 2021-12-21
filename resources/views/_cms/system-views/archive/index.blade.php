@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Archived Logs
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <br>
            <div id="archivedLogsContainer">
                <div class="alert alert-warning lead text-center">
                    Error Occurred, Contact IT - Web Developer
                </div>
            </div>
        </div>
    </div>
@endsection
