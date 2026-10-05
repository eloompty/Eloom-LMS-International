@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Grade Appeals')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Pending Grade Appeals</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($appeals->isEmpty())
        <p class="text-muted">No pending appeals.</p>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr><th>#</th><th>Student</th><th>Mark Type</th><th>Reason</th><th>Response</th></tr>
            </thead>
            <tbody>
                @foreach($appeals as $appeal)
                <tr>
                    <td>{{ $appeal->id }}</td>
                    <td>{{ optional($appeal->student)->first_name }} {{ optional($appeal->student)->last_name }}</td>
                    <td>{{ ucfirst($appeal->mark_type) }}</td>
                    <td>{{ $appeal->reason }}</td>
                    <td>
                        <form method="POST" action="{{ route('trainer.gradebook.appeal.respond', $appeal->id) }}">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="text" name="trainer_response" class="form-control"
                                       placeholder="Your response" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
