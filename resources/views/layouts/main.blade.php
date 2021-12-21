<!DOCTYPE html>
<!-- saved from url=(0014)about:internet -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RX 93.1 &mdash; Employee</title>

    @include('components.links')
    @include('components.sweetalert')
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .zoom {
            transition: transform .2s;
        }

        .zoom:hover {
            transform: scale(1.05);
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/starter-template.css') }}" rel="stylesheet">
    <script data-dapp-detection="">
        (function() {
            let alreadyInsertedMetaTag = false;

            function __insertDappDetected() {
                if (!alreadyInsertedMetaTag) {
                    const meta = document.createElement('meta')
                    meta.name = 'dapp-detected'
                    document.head.appendChild(meta)
                    alreadyInsertedMetaTag = true
                }
            }

            if (window.hasOwnProperty('web3')) {
                // Note a closure can't be used for this var because some sites like
                // www.wnyc.org do a second script execution via eval for some reason.
                window.__disableDappDetectionInsertion = true
                // Likely oldWeb3 is undefined and it has a property only because
                // we defined it. Some sites like wnyc.org are evaling all scripts
                // that exist again, so this is protection against multiple calls.
                if (window.web3 === undefined) {
                    return
                }
                if (!window.web3.currentProvider ||
                    !window.web3.currentProvider.isMetaMask) {
                    __insertDappDetected()
                }
            } else {
                var oldWeb3 = window.web3;
                Object.defineProperty(window, 'web3', {
                    configurable: true,
                    set: function (val) {
                        if (!window.__disableDappDetectionInsertion)
                            __insertDappDetected();
                        oldWeb3 = val
                    },
                    get: function () {
                        if (!window.__disableDappDetectionInsertion)
                            __insertDappDetected();
                        return oldWeb3
                    }
                })
            }
        })()
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/monster-logo.png') }}" width="100px" alt="rx931_logo">
        </a>
        @yield('employee.nav')
    </nav>
    <div class="container-fluid">
        @include('_cms.system-views._feedbacks.success')
        @include('_cms.system-views._feedbacks.error')
        @yield('employee.content')
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @include('components.scripts')

    <div id="dialog" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Just making sure</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">Are you sure? Your changes won't be made</p>
                </div>
                <div class="modal-footer">
                    <div class="btn-group pull-left">
                        <button type="button" class="btn btn-outline-dark" onclick="window.location.reload()">Yes</button>
                        <button id="noButton" type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reportBug" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report a Bug</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="reportBugForm" action="{{ route('report.bug') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bugTitle">Title of the Bug</label>
                                    <input type="text" id="bugTitle" name="bugTitle" class="form-control" placeholder="Title" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bug-content">Describe the steps taken before encountering the bug below</label>
                                    <textarea name="bugDescription" id="bug-content" style="height: 400px;"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="bugImage" name="bugImage" required>
                                        <div class="custom-file-label">Insert the screenshot of the bug here</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('change.password') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Current Password">
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-dark">Save</button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function (){
            $('#employee-form').on('submit', function(event){
                event.preventDefault();

                let formData = new FormData(this);
                formData.set('_method', 'PUT');
                let url = $('#employee-form').attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: () => {
                        $('#submit-button').attr('disabled', 'disabled');
                    },
                    success: (response) => {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });

                        $('#name').html(formData.get('FirstName')+' '+formData.get('LastName'));
                        $('#name1').html(formData.get('FirstName')+' '+formData.get('LastName'));
                        $('#contactNumber').html(formData.get('ContactNo'))
                        $('#contactNumber1').html(formData.get('ContactNo'))

                        $('#update-employee').modal('hide');
                        $('#submit-button').removeAttr('disabled');
                    },
                    error: (error) => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Check the Console for Errors'
                        });

                        console.log(error);
                        $('#update-employee').modal('hide');
                        $('#submit-button').removeAttr('disabled');
                    },
                })
            });
        });
    </script>
</body>
</html>
