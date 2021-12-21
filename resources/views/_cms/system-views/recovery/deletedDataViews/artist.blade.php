<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Artist</th>
        <th>Country</th>
        <th>Type</th>
        <th>Date Deleted</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($artistData as $artists)
        <?php try { ?>
        <tr>
            <td>{{ $artists->id }}</td>
            <td>
                {{ $artists->Name }}
            </td>
            <td>{{ $artists->ArtistCountry }}</td>
            <td>{{ $artists->ArtistType }}</td>
            <td>{{ date('M d, Y', strtotime($artists->deleted_at)) }}</td>
            <td>
                <form action="{{ route('recover.artists', $artists->id) }}" method="POST">
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
