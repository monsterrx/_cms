<div class="col-md-6 col-xs-12 col-sm-12 col-lg-4 mb-4">
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="student_id" class="card-title lead">Student</label>
                <select class="custom-select" id="student_id" name="student_id[]" required>
                    <option value>--</option>
                    @forelse($data['student'] as $student)
                        <option value="{{ $student->id }}">{{ $student->student_name }}</option>
                    @empty
                        <option value>No Available Data</option>
                    @endforelse
                </select>
            </div>
            <div class="form-group">
                <label for="scholar_type" class="lead">Scholar Type</label>
                <select id="scholar_type" name="scholar_type[]" class="custom-select">
                    <option value="0">Official</option>
                    <option value="1">Sponsored</option>
                </select>
            </div>
        </div>
    </div>
</div>