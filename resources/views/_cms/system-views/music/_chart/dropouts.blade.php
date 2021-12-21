@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Dropouts
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th scope="col">Last Position</th>
                                    <th scope="col">Songs</th>
                                    <th scope="col">Artist</th>
                                    <th scope="col">Dated</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($dropout as $dropouts)
                                    <?php try { ?>
                                    <tr>
                                        <th scope="row">{{ $dropouts->last_position }}</th>
                                        <td>{{ $dropouts->Song->name }}</td>
                                        <td>{{ $dropouts->Song->Album->Artist->name }}</td>
                                        <td>{{ date("M d Y", strtotime($dropouts->dated)) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#show-{{ $dropouts->id }}" data-toggle="modal" class="btn btn-outline-dark">Show</a>
                                                <a href="#reentry-{{ $dropouts->id }}" data-toggle="modal" class="btn btn-outline-dark">Re-Entry</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } catch (ErrorException $e) {?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-muted">
                                                <center><strong style="color: red;">Deleted Data</strong></center>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
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
    @forelse($dropout as $dropouts)
        <div id="show-{{ $dropouts->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title lead">Dropped Song Details</h4>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p class="lead">
                            <b>Song</b>: {{ $dropouts->Song->name }}
                            <br>
                            <b>Artist</b>: {{ $dropouts->Song->Album->Artist->name }}
                            <br>
                            <b>Date Dropped</b>: {{ $dropouts->dated }}
                            <br>
                            <b>Last Position</b>: {{ $dropouts->last_position }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Hide</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse
    @forelse($dropout as $dropouts)
        <div id="reentry-{{ $dropouts->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title lead">Re-Entry Dropped Song</h4>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('dropouts.update', $dropouts->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" id="song_id" name="song_id">
                            <label for="Positions" class="label">Chart Position</label>
                            <select id="Positions" name="Positions" class="custom-select">
                                <option value selected>--</option>
                                @for($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <br><br>
                            <label for="dated" class="label">Dated</label>
                            <input type="date" id="dated" name="dated" class="form-control">
                            <br><br>
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse
@endsection
