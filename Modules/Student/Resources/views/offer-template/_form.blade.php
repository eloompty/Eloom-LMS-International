@section('content')
<style>
    .ob-canvas-outer { overflow-x: auto; padding-bottom: 24px; }
    .ob-canvas {
        background: #fff; border: 1px solid #ced4da; border-radius: 4px;
        box-shadow: 0 4px 24px rgba(0,0,0,.10); max-width: 760px; margin: 0 auto;
        padding: 16px; min-height: 300px;
    }
    .ob-canvas-empty { text-align: center; color: #adb5bd; padding: 60px 16px; font-size: 14px; }

    .ob-section {
        border: 1px solid #e3e6ea; border-radius: 6px; margin-bottom: 12px; background: #fff;
        transition: border-color .15s, box-shadow .15s;
    }
    .ob-section.ob-ghost { opacity: .4; background: #eef4ff; }
    .ob-section-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 7px 10px; background: #f7f9fb; border-bottom: 1px solid #eef0f2;
        border-radius: 5px 5px 0 0;
    }
    .ob-section-bar-left { display: flex; align-items: center; gap: 8px; min-width: 0; }
    .ob-drag-handle { color: #adb5bd; cursor: grab; font-size: 14px; }
    .ob-drag-handle:active { cursor: grabbing; }
    .ob-type-badge {
        font-size: 10px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
        padding: 2px 9px; border-radius: 20px; background: #e3f2fd; color: #1565c0; white-space: nowrap;
    }
    .ob-type-badge.dyn { background: #e8f5e9; color: #2e7d32; }
    .ob-type-badge.brk { background: #fff3e0; color: #ef6c00; }
    .ob-title-input {
        border: none; background: transparent; font-size: 13px; font-weight: 600; color: #495057;
        padding: 2px 4px; min-width: 80px; max-width: 320px;
    }
    .ob-title-input:focus { outline: 1px solid #80bdff; border-radius: 3px; background: #fff; }
    .ob-del { background: none; border: none; color: #adb5bd; font-size: 15px; cursor: pointer; padding: 2px 6px; border-radius: 4px; }
    .ob-del:hover { color: #dc3545; background: #fff1f0; }
    .ob-section-body { padding: 8px 10px; }

    .ob-dyn-placeholder {
        display: flex; align-items: center; gap: 10px; padding: 16px;
        background: #f6fbf7; border: 1px dashed #b7dfc0; border-radius: 5px; color: #2e7d32; font-size: 13px;
    }
    .ob-dyn-placeholder i { font-size: 20px; }
    .ob-break-placeholder {
        text-align: center; color: #ef6c00; font-size: 12px; font-weight: 600; letter-spacing: .5px;
        text-transform: uppercase; border-top: 2px dashed #ffcc80; padding-top: 8px;
    }

    .ob-add-btn {
        display: flex; align-items: center; gap: 8px; width: 100%; text-align: left;
        border: 1px solid #ced4da; background: #fff; border-radius: 5px; padding: 8px 10px;
        font-size: 13px; color: #495057; margin-bottom: 6px; cursor: pointer; transition: background .15s, border-color .15s;
    }
    .ob-add-btn:hover { background: #f1f5f9; border-color: #adb5bd; }
    .ob-add-btn i { width: 18px; text-align: center; color: #6c757d; }

    .token-grid { display: flex; flex-wrap: wrap; gap: 6px; }
    .token-chip {
        background: #e8f4fd; border: 1px solid #bee5eb; border-radius: 4px;
        padding: 3px 8px; font-size: 11px; font-family: monospace; color: #0c5460;
        cursor: pointer; transition: background .15s; white-space: nowrap; user-select: none;
    }
    .token-chip:hover { background: #bee5eb; }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:22px;font-weight:700;">{{ $pageTitle }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('admin.offer.template.index') }}">Offer Letter Templates</a></li>
                    <li class="breadcrumb-item active">{{ $crumb }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form id="ob-form" action="{{ $formAction }}" method="POST">
            @csrf
            <input type="hidden" name="layout" id="ob-layout-input" value="">

            <div class="row">
                {{-- LEFT PANEL --}}
                <div class="col-lg-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-cog mr-2"></i>Template Settings</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label style="font-size:13px;font-weight:600;">Template Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ $templateName }}" placeholder="e.g. International Offer Letter">
                            </div>
                            <div class="form-group mb-0">
                                <label style="font-size:13px;font-weight:600;">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control form-control-sm">
                                    <option value="" disabled {{ $templateStatus === '' ? 'selected' : '' }}>-- Select --</option>
                                    <option value="1" {{ $templateStatus === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $templateStatus === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-success">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-plus mr-2"></i>Add Section</h3></div>
                        <div class="card-body">
                            <button type="button" class="ob-add-btn" onclick="addSection('richtext')"><i class="fas fa-paragraph"></i> Rich Text</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('student_details')"><i class="fas fa-user"></i> Applicant / Offer Details</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('course_table')"><i class="fas fa-table"></i> Course Table</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('course_subjects')"><i class="fas fa-list"></i> Course Subject Table</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('fees_due')"><i class="fas fa-file-invoice-dollar"></i> Course Fees Due</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('payment_plan')"><i class="fas fa-money-check-alt"></i> Payment Plan</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('fees_overview')"><i class="fas fa-dollar-sign"></i> Fees Overview (detailed)</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('payment_schedule')"><i class="fas fa-calendar-alt"></i> Payment Schedule (detailed)</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('signature')"><i class="fas fa-signature"></i> Signature &amp; Footer</button>
                            <button type="button" class="ob-add-btn" onclick="addSection('page_break')"><i class="fas fa-grip-lines"></i> Page Break</button>
                        </div>
                    </div>

                    <div class="card card-outline card-info">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-code mr-2"></i>Merge Fields</h3></div>
                        <div class="card-body">
                            <p class="text-muted mb-2" style="font-size:12px;">Click a field to copy, then paste it into a Rich Text section. It is auto-filled per student.</p>
                            <div class="token-grid">
                                @php
                                    $mergeFields = [
                                        'student_name','student_first_name','student_last_name','student_date_of_birth','student_gender','student_nationality','student_phone','student_email','passport_no',
                                        'offer_number','issue_date','expiry_date','condition_description','credit_description',
                                        'course_name','course_code','cricos_code','course_start_date','course_end_date','course_duration','course_weeks',
                                        'organisation_name','rto_no','organisation_email','organisation_phone','signed_by_name','signed_by_designation',
                                        'bank_account_name','bank_name','bank_bsb','bank_account_number',
                                        'study_mode','study_location','work_placement','hours_per_week','holiday_breaks','entry_requirements',
                                        'agent_name','agent_address'
                                    ];
                                @endphp
                                @foreach ($mergeFields as $token)
                                    @php $mf = '{{' . $token . '}}'; @endphp
                                    <span class="token-chip" onclick="copyToken('{{ $mf }}')">{{ $mf }}</span>
                                @endforeach
                            </div>
                            <div id="copy-toast" style="display:none;margin-top:8px;font-size:12px;color:#28a745;"><i class="fas fa-check-circle mr-1"></i>Copied!</div>
                        </div>
                    </div>

                    <div class="d-flex mb-4" style="gap:8px;">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-save mr-2"></i>{{ $submitLabel }}</button>
                        <a href="{{ route('admin.offer.template.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </div>

                {{-- RIGHT PANEL: Canvas --}}
                <div class="col-lg-8">
                    <div class="ob-canvas-outer">
                        <div class="ob-canvas">
                            <div id="ob-sections"></div>
                            <div class="ob-canvas-empty" id="ob-empty">
                                <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                                Use <strong>Add Section</strong> to start building your offer letter.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
var state   = { sections: @json($initialSections) };
var secCount = 0;
var sortable = null;

var META = {
    richtext:         { label: 'Rich Text',                  badge: '',    icon: 'fa-paragraph' },
    student_details:  { label: 'Applicant / Offer Details',  badge: 'dyn', icon: 'fa-user',     hint: 'Student name, passport, offer number, address &amp; DOB — auto-filled per student.' },
    course_table:     { label: 'Course Table',               badge: 'dyn', icon: 'fa-table',    hint: 'Selected intake courses (code, CRICOS, dates, duration) — auto-filled per student.' },
    course_subjects:  { label: 'Course Subject Table',       badge: 'dyn', icon: 'fa-list',     hint: 'Subjects of each selected intake course (code, name, dates, credits/hours), grouped per course — auto-filled per student.' },
    fees_due:         { label: 'Course Fees Due',            badge: 'dyn', icon: 'fa-file-invoice-dollar', hint: 'Enrolment fee, total tuition fee, additional fees &amp; total — auto-filled per student.' },
    payment_plan:     { label: 'Payment Plan',               badge: 'dyn', icon: 'fa-money-check-alt', hint: 'Fee instalments with amount &amp; due date — auto-filled per student.' },
    fees_overview:    { label: 'Fees Overview (detailed)',   badge: 'dyn', icon: 'fa-dollar-sign', hint: 'Course fees overview &amp; proforma invoice summary — auto-filled per student.' },
    payment_schedule: { label: 'Payment Schedule (detailed)',badge: 'dyn', icon: 'fa-calendar-alt', hint: 'First payment &amp; full installment schedule — auto-filled per student.' },
    signature:        { label: 'Signature & Footer',         badge: 'dyn', icon: 'fa-signature', hint: 'Signature image, signed-by name/designation &amp; company footer.' },
    page_break:       { label: 'Page Break',                 badge: 'brk', icon: 'fa-grip-lines' }
};

function uid() { return 'sec-' + (Date.now().toString(36)) + '-' + (++secCount); }

function addSection(type) {
    syncEditors();
    var s = { id: uid(), type: type, title: META[type].label };
    if (type === 'richtext') { s.content = ''; }
    state.sections.push(s);
    render();
    // focus the new editor / scroll into view
    var card = document.querySelector('[data-sid="' + s.id + '"]');
    if (card) card.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function deleteSection(id) {
    if (!confirm('Remove this section?')) return;
    syncEditors();
    state.sections = state.sections.filter(function (s) { return s.id !== id; });
    render();
}

function setTitle(id, value) {
    var s = state.sections.find(function (x) { return x.id === id; });
    if (s) s.title = value;
}

// Pull current CKEditor HTML into the data model before any re-render.
function syncEditors() {
    state.sections.forEach(function (s) {
        if (s.type === 'richtext' && CKEDITOR.instances['ck-' + s.id]) {
            s.content = CKEDITOR.instances['ck-' + s.id].getData();
        }
    });
}

function destroyEditors() {
    Object.keys(CKEDITOR.instances).forEach(function (k) {
        try { CKEDITOR.instances[k].destroy(true); } catch (e) {}
    });
}

function render() {
    destroyEditors();
    var wrap = document.getElementById('ob-sections');
    wrap.innerHTML = '';
    state.sections.forEach(function (s) { wrap.appendChild(makeCard(s)); });
    document.getElementById('ob-empty').style.display = state.sections.length ? 'none' : '';
    // init CKEditor for rich text sections
    state.sections.forEach(function (s) {
        if (s.type === 'richtext') {
            CKEDITOR.replace('ck-' + s.id, { height: 200 });
            CKEDITOR.instances['ck-' + s.id].setData(s.content || '');
        }
    });
    initSortable();
}

function makeCard(s) {
    var meta = META[s.type];
    var div = document.createElement('div');
    div.className = 'ob-section';
    div.dataset.sid = s.id;

    var badgeClass = 'ob-type-badge' + (meta.badge ? ' ' + meta.badge : '');
    var body = '';
    if (s.type === 'richtext') {
        body = '<textarea id="ck-' + s.id + '"></textarea>';
    } else if (s.type === 'page_break') {
        body = '<div class="ob-break-placeholder"><i class="fas fa-grip-lines mr-1"></i>New page starts here</div>';
    } else {
        body = '<div class="ob-dyn-placeholder"><i class="fas ' + meta.icon + '"></i><div><strong>' + meta.label + '</strong><br><span style="font-size:12px;">' + (meta.hint || '') + '</span></div></div>';
    }

    div.innerHTML =
        '<div class="ob-section-bar">' +
            '<div class="ob-section-bar-left">' +
                '<span class="ob-drag-handle" title="Drag to reorder"><i class="fas fa-grip-vertical"></i></span>' +
                '<span class="' + badgeClass + '"><i class="fas ' + meta.icon + ' mr-1"></i>' + meta.label + '</span>' +
                (s.type === 'page_break' ? '' :
                    '<input type="text" class="ob-title-input" value="' + escAttr(s.title || '') + '" placeholder="Section label" oninput="setTitle(\'' + s.id + '\', this.value)">') +
            '</div>' +
            '<button type="button" class="ob-del" title="Remove" onclick="deleteSection(\'' + s.id + '\')"><i class="fas fa-times"></i></button>' +
        '</div>' +
        '<div class="ob-section-body">' + body + '</div>';
    return div;
}

function escAttr(v) {
    return String(v).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function initSortable() {
    if (sortable) sortable.destroy();
    sortable = Sortable.create(document.getElementById('ob-sections'), {
        animation: 150,
        handle: '.ob-drag-handle',
        ghostClass: 'ob-ghost',
        onStart: function () { syncEditors(); },
        onEnd: function () {
            var ids = [];
            document.querySelectorAll('#ob-sections .ob-section').forEach(function (c) { ids.push(c.dataset.sid); });
            state.sections.sort(function (a, b) { return ids.indexOf(a.id) - ids.indexOf(b.id); });
            render();
        }
    });
}

function copyToken(token) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(token).then(showCopyToast);
    } else {
        var ta = document.createElement('textarea');
        ta.value = token; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select(); document.execCommand('copy');
        document.body.removeChild(ta); showCopyToast();
    }
}
function showCopyToast() {
    var t = document.getElementById('copy-toast');
    t.style.display = '';
    clearTimeout(t._tid);
    t._tid = setTimeout(function () { t.style.display = 'none'; }, 1800);
}

$('#ob-form').validate({
    ignore: [],
    rules:    { name: { required: true }, status: { required: true } },
    messages: { name: 'Please enter a template name', status: 'Please select a status' },
    errorElement: 'span',
    errorPlacement: function (error, element) { error.addClass('invalid-feedback'); element.closest('.form-group').append(error); },
    highlight:   function (el) { $(el).addClass('is-invalid'); },
    unhighlight: function (el) { $(el).removeClass('is-invalid'); },
    submitHandler: function (form) {
        syncEditors();
        if (!state.sections.length) { alert('Please add at least one section to the template.'); return false; }
        document.getElementById('ob-layout-input').value = JSON.stringify(state.sections);
        form.submit();
    }
});

render();
</script>
@endsection
