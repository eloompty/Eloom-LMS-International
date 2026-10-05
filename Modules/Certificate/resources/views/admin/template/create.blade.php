@extends('user::layouts.master')
@section('title', 'Admin | Create Certificate Template')

@section('header-script')
<style>
.builder-section-header { cursor:pointer; user-select:none; }
.builder-section-header:hover { background:#f8fafc; }
.merge-pill {
    display:inline-flex; align-items:center; padding:4px 10px; margin:2px;
    border-radius:6px; border:1px solid #dbeafe; background:#eff6ff;
    color:#1d4ed8; font-size:11px; font-weight:800; cursor:pointer;
    transition:background .15s, border-color .15s;
}
.merge-pill:hover { background:#dbeafe; border-color:#93c5fd; }
.builder-label {
    display:block; font-size:11px; font-weight:800; color:#374151;
    text-transform:uppercase; margin-bottom:5px;
}
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Certificate Template Builder</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.certificate.template.index') }}">Templates</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
             style="border-radius:8px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; font-size:13px; font-weight:700;">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
            <button type="button" class="close" data-dismiss="alert" style="color:#991b1b;"><span>&times;</span></button>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.certificate.template.store') }}" id="templateForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="layout" id="layoutJson">

            {{-- Meta row --}}
            <div class="dashboard-panel mb-4">
                <div class="card-body" style="padding:18px 20px;">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="builder-label">Template Name <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', 'Certificate of Completion') }}" required
                                   style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                        </div>
                        <div class="col-md-3">
                            <label class="builder-label">Type <span style="color:#dc2626;">*</span></label>
                            <select name="type" class="form-control" required
                                    style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                @foreach(['completion','diploma','degree','transcript','badge'] as $t)
                                <option value="{{ $t }}" {{ old('type','completion') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center mt-3 mt-md-0" style="gap:10px; padding-top:20px;">
                                <input type="checkbox" name="is_default" id="isDefault" value="1"
                                       style="width:16px; height:16px; accent-color:#2563eb; cursor:pointer;">
                                <label for="isDefault" class="mb-0" style="font-size:13px; font-weight:700; color:#374151; cursor:pointer;">
                                    Set as default for this type
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <button type="submit" class="btn btn-primary btn-block"
                                    style="font-weight:800; font-size:13px; border-radius:6px;">
                                <i class="fas fa-save mr-1"></i> Save Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- LEFT: Controls --}}
                <div class="col-lg-5">

                    {{-- Merge Fields --}}
                    <div class="dashboard-panel mb-3">
                        <div class="card-header">
                            <h3 class="card-title" style="font-size:13px;">
                                <i class="fas fa-tags"></i> Merge Fields
                            </h3>
                            <div class="card-tools">
                                <small style="color:#6b7280; font-size:11px; font-weight:700;">Click to insert into focused field</small>
                            </div>
                        </div>
                        <div class="card-body" style="padding:12px 16px;">
                            @foreach(['{{student_name}}','{{course_name}}','{{intake_name}}','{{issued_date}}','{{cert_id}}','{{verify_url}}'] as $field)
                            <span class="merge-pill merge-field-badge" data-field="{{ $field }}">{{ $field }}</span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Background & Border --}}
                    <div class="dashboard-panel mb-3">
                        <div class="card-header builder-section-header"
                             data-toggle="collapse" data-target="#sectionBg">
                            <h3 class="card-title" style="font-size:13px;">
                                <i class="fas fa-paint-brush"></i> Background & Border
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn-tool"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="collapse show" id="sectionBg">
                            <div class="card-body" style="padding:14px 16px;">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="builder-label">Background Color</label>
                                        <input type="color" id="f_background_color" class="form-control form-control-sm builder-field"
                                               value="#fffdf7" style="height:36px; padding:2px; border-radius:6px;">
                                    </div>
                                    <div class="col-6">
                                        <label class="builder-label">Border Color</label>
                                        <input type="color" id="f_border_color" class="form-control form-control-sm builder-field"
                                               value="#c9a96e" style="height:36px; padding:2px; border-radius:6px;">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <label class="builder-label">Border Style</label>
                                        <select id="f_border_style" class="form-control form-control-sm builder-field"
                                                style="border-radius:6px; border-color:#d1d5db;">
                                            <option value="solid">Solid</option>
                                            <option value="double" selected>Double</option>
                                            <option value="dashed">Dashed</option>
                                            <option value="groove">Groove</option>
                                            <option value="ridge">Ridge</option>
                                            <option value="none">None</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="builder-label">Border Width: <span id="v_border_width">8</span>px</label>
                                        <input type="range" id="f_border_width" class="builder-field w-100" min="0" max="20" value="8">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Logo & Subtitle --}}
                    <div class="dashboard-panel mb-3">
                        <div class="card-header builder-section-header"
                             data-toggle="collapse" data-target="#sectionLogo">
                            <h3 class="card-title" style="font-size:13px;">
                                <i class="fas fa-image"></i> Logo & Subtitle
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn-tool"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="collapse show" id="sectionLogo">
                            <div class="card-body" style="padding:14px 16px;">
                                <div class="d-flex align-items-center mb-3" style="gap:10px;">
                                    <input type="checkbox" id="f_show_logo" class="builder-field" checked
                                           style="width:16px; height:16px; accent-color:#2563eb; cursor:pointer;">
                                    <label for="f_show_logo" class="mb-0" style="font-size:13px; font-weight:700; color:#374151; cursor:pointer;">
                                        Show logo
                                    </label>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="builder-label">Logo image (optional)</label>
                                    <input type="file" name="logo" id="f_logo_file" accept="image/*"
                                           class="form-control form-control-sm"
                                           style="border-radius:6px; border-color:#d1d5db; font-size:12px;">
                                    <small style="display:block; margin-top:4px; color:#6b7280; font-size:11px; font-weight:700;">
                                        Leave empty to use the institution logo from Settings.
                                    </small>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="builder-label">Subtitle text (above title)</label>
                                    <input type="text" id="f_subtitle_text" class="form-control form-control-sm builder-field mergeable"
                                           value="This is to certify that"
                                           placeholder="e.g. This is to certify that"
                                           style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <label class="builder-label">Subtitle Size: <span id="v_subtitle_size">13</span>px</label>
                                        <input type="range" id="f_subtitle_size" class="builder-field w-100" min="9" max="24" value="13">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="dashboard-panel mb-3">
                        <div class="card-header builder-section-header"
                             data-toggle="collapse" data-target="#sectionTitle">
                            <h3 class="card-title" style="font-size:13px;">
                                <i class="fas fa-heading"></i> Title
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn-tool"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="collapse show" id="sectionTitle">
                            <div class="card-body" style="padding:14px 16px;">
                                <div class="form-group mb-3">
                                    <label class="builder-label">Title Text</label>
                                    <input type="text" id="f_title_text" class="form-control form-control-sm builder-field mergeable"
                                           value="Certificate of Completion"
                                           style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="builder-label">Title Color</label>
                                        <input type="color" id="f_title_color" class="form-control form-control-sm builder-field"
                                               value="#2c3e50" style="height:36px; padding:2px; border-radius:6px;">
                                    </div>
                                    <div class="col-6">
                                        <label class="builder-label">Title Size: <span id="v_title_size">34</span>px</label>
                                        <input type="range" id="f_title_size" class="builder-field w-100" min="16" max="56" value="34">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="dashboard-panel mb-3">
                        <div class="card-header builder-section-header"
                             data-toggle="collapse" data-target="#sectionBody">
                            <h3 class="card-title" style="font-size:13px;">
                                <i class="fas fa-align-center"></i> Body Text
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn-tool"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="collapse show" id="sectionBody">
                            <div class="card-body" style="padding:14px 16px;">
                                <div class="form-group mb-3">
                                    <label class="builder-label">Body Content</label>
                                    <textarea id="f_body_text" class="form-control form-control-sm builder-field mergeable"
                                              rows="5"
                                              style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;">@{{student_name}}

