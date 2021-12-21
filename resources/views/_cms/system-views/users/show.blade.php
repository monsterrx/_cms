@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                User Information
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <div class="lead fa-pull-right">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="my-4"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="h2 text-center" style="font-weight: 300;">{{ $user->Employee->first_name }} {{ $user->Employee->last_name }}</div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="employee_number" class="h3">Employee Number:</label>
                    <div id="employee_number" class="h4" style="font-weight: 300; color: red;">{{ $user->Employee->employee_number }}</div>
                </div>
                <div class="col-md-4">
                    <label for="email" class="h3">Email:</label>
                    <div id="email" class="h4" style="font-weight: 300;">{{ $user->email }}</div>
                </div>
                <div class="col-md-4">
                    <label for="DesignationName" class="h3">Designation:</label>
                    <div id="DesignationName" class="h4" style="font-weight: 300;">{{ $user->Employee->Designation->name }}</div>
                </div>
            </div>
            <div class="my-5"></div>
            <div class="row">
                <div class="col-md-12">
                    <label for="Address" class="h3">Address:</label>
                    <div id="Address" class="h4" style="font-weight: 300;">{{ $user->Employee->address }}</div>
                </div>
            </div>
            <div class="my-5"></div>
            <div class="row">
                <div class="col"></div>
                <div class="col-md-4">
                    <label for="birthday" class="h3">Birthday:</label>
                    <div id="birthday" class="h4" style="font-weight: 300;">{{ date('F d, Y', strtotime($user->Employee->birthday)) }}</div>
                </div>
                <div class="col-md-4">
                    <label for="ContactNo" class="h3">Contact Number:</label>
                    <div id="ContactNo" class="h4" style="font-weight: 300;">{{ $user->Employee->contact_number }}</div>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </div>
@endsection
