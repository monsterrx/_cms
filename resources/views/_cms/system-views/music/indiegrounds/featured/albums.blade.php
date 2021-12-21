<label for="albums" class="lead">Albums</label>
<div id="albums" class="row justify-content-center">
    @forelse($featuredIndieArtist->Indie->Artist->Album as $album)
        <div class="col-4 pr-0">
            <div class="card">
                <a href="#songs_collapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="songs_collapse">
                    <img src="{{ asset('images/albums/'.$album->image) }}" alt="{{ $album->name }}" class="card-img-top">
                </a>
            </div>
        </div>
        <div class="col-8 p-0">
            <div class="collapse" id="songs_collapse">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-center"><b>{{ $album->name }}</b></div>
                        @forelse($album->Song as $songs)
                            @if($songs->type == "mp3/m4a")
                                <div class="row justify-content-between">
                                    <div class="col">
                                        <div class="my-3">
                                            {{ $songs->name }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fa-pull-right">
                                            <audio id="song_{{ $songs->id }}" controls controlsList="nodownload" src="{{ asset('audios/'.$songs->track_link) }}">
                                                Your browser does not support audio tag.
                                            </audio>
                                        </div>
                                    </div>
                                </div>
                            @elseif($songs->type == "spotify")
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="embed-container">
                                            <iframe src="https://open.spotify.com/embed/track/{{ $songs->track_link }}" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                                        </div>
                                        <div class="my-3"></div>
                                    </div>
                                </div>
                            @else
                                <div class="h4 text-center">
                                    Demo song coming soon!
                                </div>
                            @endif
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-4">
            <div class="card">
                <img src="{{ asset('images/_assets/default.png') }}" alt="albums-missing" class="card-img">
                <div class="card-body">
                    <div class="text-center">
                        Albums coming soon!
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>
