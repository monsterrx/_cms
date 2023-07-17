@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <a href="{{ route('articles.index') }}" class="btn btn-outline-dark" ><i class="fa fa-arrow-left"></i>  Back</a>
            <div class="my-4"></div>
            <div class="lead">Article Id: {{ $article->unique_id }}</div>
            <hr class="my-4">
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <img src="{{ url('images/articles/'.$article->image)}}" class="img-fluid card-img-top" alt="{{ $article->image }}">
                        <div class="card-body">
                            <span class="card-title lead">{{ $article->title }}</span>
                            <p class="text-muted" style="font-weight: 300;">{{ $article->heading }}</p>
                            <p class="text-muted" style="font-weight: 300;">{{ $article->Category->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="col-md-12">
                        <p class="lead" style="margin-bottom: 0">Article Name</p>
                        <span class="lead text-muted" id="">{{ $article->title }}</span>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <p class="lead" style="margin-bottom: 0">Category</p>
                        <span class="lead text-muted" id="">{{ $article->Category->name }}</span>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <p class="lead" style="margin-bottom: 0">Published Date</p>
                        @if( $article->published_at === null )
                            <span class="lead text-muted" id="" style="color:red">Not Published</span>
                        @else
                            <span class="lead text-muted" id="">{{ date('M d, Y', strtotime($article->published_at)) }}</span>
                        @endif
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="#editArticleModal" class="btn btn-outline-dark btn-block" data-toggle="modal"><i class="fa fa-edit"></i>&nbsp;Edit</a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('article.preview', $article->id) }}" class="btn btn-outline-dark btn-block"><i class="fa fa-search"></i>&nbsp;&nbsp;Preview</a>
                            </div>
                            <div class="col-md-4">
                                <a href="#addArticleImage" class="btn btn-outline-dark btn-block" data-toggle="modal"><i class="fa fa-image"></i>&nbsp;Add Image</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <!-- CONTENT -->
            <div class="row">
                <h5 class="lead text-left">More Content</h5>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Content</th>
                            <th>View</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($article->Content as $contents)
                                <?php try { ?>
                            <tr>
                                <td><p>{{ Str::limit($contents->content, $limit = 90, $end = '...') }}</p></td>
                                <td><a href="{{ route('sub_contents.show', [$article->id, $contents->id]) }}">Edit/Delete</a></td>
                            </tr>
                            <?php } catch (ErrorException $e) { ?>
                            <td>
                                <div class="alert alert-danger text-center lead">
                                    Deleted Data
                                </div>
                            </td>
                            <?php } ?>
                        @empty
                            <tr>
                                <td colspan="2">
                                    <div class="h4 text-info lead" role="alert">
                                        No contents found
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('sub_contents.create', $article->id) }}">
                        <button class="btn btn-outline-dark">
                            <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;ADD CONTENT
                        </button>
                    </a>
                </div>
            </div>
            <!-- END -->
            <hr class="my-4">
            <br>
            <div class="row">
                <h5 class="lead text-left">Images</h5>
            </div>
            <hr class="my-4">
            <div class="row mb-3">
                @forelse($article->Image as $images)
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-img-top">
                                <img class="img-fluid" src="{{ url('images/articles/'.$images->file) }}" alt="{{ $images->file }}">
                            </div>
                            <div class="card-footer">
                                <a href="#deletePhotoModal-{{ $images->id }}" class="btn btn-outline-dark" data-toggle="modal">
                                    <i class="fa fa-trash-alt"></i>&nbsp;&nbsp;DELETE
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deletePhotoModal-{{ $images->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Are you sure to delete this photo?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('articles.remove.image') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" id="image_id" name="id" value="{{ $images->id }}">
                                                <div class="btn-group fa-pull-right">
                                                    <button type="submit" class="btn btn-outline-dark">Yes</button>
                                                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="h4 text-info text-center lead">
                            No Related Images
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="#addPhotoModal" class="btn btn-outline-dark" data-toggle="modal">
                        <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;ADD PHOTO
                    </a>
                </div>
            </div>
            <br>
            <!-- END -->
            <br>
            <div class="row">
                <h5 class="lead text-left">Related Articles</h5>
            </div>
            <hr class="my-4">
            <div class="row mb-3">
                @forelse($article->Relevant as $relatedArticles)
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-img-top">
                                <img class="img-fluid" src="{{ url('images/articles/'.$relatedArticles->RelatedArticle->image) }}" alt="{{ $relatedArticles->RelatedArticle->image }}">
                            </div>
                            <div class="card-body">
                                <a class="lead text-decoration-none" href="{{ route('articles.show', [$relatedArticles->related_article_id]) }}" target="_blank" title="{{ $relatedArticles->RelatedArticle->title }}">
                                    {{ Str::limit($relatedArticles->RelatedArticle->title, '35', '...') }}
                                </a>
                            </div>
                            <div class="card-footer">
                                <a href="#deleteRelated-{{ $relatedArticles->related_article_id }}" class="btn btn-outline-dark" data-toggle="modal">
                                    <i class="fa fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="deleteRelated-{{ $relatedArticles->related_article_id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Are you sure to delete this related article?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('articles.remove.related', $article->id) }}" id="delete_post2">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="lead">The article "<span class="font-weight-bold">{{ $relatedArticles->RelatedArticle->title }}</span>" will be removed from the related articles</p>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="text" id="image_id" name="related_article_id" value="{{ $relatedArticles->related_article_id }}" style="display: none;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="btn-group fa-pull-right">
                                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="h4 text-info text-center lead">
                            No Related Articles
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="#addRelatedModal" class="btn btn-outline-dark" data-toggle="modal">
                        <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;ADD RELATED ARTICLES
                    </a>
                </div>
            </div>
            <br>
            <!-- END -->
            <br>
            <div class="row mb-5">
                @if($article->published_at === null)
                    <div class="col-md-6">
                        <a href="#publish-article" class="btn btn-outline-dark btn-block" data-toggle="modal">
                            <i class="fas fa-book"></i>&nbsp;&nbsp;Publish This Article?
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-dark btn-block" >Back on List</a>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="text-center lead">
                            Published Date: {{ date('M d, Y', strtotime($article->published_at)) }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="publish-article" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Publish this Article?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('articles.publish', $article->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" id="article_id" name="id" value="{{ $article->id }}" style="display:none;">
                                <div class="lead">
                                    Publish <span class="h5">{{ $article->title }}</span>?
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editArticleModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="lead">Edit Article {{ $article->title }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('articles.update', $article->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="article_Title" class="label lead">Title</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ $article->title }}">
                        </div>
                        <div class="form-group">
                            <label for="categories_id" class="label lead">Category</label>
                            <select id="category_id" name="category_id" class="custom-select">
                                <option value="{{ $article->categories_id }}" selected>{{ $article->Category->name }}</option>
                                @foreach($category as $categories)
                                    <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="heading" class="label lead">Content</label>
                            <textarea id="heading" name="heading" class="form-control">{{ $article->heading }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="published_at" class="label lead">Publish Date: </label>
                            <input type="date" id="published_at" name="published_at" class="form-control" value="{{ date('Y-m-d', strtotime($article->published_at)) }}">
                        </div>
                        <div class="my-4">
                            <button type="submit" class="btn btn-outline-dark float-right"><i class="fas fa-save"></i>  Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('articles.destroy', $article->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Delete</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addArticleImage" tabindex="-1" role="dialog" aria-labelledby="addArticleImage" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Article Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div id="imageCropper" class="cropper img-fluid"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" id="article_id" name="article_id" value="{{ $article['id'] }}" style="display: none;">
                            <input type="text" class="form-control" id="croppedArticleImage" name="croppedArticleImage" style="display: none;">
                        </div>
                        <div class="custom-file">
                            <input type="file" id="image" name="image" class="custom-file-input">
                            <label for="image" class="custom-file-label">Add Image</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="crop" class="btn btn-outline-dark">Crop</button>
                            <button type="submit" id="saveButton" class="btn btn-outline-dark" hidden>Save</button>
                            <button type="button" id="cancelButton" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="addPhotoModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title lead">
                        Add Related Images
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('photos.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="custom-file">
                                    <input id="article_id" name="article_id" type="text" class="form-control" style="display: none;" value="{{ $article->id }}">
                                    <label for="file" class="custom-file-label">Extra Images</label>
                                    <input type="file" name="file[]" class="custom-file-input" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <button type="submit" class="btn btn-outline-dark float-right">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="addRelatedModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title lead">
                        Add Related Articles
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('articles.add.related', $article->id) }}">
                        @csrf
                        <label for="article_related" class="label">Related To:</label>
                        <select id="article_related" name="related_article_id" class="custom-select">
                            <option value>--</option>
                            @forelse($articles as $relatedArticle)
                                <option value="{{ $relatedArticle->id }}">{{ $relatedArticle->title }}</option>
                            @empty
                                <option>No Data Found</option>
                            @endforelse
                        </select>
                        <br><br>
                        <button type="submit" class="btn btn-outline-dark">Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    {{-- END --}}
@endsection
