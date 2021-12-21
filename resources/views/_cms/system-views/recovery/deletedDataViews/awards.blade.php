<table class="table table-hover" id="genericTable">
    <thead>
    <tr>
        <th>Id</th>
        <th>Show/Jock</th>
        <th>Award</th>
        <th>Title</th>
        <th>Year Achieved</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($awardData as $awards)
        <?php try { ?>
        <tr>
            <td>{{ $awards->id }}</td>
            <td>
                @if($awards->jock_id)
                    {{ $awards->Jock->jock_name }}
                @elseif($awards->show_id)
                    {{ $awards->Show->title }}
                @else
                    N/A
                @endif
            </td>
            <td>{{ $awards->award_name }}</td>
            <td>{{ $awards->award_title }}</td>
            <td>{{ date('Y', strtotime($awards->year)) }}</td>
            <td>
                <form action="{{ route('recover.awards', $awards->id) }}" method="POST">
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
