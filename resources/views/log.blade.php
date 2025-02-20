@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>User Activity Log</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Action</th>
                <th>Date</th>
                <th>Undo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('undo.action', $log->id) }}" class="btn btn-danger btn-sm">
                            Undo
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
