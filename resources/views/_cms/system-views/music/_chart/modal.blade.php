<div class="modal fade" id="new-chart" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Charted Song</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newChartedSongForm" method="POST" action="{{ route('charts.new') }}">
                @csrf
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="song_id" name="song_id" value="{{ $id }}">
                                <div class="form-group">
                                    <label class="lead" for="Positions">Position</label>
                                    <select id="Positions" name="Positions" class="custom-select">
                                        @for($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="my-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="lead" for="dated">Date Charted</label>
                                    <input id="dated" name="dated" type="date" class="form-control" value="{{ $dated }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button id="modalSaveButton" type="submit" class="btn btn-outline-dark">Save</button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
