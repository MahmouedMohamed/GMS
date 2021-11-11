@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
<div>
    @forelse ($plans as $plan)
        <li>{{ $plan->name }}</li>
    @empty
        <p>No Plans</p>
    @endforelse
</div>
<a href="{{ route('gym.subscriptions.create') }}">Create New</a>
