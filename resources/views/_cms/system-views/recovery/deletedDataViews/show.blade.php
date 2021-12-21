<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Front Description</th>
        <th>Specials</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($showData as $show)
        <?php try { ?>
        <tr>
            <td>{{ $show->id }}</td>
            <td>
                {{ $show->title }}
            </td>
            <td>{{ $show->frontDescription }}</td>
            <td>
                @if($show->is_special === 0)
                    No
                @elseif($show->is_special === 1)
                    Yes
                @else
                    Undefined
                @endif
            </td>
            <td>{{ date('M d, Y', strtotime($show->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.shows', $show->id) }}" method="POST">
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
