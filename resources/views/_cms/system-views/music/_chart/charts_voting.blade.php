<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover m-0">
                    <thead>
                    <tr>
                        <th scope="col">Spot</th>
                        <th scope="col">Song</th>
                        <th scope="col">Artist</th>
                        <th scope="col">Online</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Social</th>
                        <th>Vote Source</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($chart as $charts)
                        <tr>
                            <td><div class="text-center">{{ $charts->position }}</div></td>
                            <td>{{ $charts->Song->name }}</td>
                            <td>{{ $charts->Song->Album->Artist->name }}</td>
                            <td>{{ $charts->online_votes }}</td>
                            <td>{{ $charts->phone_votes }}</td>
                            <td>{{ $charts->social_votes }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" id="voteButton" data-id="{{ $charts->id }}" data-device="phone" class="btn btn-outline-dark"><i class="fas fa-phone-alt"></i>  Phone Call</button>
                                    <button type="button" id="voteButton" data-id="{{ $charts->id }}" data-device="socmed" class="btn btn-outline-dark"><i class="fas fa-globe"></i>  Social Media</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="alert alert-danger text-danger text-center mb-0">
                                    No chart data found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
