@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div id="page_title" class="display-4">
                Award Information
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('awards.update', $data['awardee_id']) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="award_name" class="label">Award Name</label>
                                    <select id="award_name" name="award_name" class="custom-select">
                                        <option value="{{ $award->award_name }}" disabled selected>{{ $data['award_name'] }}</option>
                                        <option value="kbp">KBP Golden Dove Award</option>
                                        <option value="cmma">Catholic Mass Media Award</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label" for="select_awardee">Awardee</label>
                                    <select id="select_awardee" name="select_awardee" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                        <option value="jock">Jock</option>
                                        <option value="show">Show</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select id="awardee" name="awardee" class="custom-select">
                                        <option value="{{ $data['awardee_id'] }}">{{ $data['awardee'] }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="award_title" class="label">Title</label>
                                    <input type="text" id="award_title" name="award_title" class="form-control" value="{{ $award->award_title }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label" for="year">Year</label>
                                    <input type="text" id="year" name="year" class="form-control" value="{{ $award->year }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="special" class="label">Special Award</label>
                                    <select id="special" name="special" class="custom-select">
                                        <option value="{{ $award->special }}" selected>@if($award->special === 0) No @else Yes @endif</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="award_description" class="label">Award Description</label>
                                    <textarea id="award_description" name="award_description" class="form-control">{{ $award->award_description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark" id="saveBtn"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                            <a href="{{ route('awards.index') }}" class="btn btn-outline-dark">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
