@extends('user::layouts.master')
@section('title', 'Admin | Scholarships')

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
                <h1>Scholarships</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Scholarships</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.scholarship.application.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> Applications
                </a>
                <a href="{{ route('admin.scholarship.discount.index') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar"></i> Revenue Impact
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of Scholarships</h3>
                        @if(checkRole('scholarship', 'add'))
                        <div class="col-md-12 text-right">
                            <a href="{{ route('admin.scholarship.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add Scholarship
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(count($scholarships) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Scope</th>
                                    <th>Disbursement</th>
                                    <th>Value</th>
                                    <th>Quota (Used / Total)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scholarships as $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->type_label }}</td>
                                    <td>
                                        <span class="badge badge-{{ $value->award_scope == 'full' ? 'success' : 'secondary' }}">{{ $value->award_scope_label }}</span>
                                    </td>
                                    <td>
                                        {{ $value->disbursement_label }}
                                        @if($value->requires_maintenance)
                                            <br><small class="text-warning"><i class="fas fa-shield-alt"></i> Keep ≥ {{ rtrim(rtrim($value->maintenance_min_percentage, '0'), '.') }}%</small>
                                        @endif
                                    </td>
                                    <td>{{ $value->value_display }}</td>
                                    <td>
                                        {{ $value->activeApplicationsCount() }}
                                        /
                                        {{ $value->quota ?? '∞' }}
                                    </td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(checkRole('scholarship', 'edit'))
                                        <a href="{{ route('admin.scholarship.edit', $value->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        @endif
                                        @if(checkRole('scholarship', 'delete') && $value->status != 2)
                                        <a href="{{ route('admin.scholarship.delete', $value->id) }}" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Delete this scholarship?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Scope</th>
                                    <th>Disbursement</th>
                                    <th>Value</th>
                                    <th>Quota</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Data Found</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
