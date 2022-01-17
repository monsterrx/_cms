<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Show</th>
                        <th>Jock Name</th>
                        <th>Timeslot</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($timeslots as $timeslot)
                        @foreach($timeslot->Jock as $jock)
                            <tr>
                                <td>{{ $timeslot->Show->title }}</td>
                                <td>{{ $jock->name }}</td>
                                <td>{{ date('h:i a', strtotime($timeslot->start)) }} to {{ date('h:i a', strtotime($timeslot->end)) }}</td>
                                <td>
                                    <a href="{{ route('timeslot.remove.jock', [$timeslot->id, $jock->id]) }}" class="btn btn-outline-dark">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-warning lead text-center mb-0">
                                    No jock schedule found
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
