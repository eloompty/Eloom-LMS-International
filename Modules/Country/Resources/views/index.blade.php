@extends('user::layouts.master')
@section('title', 'Admin | Countries')

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
                <h1>Countries</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Countries</li>
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
                        <h3 class="card-title">List of Countries</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($countries) > 0)
                        <form action="{{ route('admin.country.update.bulk') }}" method="POST">
                            @csrf
                            <table id="example1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        @if (checkRole('country', 'edit') == true)
                                        <th>Select</th>
                                        @endif
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Nationality</th>
                                        <th>Currency</th>
                                        <th>Currency Code</th>
                                        <th>Flag</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($countries as $index => $value)
                                    <tr>
                                        @if (checkRole('country', 'edit') == true)
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo{{ $value->id }}" name="id[]" value="{{ $value->id }}">
                                                <label for="checkboxInfo{{ $value->id }}" class="check delete"></label>
                                            </div>
                                        </td>
                                        @endif
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->code }}</td>
                                        <td>{{ $value->nationality }}</td>
                                        <td>{{ $value->currency }}</td>
                                        <td>{{ $value->currency_code }}</td>
                                        <td><a href="{{ asset($value->flag) }}" target="_blank"><img src="{{ asset($value->flag) }}" alt="" width="24" /></a></td>
                                        <td>
                                            @if ($value->status == 1) <span class="status active">Active</span>
                                            @else <span class="status inactive">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        @if (checkRole('country', 'edit') == true)
                                        <th>Edit</th>
                                        @endif
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Nationality</th>
                                        <th>Currency</th>
                                        <th>Currency Code</th>
                                        <th>Flag</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                            @if (checkRole('country', 'edit') == true)
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" disabled>-- Select Status --</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Update Selected</button>
                            @endif
                        </form>
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
