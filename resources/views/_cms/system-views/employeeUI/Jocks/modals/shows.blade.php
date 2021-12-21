<div class="col-md-12">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title h3">
                Your Shows
            </div>
        </div>
        <div class="modal-body">
            <div class="row m-0">
                @if($show->isNotEmpty())
                    @foreach($show as $shows)
                        <?php try { ?>
                        <div class="col-md-3">
                            <div class="card zoom">
                                <div class="card-body text-center">
                                    <div class="lead">{{ $shows->title }}</div>
                                </div>
                            </div>
                        </div>
                        <?php } catch (ErrorException $e) { ?>
                        <div class="col-md-3">
                            <div class="alert alert-warning text-center alert mt-1">
                                <p class="h6 m-0">Your show has been deleted, contact the IT Developer.</p>
                            </div>
                        </div>
                        <?php } ?>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="alert alert-secondary text-center alert mt-1">
                            <p class="h6 m-0">You don't have any shows yet.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>