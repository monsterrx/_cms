<div class="col-md-6 col-xs-12 col-sm-12 col-lg-4 mb-4">
    <div class="card">
        <div class="card-body">
            <label for="sponsor_id" class="card-title lead">Sponsor</label>
            <select class="custom-select" id="sponsor_id" name="sponsor_id[]" required>
                <option value>--</option>
                @forelse($data['sponsor'] as $sponsor)
                    <option value="{{ $sponsor->id }}">{{ $sponsor->sponsor_name }}</option>
                @empty
                    <option value>No Available Data</option>
                @endforelse
            </select>
        </div>
    </div>
</div>