@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Update Current Charted Song
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="row">
                        <div class="col-md-4">
                            <img class="card-img img-thumbnail" src="{{ asset('images/albums/'.$chart->Song->Album->AlbumImage) }}" alt="{{ $chart->Song->Album->AlbumImage }}">
                        </div>
                        <div class="col-md-8">
                            <p class="lead">Artist: {{ $chart->Song->Album->Artist->Name }}
                                <br> Album: {{ $chart->Song->Album->AlbumName }}
                                <br> Song: {{ $chart->Song->SongName }}
                            </p>
                            <form action="{{ route('charts.update', $chart->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <input type="hidden" id="song_id" name="song_id" value="{{ $chart->Song->id }}">
                                </div>
                                <div class="form-group">
                                    <label for="Positions">Chart Position</label>
                                    <select id="Positions" name="Positions" class="custom-select">
                                        <option value="{{ $chart->Positions }}" selected>{{ $chart->Positions }}</option>
                                        @for($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="active" for="dated">Date Charted</label>
                                    <input id="dated" name="dated" type="date" class="form-control" value="{{ $chart->dated }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-outline-dark btn-block">Update</button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" data-toggle="modal" data-target="#dropModal" class="btn btn-outline-dark btn-block">Drop</a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" data-toggle="modal" data-target="#deleteModal" class="btn btn-outline-dark btn-block">Delete</a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('charts.index') }}" class="btn btn-outline-dark btn-block">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('_cms.system-views._feedbacks.modals')
@endsection
