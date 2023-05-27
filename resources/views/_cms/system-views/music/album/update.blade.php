@extends('layouts.base')

@section('content')
    <div class="container-fluid mb-5">
        <div class="display-4">
            Edit Album
        </div>
        <br>
        @include('system-views._feedbacks.success')
        @include('system-views._feedbacks.error')
        <br>
        <div class="row">
            <div class="col-md-4">
                <img src="{{ asset('images/albums/'.$album->AlbumImage) }}" alt="{{ $album->AlbumImage }}" class="img-thumbnail img-fluid card-img-top">
            </div>
            <div class="col-md-8">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Song</th>
                        <th>Genre</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($song as $songs)
                        <?php try { ?>
                        <tr data-href="{{ route('songs.show', $songs->id) }}" onclick="viewData()">
                            <td>{{ $songs->SongName }}</td>
                            <td>{{ $album->Genre->name }}</td>
                        </tr>
                        <?php } catch (ErrorException $e) {?>
                            <td>DELETED DATA</td>
                        <?php } ?>
                    @empty
                        <tr>
                            <td colspan="2">
                                <div class="alert alert-danger text-muted text-center lead">
                                    NO SONGS FOUND
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <form action="{{ route('albums.update', $album->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="AlbumName">Album</label>
                                <input type="text" id="AlbumName" name="AlbumName" placeholder="AlbumName" class="form-control" value="{{ $album->AlbumName }}">
                            </div>
                            <div class="col-md-6">
                                <label for="artist_id">Artist</label>
                                <select id="artist_id" name="artist_id" class="custom-select">
                                    <option value="{{ $album->Artist->id }}" selected>{{ $album->Artist->Name }}</option>
                                    @forelse($artist as $artists)
                                        <?php try { ?>
                                            <option value="{{ $artists->id }}">{{ $artists->Name }}</option>
                                        <?php } catch (ErrorException $e) {?>
                                            <option value="{{ $artists->id }}">Deleted</option>
                                        <?php } ?>
                                    @empty
                                        <option value="">No Data Found</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="genre_id">Genre</label>
                                <select id="genre_id" name="genre_id" class="custom-select">
                                    <option value="{{ $album->Genre->id }}" selected>{{ $album->Genre->name }}</option>
                                    @forelse($genre as $genres)
                                        <?php try { ?>
                                        <option value="{{ $genres->id }}">{{ $genres->name }}</option>
                                        <?php } catch (ErrorException $e) {?>
                                        <option value="{{ $genres->id }}">Deleted</option>
                                        <?php } ?>
                                    @empty
                                        <option value="">No Data Found</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="AlbumYear">Year</label>
                                <input type="text" id="AlbumYear" name="AlbumYear" class="form-control" value="{{ $album->AlbumYear }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="AlbumType">Type</label>
                                <select id="AlbumType" name="AlbumType" class="custom-select">
                                    <option value="Single">Single</option>
                                    <option value="EP">EP</option>
                                    <option value="Full">Full</option>
                                    <option value="Demo">Demo</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" id="image" name="image" class="custom-file-input">
                                        <label class="custom-file-label" for="image">Album Cover</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-dark btn-block">Save</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" href="#delete-modal" class="btn btn-outline-dark btn-block" data-toggle="modal">Delete</button>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('albums.index') }}" class="btn btn-outline-dark btn-block">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete {{ $album->AlbumName }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="lead">Are you sure you want to delete this Album?</div>
                        <form method="post" action="{{ route('albums.destroy', $album->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="btn-group my-3 fa-pull-right">
                                <button type="submit" class="btn btn-outline-dark">Yes</button>
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
