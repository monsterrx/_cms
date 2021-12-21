<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>School</th>
        <th>Address</th>
        <th>Has Seal</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($schoolData as $school)
        <?php try { ?>
        <tr>
            <td>{{ $school->id }}</td>
            <td>
                {{ $school->school_name }}
            </td>
            <td>{{ $school->address }}</td>
            <td>
                @if($school->school_seal === '' || $school->school_seal === null)
                    No
                @else
                    Yes
                @endif
            </td>
            <td>{{ date('M d, Y', strtotime($school->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.schools', $school->id) }}" method="POST">
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
