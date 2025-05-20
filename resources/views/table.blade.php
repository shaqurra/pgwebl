@extends('layout.template')

@section('content')
<div class="container">
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($points as $point)
        <tr>
            <td>{{ $point->id }}</td>
            <td>{{ $point->name }}</td>
            <td>{{ $point->description }}</td>
            <td><img src="{{ asset('storage/images/' . $point->image) }}" alt="" width="150" title="{{ $point->image }}"></td>
            <td>{{ $point->created_at }}</td>
            <td>{{ $point->updated_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
