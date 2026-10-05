@extends('user::layouts.master')
@section('title', 'Admin | Offer Letter Templates')

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
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Offer Letter Templates</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Offer Letter Templates</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of Offer Letter Templates</h3>
                        @if (checkRole('offer_template', 'add') == true)
                        <div class="col-md-12 text-right"><a href="{{ route('admin.offer.template.create') }}" class="btn btn-success">Add Template</a></div>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($templates) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $template->name }}</td>
                                    <td>
                                        @if ($template->status == 1) <span class="status active">Active</span>
                                        @else <span class="status inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td data-sort='{{ convertDate($template->created_at) }}'>{{ dateFormat($template->created_at) }}</td>
                                    <td>
                                        @if (checkRole('offer_template', 'edit') == true)
                                        <a href="{{ route('admin.offer.template.edit', $template->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @endif
                                        @if (checkRole('offer_template', 'delete') == true)
                                        <a href="{{ route('admin.offer.template.delete', $template->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this template?')"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <h3>No Data Found</h3>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
