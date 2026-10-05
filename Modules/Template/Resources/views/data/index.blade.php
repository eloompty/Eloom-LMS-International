@extends('user::layouts.master')
@section('title', 'Admin | Template Data')

@section('content')
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
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
                <h1>{{ $template->name }} Data</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('admin.template.index') }}">Templates</a></li>
                    <li class="breadcrumb-item active">Template Data</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="updatetemplatedata" action="{{ route('admin.template.data.update', $template->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit {{ $template->name }} Data</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @foreach ($template->templateData as $index => $value)
                            <div class="form-group">
                                <label>{{ ucwords(str_replace("_", " ", $value->key )) }}</label>
                                @if ($value->key == 'content' || $value->key == 'footer')
                                <textarea name="{{ $value->key }}" class="form-control" id="{{ $value->key }}" placeholder="{{ ucwords(str_replace("_", " ", $value->key )) }}"">{{ $value->value }}</textarea>
                                <script>
                                    CKEDITOR.replace('{{ $value->key }}');
                                </script>
                                @else
                                <input @if($value->key == 'date') type='date' @elseif ($value->key == 'logo' || $value->key == 'regards_signature') type='file' @else type=" text" @endif name="{{ $value->key }}" class="form-control" id="{{ $value->key }}" placeholder="{{ ucwords(str_replace("_", " ", $value->key )) }}" value="{{ $value->value }}">
                                @endif
                            </div>
                            @if ($value->key == 'logo' || $value->key == 'regards_signature')
                            <div class="form-group">
                                <img src="{{ asset($value->value) }}" id="box-image{{ $value->id }}" alt="" style="width: 80px; border: #ebebeb 1px solid;">
                            </div>
                            @endif
                            @endforeach
                        </div>
                        <!-- /.card-body -->
                         <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection