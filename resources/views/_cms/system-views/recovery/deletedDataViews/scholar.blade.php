<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Year Level</th>
        <th>Course</th>
        <th>School</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($scholarData as $student)
        <?php try { ?>
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
            <td>{{ $student->year_level }}</td>
            <td>{{ $student->course }}</td>
            <td>{{ $student->School->school_name }}</td>
            <td>{{ date('M d, Y', strtotime($student->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.scholars', $student->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-dark" title="Restore"><i class="fas fa-plus-square"></i></button>
                </form>
            </td>
        </tr>
        <?php } catch (ErrorException $e) { ?>

        <?php } ?>
    @empty
    @endforelse
    </tbody>
</table>
