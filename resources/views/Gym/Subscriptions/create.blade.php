<form method="POST" action="{{ route('gym.subscriptions.store') }}">
    @csrf

    <label for="numberOfMonths">Number of Months</label>

    <input id="numberOfMonths" name="numberOfMonths" type="text" class="@error('numberOfMonths') is-invalid @enderror">

    @error('numberOfMonths')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <label for="cost">Cost</label>

    <input id="cost" name="cost" type="text" class="@error('cost') is-invalid @enderror">

    @error('cost')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <label for="discount">Discount</label>

    <input id="discount" name="discount" type="text" class="@error('discount') is-invalid @enderror">

    @error('discount')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <input type="submit" value="Add Subscription Plan">
</form>
