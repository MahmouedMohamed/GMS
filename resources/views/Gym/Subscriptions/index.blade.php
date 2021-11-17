@extends('layouts.app')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="container">
        <div class="row row-cols-3 row-cols-md-2 g-4">
            @forelse ($plans as $plan)
                <div class="col row pr-0 m-1">
                    <div class="card text-white bg-dark">
                        <img src="https://picsum.photos/200/300" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $plan->Name }}</h5>
                            <p class="card-text">Enjoy {{ $plan->NumberofMonths }}
                                {{ $plan->NumberofMonths > 1 ? 'Months' : 'Month' }} of with only
                                {{ $plan->Cost }} EGP.</p>
                            <div class="row justify-content-around">
                                <a href="{{ route('gym.subscriptions.edit', ['subscription' => $plan->ID]) }}"
                                    class="btn btn-primary ">Edit</a>
                                <form action="{{ route('gym.subscriptions.destroy', ['subscription' => $plan->ID]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                        @if ($plan->HasDiscount)
                        <div class="bg-red-500 text-white position-absolute top-0 right-0">Discount</div>
                    @endif
                    </div>

                </div>
            @empty
                <p>No Plans</p>
            @endforelse
        </div>
    </div>
    <a href="{{ route('gym.subscriptions.create') }}">Create New</a>
@endsection
