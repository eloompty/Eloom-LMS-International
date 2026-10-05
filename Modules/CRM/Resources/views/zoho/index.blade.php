@extends('user::layouts.master')
@section('title', 'Admin | Zoho Leads')

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
                <h1>Zoho Leads</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Zoho Leads</li>
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
                        <h3 class="card-title">List of Zoho Leads</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.zoho.create') }}" class="btn btn-success">Add Zoho Lead</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($leads) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Converted</th>
                                    <th>Lead Status</th>
                                    <th>Approved State</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leads as $index => $lead)
                                <tr>
                                    <td>{{ $lead['id'] }}</td>
                                    <td>{{ $lead['Company'] }}</td>
                                    <td>{{ $lead['Last_Name'] }}</td>
                                    <td>{{ $lead['First_Name'] }}</td>
                                    <td>{{ $lead['Email'] }}</td>
                                    <td>{{ $lead['Phone'] }}</td>
                                    <td>@if ($lead['$converted'] == 1) Yes @else No @endif</td>
                                    <td>{{ $lead['Lead_Status'] }}</td>
                                    <td>{{ ucfirst($lead['$approval_state']) }}</td>
                                    <td>
                                        <!-- <a href="{{ route('admin.zoho.edit', $lead['id']) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a> -->
                                        <a href="{{ route('admin.zoho.delete', $lead['id']) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Converted</th>
                                    <th>Lead Status</th>
                                    <th>Approved State</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
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