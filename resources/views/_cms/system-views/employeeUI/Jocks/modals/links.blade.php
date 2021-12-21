<div class="col-md-6">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title h3">
                Social Media Links
            </div>
        </div>
        <div class="modal-body">
            <div class="row m-0">
                @if($link->isNotEmpty())
                    @foreach($link as $links)
                        <?php try { ?>
                            <div class="col-md-12">
                                <div class="card zoom mb-1">
                                    <div class="card-body text-center">
                                        <div class="lead">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if ($links->website === 'Facebook')
                                                        <img src="{{ asset("images/social_media/facebook.png") }}" alt="facebook_logo" width="70px" class="circle">
                                                    @elseif ($links->website === 'Twitter')
                                                        <img src="{{ asset("images/social_media/twitter.png") }}" alt="twitter_logo" width="70px" class="circle">
                                                    @elseif ($links->website === 'Youtube')
                                                        <img src="{{ asset("images/social_media/youtube.png") }}" alt="youtube_logo" width="70px" class="circle">
                                                    @elseif ($links->website === 'Instagram')
                                                        <img src="{{ asset("images/social_media/instagram.png") }}" alt="instagram_logo" width="70px" class="circle">
                                                    @else
                                                        <img src="{{ asset("images/social_media/other.png") }}" alt="other_logo" width="70px" class="circle">
                                                    @endif
                                                </div>
                                                <div class="col-md-8">
                                                    <span class="title">{{ $links->website }}</span>
                                                    <p><a href="//{{ $links->url }}" target="_blank">{{ $links->url }}</a><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } catch (ErrorException $e) { ?>
                            <div class="col-md-12">
                                <div class="alert alert-warning text-center alert mt-1">
                                    <p class="h6 m-0">Your link has been deleted, please contact IT developer.</p>
                                </div>
                            </div>
                        <?php } ?>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="alert alert-secondary text-center alert mt-1">
                            <p class="h6 m-0">Add web links by clicking your name then profile!</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
