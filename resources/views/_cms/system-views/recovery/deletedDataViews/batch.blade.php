<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Batch Number</th>
        <th>Semester</th>
        <th>School Year</th>
        <th>Description</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($batchData as $batch)
        <?php try { ?>
        <tr>
            <td>{{ $batch->id }}</td>
            <td>{{ $batch->number }}</td>
            <td>{{ $batch->semester }}</td>
            <td>{{ date("Y", strtotime($batch->school_year_start)) }}&nbsp;&#45;&nbsp;{{ date("Y", strtotime($batch->school_year_end)) }}</td>
            <td>{{ $batch->description }}</td>
            <td>{{ date('M d, Y', strtotime($batch->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.batches', $batch->id) }}" method="POST">
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
