<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Genre</th>
        <th>Genre Description</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($genreData as $genre)
        <?php try { ?>
        <tr>
            <td>{{ $genre->id }}</td>
            <td>
                {{ $genre->name }}
            </td>
            <td>{{ $genre->GenreDescription }}</td>
            <td>{{ date('M d, Y', strtotime($genre->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.genres', $genre->id) }}" method="POST">
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
