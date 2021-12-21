<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Nickname</th>
        <th>School</th>
        <th>Batch</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($data as $studentJocks)
        @foreach($studentJocks->Batch as $batch)
            <?php try { ?>
            <tr>
                <td>{{ $studentJocks->id }}</td>
                <td>{{ $studentJocks->firstName }} {{ $studentJocks->lastName }}</td>
                <td>{{ $studentJocks->nickName }}</td>
                <td>{{ $studentJocks->School->school_name }}</td>
                <td>{{ $batch->batchNumber }}</td>
                <td>{{ date('F m, Y', strtotime($studentJocks->deleted_at)) }}</td>
                <td>
                    <form action="{{ route('recover.radioOneJock', $studentJocks->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-dark" title="Restore"><i class="fas fa-plus-square"></i></button>
                    </form>
                </td>
            </tr>
            <?php } catch (ErrorException $e) { ?>

        <?php } ?>
        @endforeach
    @empty
    @endforelse
    </tbody>
</table>
