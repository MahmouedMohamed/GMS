@extends('layouts.app')
@section('content')

<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Trainer Name</th>
        <th scope="col">Day</th>
        <th scope="col">From</th>
        <th scope="col">To</th>
      </tr>
    </thead>
    @foreach ($shifts as $shift )
    <tbody>
      <tr>
        <td>{{ $shift->trainer->name }}</td>
        <td>{{ $shift->day }}</td>
        <td>{{ $shift->from }}</td>
        <td>{{ $shift->to }}</td>
      </tr>
    </tbody>
    @endforeach
  </table>

@endsection
