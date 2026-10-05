@extends('user::layouts.master')
@section('title', 'Admin | Scholarship Applications')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Scholarship Applications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.index') }}">Scholarships</a></li>
                    <li class="breadcrumb-item active">Applications</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        {{-- Filter --}}
        <div class="card card-default">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.scholarship.application.index') }}" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">Status:</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.scholarship.application.index') }}" class="btn btn-default ml-2">Reset</a>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Applications</h3>
                    </div>
                    <div class="card-body">
                        @if(count($applications) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Scholarship</th>
                                    <th>Discount Amount</th>
                                    <th>Applied By</th>
                                    <th>Status</th>
                                    <th>Reviewed By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $app)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ userName('Student', $app->student_id) }}</td>
                                    <td>
                                        @if($app->intakeCourse && $app->intakeCourse->course)
                                            {{ $app->intakeCourse->course->course_name }}
                                        @else - @endif
                                    </td>
                                    <td>{{ $app->scholarship->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $releasedTotal = $app->disbursements->where('state', 'released')->sum('actual_amount');
                                            $scheduledCount = $app->disbursements->where('state', 'scheduled')->count();
                                            $withheldCount = $app->disbursements->where('state', 'withheld')->count();
                                        @endphp
                                        @if($releasedTotal > 0 || $scheduledCount > 0 || $withheldCount > 0)
                                            ${{ number_format($releasedTotal, 2) }}
                                            @if($scheduledCount > 0)<br><small class="text-info">+{{ $scheduledCount }} scheduled</small>@endif
                                            @if($withheldCount > 0)<br><small class="text-warning">{{ $withheldCount }} withheld</small>@endif
                                        @elseif($app->feeDiscount)
                                            ${{ number_format($app->feeDiscount->discount_amount, 2) }}
                                        @else -
                                        @endif
                                    </td>
                                    <td>{{ userName('User', $app->applied_by) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $app->status_class }}">
                                            {{ $app->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($app->reviewed_by)
                                            {{ userName('User', $app->reviewed_by) }}
                                            <br><small>{{ dateFormat($app->reviewed_at) }}</small>
                                        @else - @endif
                                    </td>
                                    <td>
                                        @if($app->status == 0 && checkRole('scholarship_application', 'edit'))
                                        <a href="{{ route('admin.scholarship.application.review', $app->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-eye"></i> Review
                                        </a>
                                        @endif
                                        @if($app->status != 0 && checkRole('scholarship_application', 'view'))
                                        <a href="{{ route('admin.scholarship.application.show', $app->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-stream"></i> Schedule
                                        </a>
                                        @endif
                                        @if($app->status == 1 && checkRole('scholarship_application', 'edit'))
                                        <form action="{{ route('admin.scholarship.application.revoke', $app->id) }}" method="post" class="d-inline"
                                              onsubmit="return confirm('Revoke this scholarship? Scheduled disbursements will be cancelled and applied discounts deactivated. Discounts already consumed by paid installments are NOT refunded.')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-dark btn-sm"><i class="fas fa-ban"></i> Revoke</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Scholarship</th>
                                    <th>Discount Amount</th>
                                    <th>Applied By</th>
                                    <th>Status</th>
                                    <th>Reviewed By</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Applications Found</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
