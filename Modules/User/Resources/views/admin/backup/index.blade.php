@extends('user::layouts.master')
@section('title', 'Admin | Database Backup')

@section('content')
<style>
    .backup-action-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        box-shadow: 0 1px 6px rgba(0,0,0,.06);
        overflow: hidden;
        height: 100%;
    }
    .backup-action-card .card-icon-header {
        padding: 28px 28px 20px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }
    .backup-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 22px;
    }
    .backup-icon-wrap.green  { background: #d4edda; color: #28a745; }
    .backup-icon-wrap.blue   { background: #d1ecf1; color: #17a2b8; }
    .backup-action-card .card-title-block h5 {
        font-size: 16px;
        font-weight: 700;
        color: #343a40;
        margin: 0 0 4px;
    }
    .backup-action-card .card-title-block p {
        font-size: 13px;
        color: #6c757d;
        margin: 0;
        line-height: 1.5;
    }
    .backup-action-card .card-divider {
        border: none;
        border-top: 1px solid #f0f0f0;
        margin: 0;
    }
    .backup-action-card .card-action-body {
        padding: 20px 28px 24px;
    }
    .warning-banner {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 13px;
        color: #856404;
        margin-bottom: 18px;
        line-height: 1.5;
    }
    .warning-banner i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
    .info-banner {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #e8f4fd;
        border: 1px solid #bee5eb;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 13px;
        color: #0c5460;
        margin-bottom: 18px;
        line-height: 1.5;
    }
    .info-banner i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
    .file-drop-zone {
        border: 2px dashed #ced4da;
        border-radius: 8px;
        padding: 24px 16px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        position: relative;
        margin-bottom: 16px;
        background: #fafafa;
    }
    .file-drop-zone:hover, .file-drop-zone.dragover {
        border-color: #17a2b8;
        background: #f0fafc;
    }
    .file-drop-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer;
    }
    .file-drop-zone .drop-icon { font-size: 28px; color: #adb5bd; display: block; margin-bottom: 8px; }
    .file-drop-zone .drop-text { font-size: 14px; color: #495057; }
    .file-drop-zone .drop-text span { color: #17a2b8; font-weight: 600; }
    .file-drop-zone .drop-hint { font-size: 12px; color: #adb5bd; margin-top: 4px; }
    .selected-file-pill {
        display: none;
        align-items: center;
        gap: 8px;
        background: #e8f4fd;
        border: 1px solid #bee5eb;
        border-radius: 6px;
        padding: 8px 14px;
        font-size: 13px;
        color: #0c5460;
        margin-bottom: 16px;
    }
    .selected-file-pill.show { display: flex; }
    .selected-file-pill .file-icon { font-size: 16px; color: #17a2b8; }
    .selected-file-pill .file-name { flex: 1; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .selected-file-pill .remove-file { cursor: pointer; color: #6c757d; font-size: 14px; }
    .selected-file-pill .remove-file:hover { color: #dc3545; }
    .btn-backup-action {
        font-size: 14px;
        font-weight: 600;
        padding: 10px 24px;
        border-radius: 7px;
        letter-spacing: 0.3px;
    }
    .page-summary-bar {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 16px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .page-summary-bar .summary-icon {
        width: 40px; height: 40px;
        background: #f0f4ff;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #4361ee; font-size: 18px; flex-shrink: 0;
    }
    .page-summary-bar .summary-text p { margin: 0; font-size: 13px; color: #6c757d; }
    .page-summary-bar .summary-text strong { font-size: 14px; color: #343a40; }
</style>

@if ($text = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i><strong>{{ $text }}</strong>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i><strong>{{ $text }}</strong>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<section class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:22px;font-weight:700;">Database Backup</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Database Backup</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        {{-- Info bar --}}
        <div class="page-summary-bar">
            <div class="summary-icon"><i class="fas fa-database"></i></div>
            <div class="summary-text">
                <strong>Database Backup &amp; Restore</strong>
                <p>Download a full SQL snapshot of the database or restore from a previous backup file.</p>
            </div>
        </div>

        <div class="row">

            {{-- Download Card --}}
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="backup-action-card">
                    <div class="card-icon-header">
                        <div class="backup-icon-wrap green">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="card-title-block">
                            <h5>Download Backup</h5>
                            <p>Generate and download a full SQL dump of the current database. The file will be named with the current timestamp.</p>
                        </div>
                    </div>
                    <hr class="card-divider">
                    <div class="card-action-body">
                        <div class="info-banner">
                            <i class="fas fa-info-circle"></i>
                            <span>The backup includes all tables and data. Store the file in a secure, off-site location.</span>
                        </div>
                        @if(checkRole('database_backup', 'add'))
                        <a href="{{ route('admin.backup.download') }}" class="btn btn-success btn-backup-action">
                            <i class="fas fa-download mr-2"></i>Download Database
                        </a>
                        @else
                        <button class="btn btn-success btn-backup-action" disabled title="You do not have permission">
                            <i class="fas fa-download mr-2"></i>Download Database
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Restore Card --}}
            <div class="col-lg-7 col-md-6 mb-4">
                <div class="backup-action-card">
                    <div class="card-icon-header">
                        <div class="backup-icon-wrap blue">
                            <i class="fas fa-upload"></i>
                        </div>
                        <div class="card-title-block">
                            <h5>Restore from Backup</h5>
                            <p>Upload a <code>.sql</code> backup file to restore the database. This will overwrite all existing data.</p>
                        </div>
                    </div>
                    <hr class="card-divider">
                    <div class="card-action-body">

                        <div class="warning-banner">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span><strong>Destructive action.</strong> Restoring will overwrite all current data with the contents of the backup file. This cannot be undone. Download a fresh backup before proceeding.</span>
                        </div>

                        <form id="restore" action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="file-drop-zone" id="drop-zone">
                                <input type="file" name="file" id="file" accept=".sql">
                                <i class="fas fa-file-code drop-icon"></i>
                                <div class="drop-text"><span>Browse</span> or drag &amp; drop your SQL file here</div>
                                <div class="drop-hint">Only .sql files are accepted</div>
                            </div>

                            <div class="selected-file-pill" id="file-pill">
                                <i class="fas fa-file-alt file-icon"></i>
                                <span class="file-name" id="file-name-text"></span>
                                <i class="fas fa-times remove-file" id="remove-file" title="Remove file"></i>
                            </div>

                            <div id="file-type-error" class="text-danger mb-3" style="font-size:13px;display:none;">
                                <i class="fas fa-exclamation-circle mr-1"></i>Only <strong>.sql</strong> files are accepted.
                            </div>

                            <div class="d-flex align-items-center gap-2" style="gap:10px;">
                                <button type="submit" class="btn btn-info btn-backup-action" id="restore-btn" style="color:#fff;" disabled>
                                    <i class="fas fa-undo-alt mr-2"></i>Restore Database
                                </button>
                                <span style="font-size:12px;color:#6c757d;">Select a file to enable restore</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    var fileInput   = document.getElementById('file');
    var dropZone    = document.getElementById('drop-zone');
    var filePill    = document.getElementById('file-pill');
    var fileNameTxt = document.getElementById('file-name-text');
    var removeBtn   = document.getElementById('remove-file');
    var restoreBtn  = document.getElementById('restore-btn');
    var typeError   = document.getElementById('file-type-error');

    function handleFile(file) {
        if (!file) return;
        var ext = file.name.split('.').pop().toLowerCase();
        if (ext !== 'sql') {
            typeError.style.display = 'block';
            filePill.classList.remove('show');
            restoreBtn.disabled = true;
            fileInput.value = '';
            return;
        }
        typeError.style.display = 'none';
        fileNameTxt.textContent = file.name;
        filePill.classList.add('show');
        dropZone.style.display = 'none';
        restoreBtn.disabled = false;
    }

    fileInput.addEventListener('change', function () {
        handleFile(this.files[0] || null);
    });

    removeBtn.addEventListener('click', function () {
        fileInput.value = '';
        filePill.classList.remove('show');
        dropZone.style.display = '';
        restoreBtn.disabled = true;
        typeError.style.display = 'none';
    });

    // Drag-and-drop visual feedback
    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', function () {
        dropZone.classList.remove('dragover');
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        var dt = e.dataTransfer;
        if (dt.files.length) {
            // Transfer dropped file to the hidden input
            var blob = dt.files[0];
            var container = new DataTransfer();
            container.items.add(blob);
            fileInput.files = container.files;
            handleFile(blob);
        }
    });

    // Confirm before restoring
    $('#restore').on('submit', function (e) {
        if (!confirm('Are you sure you want to restore the database? All current data will be overwritten and this cannot be undone.')) {
            e.preventDefault();
        }
    });

    $('#restore').validate({
        rules: { file: { required: true } },
        messages: { file: 'Please select a .sql backup file' },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            error.insertAfter(element.closest('.file-drop-zone'));
        },
        highlight: function (element) { $(element).addClass('is-invalid'); },
        unhighlight: function (element) { $(element).removeClass('is-invalid'); }
    });
</script>
@endsection
