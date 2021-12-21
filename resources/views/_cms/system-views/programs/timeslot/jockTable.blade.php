<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Jock Id</th>
                        <th>Jock Name</th>
                        <th>Timeslot</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($timeslots as $timeslot)
                        <tr>
                            <td>{{ $timeslot->Jock->id }}</td>
                            <td>{{ $timeslot->Jock->name }}</td>
                            <td>{{ date('h:i a', strtotime($timeslot->start)) }} to {{ date('h:i a', strtotime($timeslot->end)) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#edit-timeslot" id="edit-timeslot-toggler" data-id="{{ $timeslot->id }}" type="jock" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-search"></i></a>
                                    <a href="#delete-timeslot" id="delete-timeslot-toggler" data-id="{{ $timeslot->id }}" type="jock" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
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
