<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table id="tdsTable" class="table table-hover m-0">
                    <thead>
                        <tr>
                            <th>Spot</th>
                            <th>Song</th>
                            <th>Artist</th>
                            <th>Album</th>
                            <th>Votes</th>
                            <th>Dated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($charts as $chart)
                            <tr>
                                <td><div class="text-center">{{ $chart->position }}</div></td>
                                <td>{{ $chart->Song->name }}</td>
                                <td>{{ $chart->Song->Album->Artist->name }}</td>
                                <td>{{ $chart->Song->Album->name }}</td>
                                <td>{{ $chart->total_votes ?? 0 }}</td>
                                <td style="color:red"><strong>{{ date('M d Y', strtotime($chart->dated)) }}</strong></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#new-chart" data-toggle="modal" data-position="{{ $chart->position }}" data-value="{{ $chart->id }}" data-date="{{ $chart->dated }}" class="btn btn-outline-dark"><i class="fa fa-edit"></i></a>
                                        <a href="#update-chart" data-toggle="modal" data-position="{{ $chart->position }}" data-value="{{ $chart->id }}" data-date="{{ $chart->dated }}" class="btn btn-outline-dark"><i class="fa fa-search"></i></a>
                                    </div>
                                </td>
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