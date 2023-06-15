<h3>Pending Unpublished Articles</h3>
<hr class="my-4">
<div id="articlesContainer" class="row">
    @forelse($unpublished as $article)
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
            <div class="card" style="width: 19rem;  height: 520px;">
                <div class="card-img-top">
                    <img src="{{ $article->image }}" class="img-fluid" alt="{{ $article->image }}">
                </div>
                <div class="card-body">
                    <p class="lead">{{ $article->title }}</p>
                </div>
                <div class="card-footer">
                    <div class="fa-pull-left">
                        <h6 class="lead">Published Date:</h6>
                        <p class="text-danger">Unpublished</p>
                    </div>
                    <div class="fa-pull-right">
                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-outline-dark">Details</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-12">
            <div class="alert alert-success" role="alert">
                <div class="lead text-center">
                    No pending articles found!
                </div>
            </div>
        </div>
    @endforelse
</div>
@if(count($unpublished) > 1)
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group fa-pull-left">
                <button type="previous" data-href="{{ $previous }}" data-article="unpublished" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i>  Previous Page</button>
                <button type="next" data-href="{{ $next }}" data-article="unpublished" class="btn btn-outline-dark">Next Page <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
@endif
