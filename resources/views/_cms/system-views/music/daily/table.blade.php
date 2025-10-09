<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                @if($charts->first() && $charts->first()->daily == 1 && $charts->first()->is_posted == 1)
                    <table id="tdsTable" class="table table-hover m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Song</th>
                                <th>Artist</th>
                                <th>Album</th>
                                <th>Votes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($charts as $chart)
                                <tr>
                                    <td><div class="text-center">{{ $chart->id }}</div></td>
                                    <td>{{ $chart->Song->name }}</td>
                                    <td>{{ $chart->Song->Album->Artist->name }}</td>
                                    <td>{{ $chart->Song->Album->name }}</td>
                                    <td>{{ $chart->online_votes }}</td>
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
                @else
                    <table id="tdsTable" class="table table-hover m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Song</th>
                                <th>Artist</th>
                                <th>Album</th>
                                <th>Votes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($charts as $chart)
                                <tr>
                                    <td><div class="text-center">{{ $chart->id }}</div></td>
                                    <td>{{ $chart->Song->name }}</td>
                                    <td>{{ $chart->Song->Album->Artist->name }}</td>
                                    <td>{{ $chart->Song->Album->name }}</td>
                                    <td>{{ $chart->online_votes }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#delete-chart" data-toggle="modal" data-position="{{ $chart->position }}" data-value="{{ $chart->id }}" data-date="{{ $chart->dated }}" class="btn btn-outline-dark"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                @endif
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
            </div>
        </div>
    </div>
</div>