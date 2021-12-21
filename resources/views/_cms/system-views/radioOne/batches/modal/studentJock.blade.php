<form id="update-student-jock-form" action="{{ route('radioOne.jocks.update', $student->id) }}" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" name="first_name" value="{{ $student->first_name }}" placeholder="First Name" />
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="{{ $student->last_name }}" placeholder="Last Name" />
        </div>
        <div class="form-group">
            <label for="nickname">Nickname</label>
            <input type="text" class="form-control" name="nickname" value="{{ $student->nickname }}" placeholder="Nickname" />
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <select id="position" name="position" class="custom-select">
                @if($student->position === 1)
                    <option value="1" selected>Heads</option>
                    <option value="2">Junior</option>
                    <option value="3">Babies</option>
                @elseif($student->position === 2)
                    <option value="1">Heads</option>
                    <option value="2" selected>Junior</option>
                    <option value="3">Babies</option>
                @elseif($student->position === 3)
                    <option value="1">Heads</option>
                    <option value="2">Junior</option>
                    <option value="3" selected>Babies</option>
                @else
                    <option value="1">Heads</option>
                    <option value="2">Junior</option>
                    <option value="3">Babies</option>
                @endif
            </select>
        </div>
        <div class="form-group">
            <label for="school_id">School</label>
            <select id="school_id" name="school_id" class="custom-select">
                <option value="{{ $student->School->id }}">{{ $student->School->name }}</option>
                @forelse($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @empty
                    <option value="{{ $student->School->id }}">{{ $student->School->name }}</option>
                @endforelse
            </select>
        </div>
        <div class="form-group">
            <div class="custom-file">
                <input type="file" name="image" id="image" class="custom-file-input" accept="image/*">
                <label for="" class="custom-file-label">Student Image</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="btn-group">
            <button type="submit" class="btn btn-outline-dark">Save</button>
            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
        </div>
    </div>
</form>
