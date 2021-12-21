@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Messages
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="messageTable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Date</th>
                                    <th>Sender</th>
                                    <th>Topic</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($message as $messages)
        <!-- Modal -->
        <div class="modal fade" id="view-{{ $messages->id }}" data-action="Open" data-message="{{ $messages->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Message Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <hr class="my-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="h5">Received: <span style="font-weight: 300;">{{ $messages->created_at }}</span></div>
                                </div>
                            </div>
                            <div class="my-4"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="h5">From: <span style="font-weight: 300;">{{ $messages->email }} ({{ $messages->name }})</span></div>
                                </div>
                            </div>
                            <div class="my-4"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="h5">Contact: <span style="font-weight: 300;">{{ $messages->contact_number }}</span></div>
                                </div>
                            </div>
                            <div class="my-4"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="h5">Subject: <span style="font-weight: 300; text-transform: capitalize;">{{ $messages->topic }}</span></div>
                                </div>
                            </div>
                            <hr class="my-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mx-4 my-4 h4" style="font-weight: 300;">{{ $messages->content }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
