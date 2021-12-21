@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Monster Scholar
            </div>
            <h2 class="h2">Sponsors</h2>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#newSponsor" class="btn btn-outline-dark fa-pull-right" data-toggle="modal">New Sponsor</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th>Sponsor</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($sponsor as $sponsors)
                                        <tr data-toggle="modal" data-target="#sponsor{{ $sponsors->id }}">
                                            <td>{{ $sponsors->name }}</td>
                                            <td>
                                                @if($sponsors->remarks === "" || $sponsors->remarks === null)
                                                    No Remarks Available
                                                @else
                                                    {{ $sponsors->remarks }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="newSponsor" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="lead">New Sponsor</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('sponsors.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="lead">Sponsor Name</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <label for="remarks" class="lead">Remarks</label>
                                    <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-dark fa-pull-right"><i class="fa fa-save"></i>  Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @foreach($sponsor as $sponsors)
        <div id="sponsor{{ $sponsors->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="lead">{{ $sponsors->name }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="POST" action="{{ route('sponsors.update', $sponsors->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name" class="lead">Sponsor Name</label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ $sponsors->name }}" placeholder="Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks" class="lead">Remarks</label>
                                        @if($sponsors->remarks === "" || $sponsors->remarks === null)
                                            <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Remarks">
                                        @else
                                            <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Remarks" value="{{ $sponsors->remarks }}">
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-outline-dark"><i class="fa fa-save"></i>  Save</button>
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
