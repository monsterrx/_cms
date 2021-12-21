<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Song</th>
        <th>Artist</th>
        <th>Album</th>
        <th>Year</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($songData as $songs)
        <?php try { ?>
        <tr>
            <td>{{ $songs->id }}</td>
            <td>
                {{ $songs->SongName }}
            </td>
            <td>{{ $songs->Album->Artist->Name }}</td>
            <td>{{ $songs->Album->AlbumName }}</td>
            <td>{{ date('Y', strtotime($songs->Album->AlbumYear)) }}</td>
            <td>{{ date('M d, Y', strtotime($songs->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.songs', $songs->id) }}" method="POST">
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
