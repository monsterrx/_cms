<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover m-0">
                    <thead>
                        <tr>
                            <th>Spot</th>
                            <th>Song</th>
                            <th>Artist</th>
                            <th>Album</th>
                            <th>Dated</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chart as $charts)
                            <tr>
                                <td><div class="text-center">{{ $charts->position }}</div></td>
                                <td>{{ $charts->Song->name }}</td>
                                <td>{{ $charts->Song->Album->Artist->name }}</td>
                                <td>{{ $charts->Song->Album->name }}</td>
                                <td style="color:red"><strong>{{ date('M d Y', strtotime($charts->dated)) }}</strong></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#new-chart" data-toggle="modal" data-position="{{ $charts->position }}" data-value="{{ $charts->id }}" data-date="{{ $charts->dated }}" class="btn btn-outline-dark"><i class="fa fa-edit"></i></a>
                                        <a href="#update-chart" data-toggle="modal" data-position="{{ $charts->position }}" data-value="{{ $charts->id }}" data-date="{{ $charts->dated }}" class="btn btn-outline-dark"><i class="fa fa-search"></i></a>
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
