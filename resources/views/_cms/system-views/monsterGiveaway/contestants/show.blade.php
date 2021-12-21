@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Contestant
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('contestants.index') }}" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i>  Back</a>
                    <div class="text-center h2">
                        {{ $contestant->firstName }} {{ $contestant->lastName }}
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fa-pull-right">
                                        <div class="my-2">
                                            <div class="h4">Phone Number</div>
                                            <p class="h5" style="font-weight: 300">{{ $contestant->phoneNumber }}</p>
                                        </div>
                                        <div class="my-3">
                                            <div class="h4">Email</div>
                                            <p class="h5" style="font-weight: 300">{{ $contestant->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <div class="h4">Birthdate</div>
                                        <p class="h5" style="font-weight: 300">{{ date('F m, Y', strtotime($contestant->birthDate)) }}</p>
                                    </div>
                                    <div class="my-3">
                                        <div class="h4">City/Municipality</div>
                                        <p class="h5" style="font-weight: 300">{{ $contestant->city }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-3">Giveaways Joined</h2>
                </div>
            </div>
            <hr class="my-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-carousel">
                        @forelse($contestant->Giveaway as $giveaway)
                            <div class="card zoom mx-3 my-3">
                                <img width="100px" src="/images/giveaways/{{ $giveaway->eventImage }}" alt="{{ $giveaway->eventImage }}">
                                <div class="card-body text-center">
                                    <div class="card-title lead">{{ str_limit($giveaway->name, $limit = 25, $end = '...') }}</div>
                                    <div class="text-muted">
                                        @if($giveaway->type === 'movies')
                                            Monster Movie Premiere
                                        @elseif($giveaway->type === 'concerts')
                                            Concert Tickets
                                        @else
                                            Undefined
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('giveaways.show', $giveaway->id) }}" class="btn btn-outline-dark" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fas fa-paper-plane"></i></a>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
