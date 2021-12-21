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
            <div class="row">
                <div class="col-md-12">
                    <div class="fa-pull-right">
                        <a href="#newOutbreak" class="btn btn-outline-dark" data-toggle="modal">New Outbreak Song</a>
                    </div>
                </div>
            </div>
            <div class="row my-3 mx-5">
                <div class="col-md-12">
                    @if($where === "" || $where === null)

                    @else
                        <div class="h3 text-center m-0">Outbreak Songs of the Week {{ date('F d, Y', strtotime($where)) }}</div>
                    @endif
                </div>
                @forelse($recentOutbreak as $outbreakSongs)
                    @if($outbreakSongs->Song->type === 'spotify')
                        <div class="col-md-6">
                            <div class="embed-container" style="max-width: 100%; margin: 15%;">
                                <iframe src="https://open.spotify.com/embed/track/{{ $outbreakSongs->track_link }}" frameborder="0" allowtransparency="true" allow="encrypted-media" height="380px"></iframe>
                            </div>
                        </div>
                    @else
                        @php
                            $album = $outbreakSongs->Song->Album->AlbumName;
                            $albumType = $outbreakSongs->Song->Album->AlbumType;
                            $res = $album . ' — ' . $albumType;
                        @endphp
                        <div class="col-md-6">
                            <div class="card marginbot-0">
                                <img src="{{ url('images/albums/'.$outbreakSongs->Song->Album->AlbumImage) }}" class="card-img-top img-thumbnail" alt="{{ $outbreakSongs->Song->Album->AlbumImage }}">
                                <div class="card-body text-center">
                                    <p style="margin-bottom: 0">{{ $outbreakSongs->Song->SongName }}</p>
                                    @if(strlen($outbreakSongs->Song->Album->Artist->Name) > 15)
                                        <marquee><p class="card-text" style="margin-bottom: 0">{{ $outbreakSongs->Song->Album->Artist->Name }}</p></marquee>
                                    @elseif(strlen($outbreakSongs->Song->Album->Artist->Name) < 15)
                                        <p class="card-text" style="margin-bottom: 0">{{ $outbreakSongs->Song->Album->Artist->Name }}</p>
                                    @else
                                        <p class="card-text" style="margin-bottom: 0">{{ $outbreakSongs->Song->Album->Artist->Name }}</p>
                                    @endif
                                    @if($outbreakSongs->Song->Album->AlbumType === 'Single' || $outbreakSongs->Song->Album->AlbumType === 'EP')
                                        @if(strlen($res) > 30)
                                            <marquee><small>{{ $res }}</small></marquee>
                                        @else
                                            <small>{{ $res }}</small>
                                        @endif
                                    @else
                                        <small>{{ $outbreakSongs->Song->Album->AlbumName }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <div class="h5 text-center mb-0">
                                Outbreak songs goes here
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <table class="table table-hover" id="outbreaksTable">
                <thead>
                <tr>
                    <th>Dated</th>
                    <th>Song</th>
                    <th>Artist</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="4">
                        <div class="alert alert-danger mb-0">
                            <div class="lead text-center">
                                No Data Found
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="newOutbreak" tabindex="-1" role="dialog" aria-labelledby="newOutbreak" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Outbreak Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="outbreakSongsForm" method="POST" action="{{ route('outbreaks.store') }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="outbreak_song_id">Song</label>
                                            <select class="custom-select" id="outbreak_song_id" name="song_id">
                                                <option value="">--</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="dated">Outbreak Date</label>
                                            <input id="dated" name="dated" type="date" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div id="linkString" class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="track_link">Sample Track from Spotify</label>
                                            <input type="text" id="track_link" name="track_link" class="form-control" placeholder="track:(random numbers and letters from spotify url)">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-outline-dark">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="updateOutbreak" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Outbreak Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateOutbreakSongForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="lead" for="outbreak_song_id">Song</label>
                                    <select class="custom-select" id="update_outbreak_song_id" name="song_id">
                                        <option value="">--</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="lead" for="dated">Outbreak Date</label>
                                    <input id="update_dated" name="dated" type="date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteOutbreak" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Outbreak Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteOutbreakSongForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete-outbreak-song-body" class="h5 text-center">
                            Loading ...
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
