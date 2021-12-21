<div id="dropModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Drop Song</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('charts.drop') }}">
                <div class="modal-body">
                    @csrf
                    <input type="text" id="chart_id" value="{{ $chart->id }}" style="display: none;">
                    <p class="lead">Drop {{ $chart->Song->SongName }} from the Charts?</p>
                </div>
                <div class="modal-footer">
                    <div class="btn-group fa-pull-right">
                        <button type="submit" class="btn btn-outline-dark">Yes</button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="deleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Delete Song</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('charts.destroy', $chart->id) }}">
                <div class="modal-body">
                    @csrf
                    @method('DELETE')
                    <input type="text" id="chart_id" value="{{ $chart->id }}" style="display: none;">
                    <p class="h6">Delete {{ $chart->Song->SongName }}?</p>
                    <p class="lead text-danger">This will delete the whole record of the song.</p>
                </div>
                <div class="modal-footer">
                    <div class="btn-group fa-pull-right">
                        <button type="submit" class="btn btn-outline-dark">Yes</button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
