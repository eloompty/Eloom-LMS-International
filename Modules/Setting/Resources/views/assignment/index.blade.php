@extends('user::layouts.master')
@section('title', 'Admin | Assignment Settings')

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
                <h1>Assignment Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Assignment Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <!-- form start -->
    <form id="updatesetting" action="{{ route('admin.setting.assignment.update') }}" method="POST">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Assignment Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="allow_all_submission_after_due_date">Allow All Submission After Due Date</label>
                                    <select name="allow_all_submission_after_due_date" class="form-control" id="allow_all_submission_after_due_date">
                                        <option @if(assignmentSetting('allow_all_submission_after_due_date')=='on' ) selected @endif value="on">On</option>
                                        <option @if(assignmentSetting('allow_all_submission_after_due_date')=='off' ) selected @endif value="off">Off</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </form>
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('updatesetting').addEventListener('submit', function(e) {
            e.preventDefault();

            if (window.confirm('This will make change in all intakes and students, are you sure you want to continue?')) {
                // User clicked "OK," so proceed with form submission
                this.submit();
            } else {
                // User clicked "Cancel," do nothing
            }
        });
    });
</script>
@endsection