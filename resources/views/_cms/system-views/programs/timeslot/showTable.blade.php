<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Show</th>
                        <th>Timeslot</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($timeslots as $timeslot)
                        <tr>
                            <td>{{ $timeslot->Show->id }}</td>
                            <td>{{ $timeslot->Show->title }}</td>
                            <td>{{ date('h:i A', strtotime($timeslot->start)) }} to {{ date('h:i A', strtotime($timeslot->end)) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#edit-timeslot" id="edit-timeslot-toggler" data-id="{{ $timeslot->id }}" type="show" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-search"></i></a>
                                    <button type="button" class="btn btn-outline-dark" id="addJockDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="addJockDropdown">
                                        @foreach($jocks as $jock)
                                            <a href="{{ route('timeslot.add.jock', [$timeslot->id, $jock->id]) }}" class="dropdown-item">{{ $jock->name }} ({{ $jock->Employee->first_name }} {{ $jock->Employee->last_name }}) @if($jock->Employee->location === $station) @elseif($jock->Employee->location === 'cbu') (Cebu) @elseif($jock->Employee->location === 'dav') (Davao) @endif</a>
                                        @endforeach
                                    </div>
                                    <a href="#delete-timeslot" id="delete-timeslot-toggler" data-id="{{ $timeslot->id }}" type="show" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-warning lead text-center mb-0">
                                    No show schedule found
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
