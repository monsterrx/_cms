@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Mobile App Assets
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-lg-12">
                    <a href="#new-asset" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Asset</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="mobileAppAssetTable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>App</th>
                                        <th>Style</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monster_assets as $title)
                                        <tr data-href="{{ route('asset.show', $title->id) }}" onclick="viewData()">
                                            <td>{{ $title->id }}</td>
                                            <td>
                                                @if($title->location === "mnl")
                                                    <div class="badge badge-primary">Monster RX93.1</div>
                                                @elseif($title->location === "cbu")
                                                    <div class="badge badge-warning">Monster BT105.9 Cebu</div>
                                                @else
                                                    <div class="badge badge-dark">Monster BT99.5 Davao</div>
                                                @endif
                                            </td>
                                            <td>
                                                @foreach($title->Asset as $asset)
                                                    @if($asset->is_dark_mode === 0)
                                                        <div class="badge badge-light">Light</div>
                                                    @else
                                                        <div class="badge badge-dark">Dark</div>
                                                    @endif
                                                @endforeach
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

    <div id="new-asset" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Assets" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Mobile App Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="mobileAssetForm" method="POST" action="{{ route('asset.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="style">Style</label>
                                    <select id="style" name="is_dark_mode" class="custom-select">
                                        <option value>--</option>
                                        <option value="0">Light</option>
                                        <option value="1">Dark</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="my-3"></div>
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
@endsection
