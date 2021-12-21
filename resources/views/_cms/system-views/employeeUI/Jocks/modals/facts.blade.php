<div class="col-md-6">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title h3">
                Facts About You
            </div>
        </div>
        <div class="modal-body">
            <div class="row">
                @if($fact->isNotEmpty())
                    @foreach($fact as $facts)
                        <?php try { ?>
                        <div class="col-md-12">
                            <div class="card zoom">
                                <div class="card-body text-center">
                                    <div class="lead">{{ $facts->content }}</div>
                                </div>
                            </div>
                        </div>
                        <?php } catch (ErrorException $e) { ?>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="lead">Your fact has been deleted, please contact IT developer.</div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="alert alert-secondary text-center alert mt-1">
                                <p class="h6 m-0">Add facts about yourself, click your name then profile!</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