has successfully completed

@{{course_name}}
@{{intake_name}}

on @{{issued_date}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="builder-label">Body Color</label>
                                        <input type="color" id="f_body_color" class="form-control form-control-sm builder-field"
                                               value="#222222" style="height:36px; padding:2px; border-radius:6px;">
                                    </div>
                                    <div class="col-6">
                                        <label class="builder-label">Body Size: <span id="v_body_size">18</span>px</label>
                                        <input type="range" id="f_body_size" class="builder-field w-100" min="10" max="36" value="18">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Signature & Footer --}}
                    <div class="dashboard-panel mb-3">
                        <div class="card-header builder-section-header"
                             data-toggle="collapse" data-target="#sectionFooter">
                            <h3 class="card-title" style="font-size:13px;">
                                <i class="fas fa-signature"></i> Signature & Footer
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn-tool"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="collapse show" id="sectionFooter">
                            <div class="card-body" style="padding:14px 16px;">
                                <div class="d-flex align-items-center mb-3" style="gap:10px;">
                                    <input type="checkbox" id="f_show_signature" class="builder-field" checked
                                           style="width:16px; height:16px; accent-color:#2563eb; cursor:pointer;">
                                    <label for="f_show_signature" class="mb-0" style="font-size:13px; font-weight:700; color:#374151; cursor:pointer;">
                                        Show signature line
                                    </label>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="builder-label">Signature Image (optional)</label>
                                    <input type="file" name="signature" id="f_signature_file" accept="image/*"
                                           class="form-control form-control-sm"
                                           style="border-radius:6px; border-color:#d1d5db; font-size:12px;">
                                    <small style="display:block; margin-top:4px; color:#6b7280; font-size:11px; font-weight:700;">
                                        Shown above the signature line.
                                    </small>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="builder-label">Signature Label</label>
                                    <input type="text" id="f_signature_label" class="form-control form-control-sm builder-field"
                                           value="Academic Director"
                                           style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="builder-label">Footer Text</label>
                                    <textarea id="f_footer_text" class="form-control form-control-sm builder-field mergeable"
                                              rows="2"
                                              style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;">Certificate ID: @{{cert_id}}   |   Verify at: @{{verify_url}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="builder-label">Footer Color</label>
                                        <input type="color" id="f_footer_color" class="form-control form-control-sm builder-field"
                                               value="#888888" style="height:36px; padding:2px; border-radius:6px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <a href="{{ route('admin.certificate.template.index') }}" class="panel-action">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary"
                                style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 22px;">
                            <i class="fas fa-save mr-1"></i> Save Template
                        </button>
                    </div>

                </div>{{-- /left --}}

                {{-- RIGHT: Live Preview --}}
                <div class="col-lg-7">
                    <div class="dashboard-panel" style="position:sticky; top:70px;">
                        <div class="card-header">
                            <h3 class="card-title" style="font-size:13px;">
                                <i class="fas fa-eye"></i> Live Preview
                            </h3>
                            <div class="card-tools">
                                <small style="color:#6b7280; font-size:11px; font-weight:700;">Updates as you edit</small>
                            </div>
                        </div>
                        <div class="card-body" style="padding:8px; background:#f1f5f9; min-height:380px;">
                            {{-- A4 landscape at ~62% scale --}}
                            <div style="transform-origin:top left; transform:scale(0.62); width:calc(100%/0.62); margin-bottom:calc(-38% + 20px);">
                                <div id="certPreview" style="width:1122px; height:794px; font-family:Arial,sans-serif; padding:28px; box-sizing:border-box; background:#fffdf7;">
                                    <div id="certInner" style="border:8px double #c9a96e; padding:44px 64px; text-align:center; height:100%; box-sizing:border-box; background:#fffdf7; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                        <div id="prev_logo" style="margin-bottom:10px;">
                                            <span style="font-size:11px; color:#aaa; border:1px dashed #ccc; padding:4px 12px;">[Institution Logo]</span>
                                        </div>
                                        <div id="prev_subtitle" style="font-size:13px; color:#555; letter-spacing:1px; text-transform:uppercase; margin-bottom:6px;">
                                            This is to certify that
                                        </div>
                                        <h1 id="prev_title" style="font-size:34px; color:#2c3e50; font-weight:bold; margin:8px 0 20px;">
                                            Certificate of Completion
                                        </h1>
                                        <div id="prev_body" style="font-size:18px; line-height:1.9; color:#222222; margin:16px 0;">
                                            &#123;&#123;student_name&#125;&#125;<br><br>has successfully completed<br><br>&#123;&#123;course_name&#125;&#125;<br>&#123;&#123;intake_name&#125;&#125;<br><br>on &#123;&#123;issued_date&#125;&#125;
                                        </div>
                                        <div id="prev_signature_img" style="margin-top:40px;"></div>
                                        <div id="prev_signature" style="border-top:1px solid #aaa; padding-top:6px; min-width:220px; font-size:12px; color:#555;">
                                            Academic Director
                                        </div>
                                        <div id="prev_footer" style="font-size:10px; color:#888; margin-top:24px; border-top:1px solid #eee; padding-top:8px; width:100%;">
                                            Certificate ID: &#123;&#123;cert_id&#125;&#125; | Verify at: &#123;&#123;verify_url&#125;&#125;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- /right --}}

            </div>{{-- /row --}}
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
(function () {
    var lastFocused = null;

    var INSTITUTION_LOGO = @json(getSettingValue('logo') ? asset(getSettingValue('logo')) : '');
    var TEMPLATE_LOGO = '';
    var pendingLogoSrc = '';

    var TEMPLATE_SIGNATURE = '';
    var pendingSignatureSrc = '';

    var logoFileEl = document.getElementById('f_logo_file');
    if (logoFileEl) {
        logoFileEl.addEventListener('change', function () {
            var file = this.files && this.files[0];
            pendingLogoSrc = file ? URL.createObjectURL(file) : '';
            updatePreview();
        });
    }

    var signatureFileEl = document.getElementById('f_signature_file');
    if (signatureFileEl) {
        signatureFileEl.addEventListener('change', function () {
            var file = this.files && this.files[0];
            pendingSignatureSrc = file ? URL.createObjectURL(file) : '';
            updatePreview();
        });
    }

    function renderSignature() {
        var imgBox = document.getElementById('prev_signature_img');
        var sigEl = document.getElementById('prev_signature');
        var show = gv('f_show_signature');
        if (sigEl) {
            sigEl.style.display = show ? 'block' : 'none';
            sigEl.textContent = gv('f_signature_label');
        }
        if (imgBox) {
            var src = pendingSignatureSrc || TEMPLATE_SIGNATURE;
            if (show && src) {
                imgBox.style.display = 'block';
                imgBox.innerHTML = '<img src="' + src + '" style="max-height:48px; max-width:220px;" alt="Signature">';
            } else {
                imgBox.style.display = 'none';
                imgBox.innerHTML = '';
            }
        }
    }

    function renderLogo() {
        var box = document.getElementById('prev_logo');
        if (!box) return;
        if (!gv('f_show_logo')) { box.style.display = 'none'; return; }
        box.style.display = 'block';
        var src = pendingLogoSrc || TEMPLATE_LOGO || INSTITUTION_LOGO;
        if (src) {
            box.innerHTML = '<img src="' + src + '" style="max-height:70px; max-width:240px;" alt="Logo">';
        } else {
            box.innerHTML = '<span style="font-size:11px; color:#aaa; border:1px dashed #ccc; padding:4px 12px;">[Institution Logo]</span>';
        }
    }

    document.querySelectorAll('.mergeable').forEach(function (el) {
        el.addEventListener('focus', function () { lastFocused = el; });
    });

    document.querySelectorAll('.merge-field-badge').forEach(function (badge) {
        badge.addEventListener('click', function () {
            if (!lastFocused) return;
            var field = this.dataset.field;
            var el = lastFocused;
            if (el.tagName === 'TEXTAREA' || el.tagName === 'INPUT') {
                var start = el.selectionStart, end = el.selectionEnd;
                el.value = el.value.substring(0, start) + field + el.value.substring(end);
                el.selectionStart = el.selectionEnd = start + field.length;
                el.dispatchEvent(new Event('input'));
                el.focus();
            }
        });
    });

    ['border_width','title_size','body_size','subtitle_size'].forEach(function (k) {
        var r = document.getElementById('f_' + k);
        var v = document.getElementById('v_' + k);
        if (r && v) r.addEventListener('input', function () { v.textContent = this.value; updatePreview(); });
    });

    document.querySelectorAll('.builder-field').forEach(function (el) {
        el.addEventListener('input', updatePreview);
        el.addEventListener('change', updatePreview);
    });

    function g(id) { return document.getElementById(id); }
    function gv(id) { var el = g(id); if (!el) return ''; return el.type === 'checkbox' ? el.checked : el.value; }

    function updatePreview() {
        var bg = gv('f_background_color'), borderColor = gv('f_border_color'),
            borderStyle = gv('f_border_style'), borderWidth = gv('f_border_width');

        g('certPreview').style.background = bg;
        var inner = g('certInner');
        inner.style.border = borderWidth + 'px ' + borderStyle + ' ' + borderColor;
        inner.style.background = bg;

        renderLogo();

        var subEl = g('prev_subtitle');
        subEl.textContent = gv('f_subtitle_text');
        subEl.style.display = gv('f_subtitle_text') ? 'block' : 'none';
        subEl.style.fontSize = gv('f_subtitle_size') + 'px';

        var titleEl = g('prev_title');
        titleEl.textContent = gv('f_title_text') || 'Certificate';
        titleEl.style.color = gv('f_title_color');
        titleEl.style.fontSize = gv('f_title_size') + 'px';

        var bodyEl = g('prev_body');
        bodyEl.innerHTML = escapeHtml(gv('f_body_text')).replace(/\n/g, '<br>');
        bodyEl.style.color = gv('f_body_color');
        bodyEl.style.fontSize = gv('f_body_size') + 'px';

        renderSignature();

        var footEl = g('prev_footer');
        footEl.textContent = gv('f_footer_text');
        footEl.style.color = gv('f_footer_color');
        footEl.style.display = gv('f_footer_text') ? 'block' : 'none';
    }

    function escapeHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    document.getElementById('templateForm').addEventListener('submit', function () {
        var layout = {
            background_color: gv('f_background_color'), border_color: gv('f_border_color'),
            border_style: gv('f_border_style'), border_width: parseInt(gv('f_border_width'), 10),
            show_logo: !!gv('f_show_logo'), subtitle_text: gv('f_subtitle_text'),
            subtitle_size: parseInt(gv('f_subtitle_size'), 10), title_text: gv('f_title_text'),
            title_color: gv('f_title_color'), title_size: parseInt(gv('f_title_size'), 10),
            body_text: gv('f_body_text'), body_color: gv('f_body_color'),
            body_size: parseInt(gv('f_body_size'), 10), text_color: '#333333', font_size: 14,
            show_signature: !!gv('f_show_signature'), signature_label: gv('f_signature_label'),
            footer_text: gv('f_footer_text'), footer_color: gv('f_footer_color'),
        };
        document.getElementById('layoutJson').value = JSON.stringify(layout);
    });

    updatePreview();
})();
</script>
@endsection
