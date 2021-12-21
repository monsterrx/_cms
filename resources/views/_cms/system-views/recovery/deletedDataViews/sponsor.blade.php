<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Remarks</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($sponsorData as $sponsor)
        <?php try { ?>
        <tr>
            <td>{{ $sponsor->id }}</td>
            <td>{{ $sponsor->sponsor_name }}</td>
            <td>{{ $sponsor->sponsor_remarks }}</td>
            <td>{{ date('M d, Y', strtotime($sponsor->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.sponsors', $sponsor->id) }}" method="POST">
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
