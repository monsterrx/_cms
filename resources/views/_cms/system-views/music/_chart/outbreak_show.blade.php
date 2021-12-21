@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Outbreak Songs
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <br>
            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('outbreaks.index') }}" class="btn btn-outline-dark fa-pull-left"><span class="fa fa-arrow-left"></span>  Back</a>
                    <a href="#musicPlayerCollapse" class="btn btn-outline-dark fa-pull-right" data-toggle="collapse" aria-expanded="false" aria-controls="musicPlayerCollapse">Show Music Player</a>
                </div>
            </div>

            <div class="collapse" id="musicPlayerCollapse">
                <div class="row">
                    <div class="col-md-12">
                        @if(strlen($outbreaks->track_link) === 22) {{-- Means its from spotify!! --}}
                        <div id="musicPlayer" class="embed-container" style="max-width: 100%; margin: 50px;">
                            <iframe src="https://open.spotify.com/embed/track/{{ $outbreaks->track_link }}" frameborder="0" allowtransparency="true" allow="encrypted-media" width="100%" height="80px"></iframe>
                        </div>
                        @elseif(strlen($outbreaks->track_link) > 25) {{-- Its from the old code --}}
                        <div class="card m-3 m-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ url('images/albums/'.$outbreaks->Song->Album->AlbumImage) }}" alt="{{ $outbreaks->Song->SongName }}" class="img-thumbnail">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body text-center" style="margin: 50px;">
                                        @if(strlen($outbreaks->Song->Album->Artist->Name) > 20)
                                            <marquee><div class="h3">{{ $outbreaks->Song->Album->Artist->Name }}</div></marquee>
                                        @else
                                            <div class="h3">{{ $outbreaks->Song->Album->Artist->Name }}</div>
                                        @endif

                                        @if(strlen($outbreaks->Song->SongName) > 20)
                                            <marquee class="mb-0"><div class="lead">{{ $outbreaks->Song->SongName }}</div></marquee>
                                        @else
                                            <div class="lead">{{ $outbreaks->Song->SongName }}</div>
                                        @endif
                                        <small>{{ $outbreaks->Song->Album->AlbumName }}</small>
                                        <center>
                                            <audio controls style="max-width: 100%;" controlsList="nodownload">
                                                <source src="{{ $outbreaks->track_link }}" type="audio/mp3">
                                                Your browser does not support audio tag
                                            </audio>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                            <center>
                                <img src="{{ url('images/albums/'.$outbreaks->Song->Album->AlbumImage) }}" alt="{{ $outbreaks->Song->Album->AlbumImage }}" class="img-thumbnail" width="350px">
                            </center>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ action('OutbreakController@update', $outbreaks->id) }}" id="update_form">
                        @csrf
                        @method('PATCH')
                        @if($outbreaks->track_link !== null || $outbreaks->track_link !== '')
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="song_id">Song</label>
                                            <select class="custom-select" id="song_id" name="song_id">
                                                <option value="{{ $outbreaks->Song->id }}">{{ $outbreaks->Song->SongName }}</option>
                                                @foreach($song as $songs)
                                                    <option value="{{ $songs->id }}">{{ $songs->SongName }} &mdash; {{ $songs->Album->Artist->Name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="dated">Outbreak Date</label>
                                            <input id="dated" name="dated" type="date" class="form-control" value="{{ $outbreaks->dated }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="track_link">Sample Track from Spotify</label>
                                            <input type="text" id="track_link" name="track_link" class="form-control" placeholder="track:(random numbers and letters from spotify url)" value="{{ $outbreaks->track_link }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-group btn-block">
                                    <button type="button" class="btn btn-outline-danger" onclick="document.getElementById('delete_form').submit();">Delete</button>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="lead" for="song_id">Song</label>
                                        <select class="custom-select" id="song_id" name="song_id">
                                            <option value="{{ $outbreaks->Song->id }}">{{ $outbreaks->Song->SongName }}</option>
                                            @foreach($data['song'] as $songs)
                                                <option value="{{ $songs->id }}">{{ $songs->SongName }} &mdash; {{ $songs->Album->Artist->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="lead" for="dated">Outbreak Date</label>
                                        <input id="dated" name="dated" type="date" class="form-control" value="{{ $outbreaks->dated }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="lead" for="track_link">Sample Track from Spotify</label>
                                        <input type="text" id="track_link" name="track_link" class="form-control" placeholder="track:(random numbers and letters from spotify url)" value="{{ $outbreaks->track_link }}">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="btn-group fa-pull-right">
                                <button type="button" class="btn btn-outline-dark" onclick="document.getElementById('update_form').submit();">Save</button>
                            </div>
                        </div>
                    </div>

                    <form method="POST" id="delete_form" action="{{ action('OutbreakController@destroy', $outbreaks->id) }}" style="display: none;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="song_id" id="song_id" value="{{ $outbreaks->id }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
