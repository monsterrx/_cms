<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table id="dailyChartSongsTable" class="table table-hover m-0">
                    <thead>
                        <tr>
                            <th>Spot</th>
                            <th>Song</th>
                            <th>Artist</th>
                            <th>Album</th>
                            <th>Votes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($songs as $key => $song)
                            <tr>
                                <td><div class="text-center">{{ $key + 1 }}</div></td>
                                <td>{{ $song->name }}</td>
                                <td>{{ $song->Album->Artist->name }}</td>
                                <td>{{ $song->Album->name }}</td>
                                <td>{{ $song->votes ?? 0 }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
            </div>
        </div>
    </div>
</div>