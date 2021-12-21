<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Year Released</th>
        <th>Type</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($albumData as $album)
        <?php try { ?>
        <tr>
            <td>{{ $album->id }}</td>
            <td>
                {{ $album->AlbumName }}
            </td>
            <td>{{ $album->AlbumYear }}</td>
            <td>{{ $album->AlbumType }}</td>
            <td>{{ date('M d, Y', strtotime($album->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.albums', $album->id) }}" method="POST">
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
