<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>BatchNumber</th>
        <th>Batch Year Start</th>
        <th>Batch Year End</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($radioOneBatchData as $batch)
        <?php try { ?>
        <tr>
            <td>{{ $batch->id }}</td>
            <td>{{ $batch->batchNumber }}</td>
            <td>{{ date('Y', strtotime($batch->startYear)) }} &mdash; {{ date('Y', strtotime($batch->endYear)) }}</td>
            <td>{{ date('Y', strtotime($batch->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.radioOneBatch', $batch->id) }}" method="POST">
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
