@extends('user::layouts.master')
@section('title', 'Admin | Themes Settings')

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
                <h1>Themes Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Themes Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Choose one theme</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="theme_select_section">

                            <form>
                                <div class="form-check form-check-inline">

                                    <div class="radiobtn">

                                        <label for="huey">
                                            <div class="select_theme text-center">
                                                <a href="{{ route('admin.setting.updateTheme', 'light') }}">
                                                    <img src="{{ asset('files/Theme1.png') }}" alt="Image 1" class="highlight">
                                                    <div class="title"> <input type="radio" id="huey" name="drone" value="huey"  @if (Auth::guard('user')->user()->theme == 'light' || Auth::guard('user')->user()->theme == 'dark') checked @endif />Theme 1</div>
                                                </a>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <div class="radiobtn">
                                        <label for="dewey">
                                            <div class="select_theme text-center">
                                                <a href="{{ route('admin.setting.updateTheme', 'theme2') }}">
                                                    <img src="{{ asset('files/Theme2.png') }}" alt="Image 1">
                                                    <div class="title"><input type="radio" id="dewey" name="drone" value="dewey" @if (Auth::guard('user')->user()->theme == 'theme2') checked @endif/>Theme 2</div>
                                                </a>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <div class="radiobtn">
                                        <label for="louie">
                                            <div class="select_theme text-center">
                                                <a href="{{ route('admin.setting.updateTheme', 'theme3') }}">
                                                    <img src="{{ asset('files/Theme3.png') }}" alt="Image 1">
                                                    <div class="title"><input type="radio" id="louie" name="drone" value="louie" @if (Auth::guard('user')->user()->theme == 'theme3') checked @endif/>Theme 3</div>
                                                </a>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection