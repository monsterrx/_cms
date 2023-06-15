@extends('layouts.base')

@section('content')
    <div class="row mt-md-3 mt-0 mb-5">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.error')
                    @include('_cms.system-views._feedbacks.success')
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-6 col-sm-6">
                    <button type="button" class="btn btn-info">
                        {{ Auth::user()->Employee->Designation->name }}
                    </button>
                </div>
            </div>
        </div>
        <div class="my-4"></div>
        @if(Auth::user()->Employee->Designation->level === 1 || Auth::user()->Employee->Designation->level === 2 || Auth::user()->Employee->Designation->level === 7)
            @if($chart->isEmpty())
                <div class="col-md-12">
                    <div class="alert alert-info text-center h5 text-info">
                        {{ env('STATION_CHART') }} goes here.
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <h5 class="h5"><i class="fa fa-music mr-3"></i>Monster Hit of the Week: {{ date('F d, Y', strtotime($where)) }} @if($chart->first()->is_posted === 0) <sup class="badge badge-pill badge-success">Draft</sup> @elseif($chart->first()->is_posted === 1) <sup class="badge badge-pill badge-info">Official</sup> @else <sup>Undefined</sup> @endif</h5>
                </div>
                @forelse($chart as $charts)
                    @if($charts->position === 1)
                        <div class="col-md-4 col-sm-4">
                            <div class="card">
                                <img class="card-img-top" src="{{ $charts->Song->Album->image }}" alt="{{ $charts->Song->Album->name }}">
                                <div class="card-body">
                                    <div class="card-title text-center">
                                        <p class="h6 m-0">{{ $charts->Song->name }}</p>
                                    </div>
                                    <div class="card-subtitle">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="lead text-center">
                                                    {{ $charts->Song->Album->Artist->name }}
                                                    <br>
                                                    {{ $charts->Song->Album->name }} @if($charts->Song->Album->type === 'single' || $charts->Song->Album->type === 'Single' || $charts->Song->Album->type === 'Sigle') &mdash; Single @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                @endforelse
                <div class="col-md-8 col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Spot</th>
                                    <th>Song Name</th>
                                    <th>Artist</th>
                                    <th>Album</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($chart as $charts)
                                    @if($charts->position > 1)
                                        <tr>
                                            <td><p class="text-black-50 text-center">{{ $charts->position }}</p></td>
                                            <td>{{ $charts->Song->name }}</td>
                                            <td>{{ $charts->Song->Album->Artist->name }}</td>
                                            <td>{{ $charts->Song->Album->name }}</td>
                                        </tr>
                                    @endif
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 mt-md-0">
                                    <a href="{{ route('charts.index') }}" class="btn btn-outline-dark btn-block">Full Chart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
            <hr class="my-4">
            <div class="container">
                <h4 class="h4"><i class="fa fa-user-tie ml-3 mr-3"></i>Employees</h4>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card zoom" data-href="{{ route('employees.index') }}" onclick="viewData()">
                            <div class="card-header h5 text-center">
                                You have {{ $employees }} active Employees
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card zoom">
                            <div class="card-header h5 text-center">
                                You have {{ $inactiveEmployees }} inactive Employees
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="container">
                <h4 class="h4"><i class="fa fa-compact-disc mr-3"></i>Music</h4>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card zoom">
                            <div class="card-header h5 text-center mb-2" data-toggle="collapse" data-target="#artistCollapse" aria-expanded="false" aria-controls="artistCollapse">
                                <p class="mb-0">You have {{ $data['artists'] }} Artists</p>
                            </div>
                            <div id="artistCollapse" class="collapse">
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Latest Added Artists</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($data['recArtist'] as $artist)
                                            <tr data-href="{{ route('artists.update', $artist->id) }}" onclick="viewData">
                                                <td>{{ Str::limit($artist->name, $limit = 30, $end = '...') }}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card zoom">
                            <div class="card-header h5 text-center mb-2" data-toggle="collapse" data-target="#albumCollapse" aria-expanded="false" aria-controls="albumCollapse">
                                <p class="mb-0">You have {{ $data['albums'] }} Albums</p>
                            </div>
                            <div id="albumCollapse" class="collapse">
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Latest Added Albums</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($data['recAlbum'] as $album)
                                            <tr data-href="{{ route('albums.update', $album->id) }}" onclick="viewData">
                                                <td>{{ Str::limit($album->name, $limit = 35, $end = '...') }}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card zoom">
                            <div class="card-header h5 text-center mb-2" data-toggle="collapse" data-target="#songCollapse" aria-expanded="false" aria-controls="songCollapse">
                                <p class="mb-0">You have {{ $data['songs'] }} Songs</p>
                            </div>
                            <div id="songCollapse" class="collapse">
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Latest Added Songs</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($data['recSong'] as $song)
                                            <tr data-href="{{ route('songs.update', $song->id) }}" onclick="viewData()">
                                                <td>{{ Str::limit($song->name, $limit = 35, $end = '...') }}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-4"></div>
            @if(env('STATION_CODE') !== 'mnl')
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col">
                            <h4 class="h4"><i class="fa fa-icons ml-3 mr-3"></i>Outbreaks</h4>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-dark float-right" data-toggle="modal" data-target="#newOutbreak">New Outbreak</button>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row justify-content-center">
                        @foreach($data['outbreaks'] as $outbreak)
                            @if($outbreak->Song->type === 'spotify')
                                <div class="col-md-6">
                                    <div class="embed-container" style="max-width: 100%; margin: 15%;">
                                        <iframe src="https://open.spotify.com/embed/track/{{ $outbreak->track_link }}" frameborder="0" allowtransparency="true" allow="encrypted-media" height="380px"></iframe>
                                    </div>
                                </div>
                            @else
                                @php
                                    $album = $outbreak->Song->Album->name;
                                    $albumType = $outbreak->Song->Album->type;
                                    $res = $album . ' — ' . $albumType;
                                @endphp
                                <div class="col-md-6">
                                    <div class="card marginbot-0">
                                        <img src="{{ url('images/albums/'.$outbreak->Song->Album->image) }}" class="card-img-top img-thumbnail" alt="{{ $outbreak->Song->Album->image }}">
                                        <div class="card-body text-center">
                                            <p style="margin-bottom: 0">{{ $outbreak->Song->name }}</p>
                                            @if(strlen($outbreak->Song->Album->Artist->name) > 15)
                                                <marquee><p class="card-text" style="margin-bottom: 0">{{ $outbreak->Song->Album->Artist->name }}</p></marquee>
                                            @elseif(strlen($outbreak->Song->Album->Artist->Name) < 15)
                                                <p class="card-text" style="margin-bottom: 0">{{ $outbreak->Song->Album->Artist->name }}</p>
                                            @else
                                                <p class="card-text" style="margin-bottom: 0">{{ $outbreak->Song->Album->Artist->name }}</p>
                                            @endif
                                            @if($outbreak->Song->Album->type === 'Single' || $outbreak->Song->Album->type === 'EP')
                                                @if(strlen($res) > 30)
                                                    <marquee><small>{{ $res }}</small></marquee>
                                                @else
                                                    <small>{{ $res }}</small>
                                                @endif
                                            @else
                                                <small>{{ $outbreak->Song->Album->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col">
                            <h4 class="h4"><i class="fa fa-microphone ml-3 mr-3"></i>Podcast</h4>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-dark float-right" data-toggle="modal" data-target="#new-podcast">New Podcast</button>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row justify-content-center">
                        @foreach($data['podcasts'] as $podcast)
                            <div class="col-md-4">
                                <div class="card zoom ml-2 mr-2" id="podcast_card" data-target="#update_podcast_modal" data-toggle="modal" data-id="{{ $podcast->id }}">
                                    <img src="{{ $podcast->image }}" alt="{{ $podcast->episode }}">
                                    <div class="card-body">
                                        <div class="card-text text-center">
                                            <div class="font-weight-bold">{{ $podcast->Show->title }}</div>
                                            {{ $podcast->episode }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <hr class="my-4">
            <div class="container">
                <h4 class="h4"><i class="fas fa-gifts mr-3"></i>Giveaways</h4>
                <hr class="my-4">
                <div class="row">
                    @if($data['recGiveaway']->isNotEmpty())
                        <div class="owl-carousel">
                        @foreach($data['recGiveaway'] as $giveaways)
                                <div class="col-md-12">
                                    <div class="card zoom mx-3 my-3" data-href="{{ route('giveaways.show', $giveaways->id) }}" onclick="viewData()">
                                        <div class="card-body text-muted text-center">
                                            <div class="lead">{{ Str::limit($giveaways->name, $limit = 20, $end = '...') }}</div>
                                            @if($giveaways->type === 'movies')
                                                <div class="text-dark">Monster Movie Premiere</div>
                                            @elseif($giveaways->type === 'concerts')
                                                <div class="text-dark">Monster Concert Tickets</div>
                                            @else
                                                <div class="text-warning">Undefined</div>
                                            @endif
                                            @if($giveaways->is_active === '0')
                                                Status: <span class="text-danger">Inactive</span>
                                            @elseif($giveaways->is_active === '1')
                                                Status: <span class="text-success">Active</span>
                                            @else
                                                <span class="text-warning">Undefined</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="alert alert-warning text-center alert mt-1">
                                <p class="h6 m-0">No online giveaways are active at this moment.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="my-4"></div>

        @elseif(Auth::user()->Employee->Designation->level === 6)
            <div class="my-4"></div>
            <div class="container">
                <h4 class="h4"><i class="fa fa-user-tie ml-3 mr-3"></i>Employees</h4>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card zoom" data-href="{{ route('employees.index') }}" onclick="viewData()">
                            <div class="card-title h5 text-center mt-3 mb-3">
                                You have {{ $employees }} active Employees
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card zoom">
                            <div class="card-title h5 text-center mt-3 mb-3">
                                You have {{ $inactiveEmployees }} inactive Employees
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-4"></div>
            </div>
            <div class="my-4"></div>
            <div class="container">
                <h4 class="h4"><i class="fa fa-envelope ml-3 mr-3"></i>Messages</h4>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card zoom" data-href="{{ route('messages.index') }}" onclick="viewData()">
                            <div class="card-title h5 text-center mt-3 mb-3">
                                You have {{ $data['message'] }} Unread Messages
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        @endif
    <div class="modal fade" id="update_podcast_modal" tabindex="-1" role="dialog" aria-labelledby="update_podcast_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="update_podcast_title_header" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center mb-4">
                        <div class="col-12 col-md-4">
                            <img id="update_podcast_image" src="" alt="" class="img-fluid img-thumbnail">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-6">
                            <audio id="update_podcast_audio" src="" controls controlsList="nodownload" type="audio/mpeg"></audio>
                        </div>
                    </div>
                    <form id="update_podcast_form" role="form" method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="update_show_id" class="label">Show</label>
                                    <select id="update_show_id" name="show_id" class="custom-select{{ $errors->has('show_id') ? ' is-invalid': '' }}">
                                        <option value>--</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="update_episode" class="label">Episode Name</label>
                                    <input type="text" id="update_episode" name="episode" class="form-control" placeholder="Episode Name">
                                </div>
                                <div class="form-group">
                                    <label for="update_date" class="label">Date</label>
                                    <input type="date" id="update_date" name="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="update_link" class="label">URL Link</label>
                                    <input type="text" id="update_link" name="link" class="form-control" placeholder="Podcast URL">
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" id="update_image" name="image" class="custom-file-input">
                                                <label id="update_image" class="custom-file-label">Podcast Image</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8 text-center">
                                <button type="submit" class="btn btn-outline-dark"><span class="fa fa-save"></span>  Save</button>
                            </div>
                            <div class="col-md-2"></div>
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
