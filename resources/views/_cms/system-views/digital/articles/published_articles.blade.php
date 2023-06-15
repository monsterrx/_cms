<h3 class="h3">Published Articles</h3>
<h6 class="h6">Card View</h6>
<hr class="my-4">
<div id="articlesContainer" class="row">
    @forelse($published as $articles)
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
            <div class="card" style="width: 19rem;  height: 520px;">
                <div class="card-img-top">
                    <img src="{{ $articles->image }}" class="img-fluid" alt="{{ $articles->image }}">
                </div>
                <div class="card-body">
                    <p class="lead">{{ Str::limit($articles->title, 45, '...') }}</p>
                </div>
                <div class="card-footer">
                    <div class="fa-pull-left">
                        <h6 class="lead">Published Date:</h6>
                        <p class="text-muted">{{ date('F d, Y', strtotime($articles->published_at)) }}</p>
                    </div>
                    <div class="fa-pull-right">
                        <a href="{{ route('articles.show', $articles->id) }}" class="btn btn-outline-dark">Details</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
                <div class="lead text-center">
                    No published articles found!
                </div>
            </div>
        </div>
    @endforelse
</div>
@if(count($published) > 1)
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group fa-pull-left">
                @if($previous === null || $previous === 'null' || !$previous)
                    <button type="previous" data-href="{{ $previous }}" data-article="published" class="btn btn-outline-dark" readonly disabled><i class="fas fa-arrow-left"></i>  Previous Page</button>
                @else
                    <button type="previous" data-href="{{ $previous }}" data-article="published" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i>  Previous Page</button>
                @endif

                @if($next === null || $next === 'null' || !$next)
                    <button type="next" data-href="{{ $next }}" data-article="published" class="btn btn-outline-dark" readonly disabled>Next Page <i class="fas fa-arrow-right"></i></button>
                @else
                    <button type="next" data-href="{{ $next }}" data-article="published" class="btn btn-outline-dark">Next Page <i class="fas fa-arrow-right"></i></button>
                @endif
            </div>
        </div>
    </div>
@endif
