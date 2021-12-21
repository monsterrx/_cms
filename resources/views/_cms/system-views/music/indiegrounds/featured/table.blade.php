<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover" id="featuredIndieTable">
                    <thead>
                    <tr>
                        <th>Artist</th>
                        <th>Date Featured</th>
                        <th>Classification</th>
                        <th>Option</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($featuredIndieArtists as $featuredIndieArtist)
                        <tr>
                            <td>{{ $featuredIndieArtist->Indie->Artist->name }}</td>
                            <td>{{ $featuredIndieArtist->date_featured }}</td>
                            <td>{{ $featuredIndieArtist->Indie->Artist->type }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#update-featured-indie" id="update-featured-artist" data-toggle="modal" data-id="{{ $featuredIndieArtist->id }}" class="btn btn-outline-dark"><i class="fas fa-search"></i></a>
                                    <a href="#delete-featured-indie" id="delete-featured-artist" data-toggle="modal" data-id="{{ $featuredIndieArtist->id }}" class="btn btn-outline-dark"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    featuredTable = $('#featuredIndieTable').DataTable();
</script>
