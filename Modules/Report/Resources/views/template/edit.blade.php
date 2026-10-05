@extends('user::layouts.master')
@section('title', 'Admin | Template Designer')

@section('content')
<style>
    /* ===== A4 Canvas ===== */
    .a4-canvas-outer { overflow-x: auto; padding-bottom: 24px; }
    .a4-canvas {
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-shadow: 0 4px 24px rgba(0,0,0,.13);
        max-width: 640px;
        margin: 0 auto;
    }

    /* ===== Zone ===== */
    .canvas-zone { border-bottom: 2px dashed #dee2e6; }
    .canvas-zone:last-child { border-bottom: none; }
    .zone-label-bar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 6px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    .zone-badge {
        font-size: 10px; font-weight: 700; letter-spacing: .8px;
        text-transform: uppercase; padding: 2px 10px; border-radius: 20px;
    }
    .zone-badge-header { background: #e3f2fd; color: #1565c0; }
    .zone-badge-footer { background: #e8f5e9; color: #2e7d32; }
    .zone-add-btns { display: flex; gap: 4px; flex-wrap: wrap; }
    .btn-add-el {
        border: 1px solid #ced4da; background: #fff; border-radius: 4px;
        padding: 3px 9px; font-size: 11px; color: #495057;
        cursor: pointer; transition: background .15s, border-color .15s;
    }
    .btn-add-el:hover { background: #e9ecef; border-color: #adb5bd; }
    .zone-elements { padding: 8px; min-height: 48px; }
    .zone-empty-hint {
        text-align: center; padding: 10px 16px;
        font-size: 12px; color: #adb5bd;
    }

    /* ===== Element Card ===== */
    .canvas-element {
        border: 1px solid #e9ecef; border-radius: 6px;
        margin-bottom: 8px; background: #fff;
        transition: border-color .15s, box-shadow .15s; cursor: pointer;
    }
    .canvas-element:last-child { margin-bottom: 0; }
    .canvas-element:hover { border-color: #80bdff; box-shadow: 0 0 0 2px rgba(0,123,255,.07); }
    .canvas-element.is-selected { border-color: #007bff; box-shadow: 0 0 0 3px rgba(0,123,255,.15); }
    .canvas-element.el-ghost { opacity: .4; background: #f0f4ff; }
    .el-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 5px 8px; background: #fafafa;
        border-bottom: 1px solid #f0f0f0; border-radius: 5px 5px 0 0;
    }
    .el-toolbar-left { display: flex; align-items: center; gap: 6px; }
    .el-drag-handle {
        color: #adb5bd; cursor: grab; font-size: 13px; padding: 0 2px; line-height: 1;
    }
    .el-drag-handle:active { cursor: grabbing; }
    .el-type-label {
        font-size: 11px; font-weight: 700; color: #6c757d;
        text-transform: uppercase; letter-spacing: .5px;
    }
    .el-delete-btn {
        background: none; border: none; color: #adb5bd; font-size: 14px;
        cursor: pointer; padding: 2px 4px; border-radius: 4px; line-height: 1;
        transition: color .15s, background .15s;
    }
    .el-delete-btn:hover { color: #dc3545; background: #fff1f0; }
    .el-preview { padding: 8px 10px; min-height: 24px; }
    .el-logo-placeholder { color: #adb5bd; font-size: 12px; font-style: italic; }

    /* ===== Data area (locked) ===== */
    .canvas-data-area {
        background: #f8f9fa; padding: 32px 16px; text-align: center;
        min-height: 90px; display: flex; align-items: center; justify-content: center;
    }

    /* ===== Token chips ===== */
    .token-grid { display: flex; flex-wrap: wrap; gap: 6px; }
    .token-chip {
        background: #e8f4fd; border: 1px solid #bee5eb; border-radius: 4px;
        padding: 3px 9px; font-size: 11px; font-family: monospace; color: #0c5460;
        cursor: pointer; transition: background .15s; white-space: nowrap;
        user-select: none;
    }
    .token-chip:hover { background: #bee5eb; }

    /* ===== Align buttons ===== */
    .align-btn.active { background: #007bff !important; color: #fff !important; border-color: #007bff !important; }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:22px;font-weight:700;">Template Designer</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.report.template.index') }}">Templates</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form id="designer-form" action="{{ route('admin.report.template.update', $template->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="layout" id="layout-input" value="">

            <div class="row">

                {{-- LEFT PANEL --}}
                <div class="col-lg-4">

                    {{-- Template Settings --}}
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cog mr-2"></i>Template Settings</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label style="font-size:13px;font-weight:600;">Template Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ $template->name }}" required>
                            </div>
                            <div class="form-group">
                                <label style="font-size:13px;font-weight:600;">Logo</label>
                                <div class="input-group input-group-sm">
                                    <div class="custom-file">
                                        <input type="file" name="logo" class="custom-file-input" id="logo-input" accept="image/*">
                                        <label class="custom-file-label" for="logo-input" style="font-size:12px;">Replace logo…</label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <img id="logo-preview" src="{{ asset($template->logo) }}" alt=""
                                         style="max-height:56px;max-width:180px;border:1px solid #dee2e6;padding:4px;border-radius:4px;">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label style="font-size:13px;font-weight:600;">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control form-control-sm" required>
                                    <option value="1" @if($template->status == 1) selected @endif>Active</option>
                                    <option value="0" @if($template->status == 0) selected @endif>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Placeholder Tokens --}}
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-code mr-2"></i>Placeholder Tokens</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-2" style="font-size:12px;">Click a token to copy it, then paste it into a Text element.</p>
                            <div class="token-grid">
                                <span class="token-chip" onclick="copyToken('&#123;&#123;school_name&#125;&#125;')">&#123;&#123;school_name&#125;&#125;</span>
                                <span class="token-chip" onclick="copyToken('&#123;&#123;report_date&#125;&#125;')">&#123;&#123;report_date&#125;&#125;</span>
                                <span class="token-chip" onclick="copyToken('&#123;&#123;generated_by&#125;&#125;')">&#123;&#123;generated_by&#125;&#125;</span>
                                <span class="token-chip" onclick="copyToken('&#123;&#123;report_title&#125;&#125;')">&#123;&#123;report_title&#125;&#125;</span>
                            </div>
                            <div id="copy-toast" style="display:none;margin-top:8px;font-size:12px;color:#28a745;">
                                <i class="fas fa-check-circle mr-1"></i>Copied to clipboard!
                            </div>
                        </div>
                    </div>

                    {{-- Element Properties --}}
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-sliders-h mr-2"></i>Element Properties</h3>
                        </div>
                        <div class="card-body">
                            <div id="props-empty" class="text-center py-2">
                                <i class="fas fa-mouse-pointer fa-2x text-muted mb-2 d-block"></i>
                                <p class="text-muted mb-0" style="font-size:12px;">Click an element on the canvas to edit its properties</p>
                            </div>

                            {{-- Logo props --}}
                            <div id="props-logo" style="display:none;">
                                <div class="form-group">
                                    <label style="font-size:12px;font-weight:600;">Alignment</label>
                                    <div class="btn-group btn-group-sm w-100" role="group">
                                        <button type="button" class="btn btn-outline-secondary align-btn" data-align="left" onclick="setPropAlign('left')"><i class="fas fa-align-left"></i></button>
                                        <button type="button" class="btn btn-outline-secondary align-btn" data-align="center" onclick="setPropAlign('center')"><i class="fas fa-align-center"></i></button>
                                        <button type="button" class="btn btn-outline-secondary align-btn" data-align="right" onclick="setPropAlign('right')"><i class="fas fa-align-right"></i></button>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;font-weight:600;">Height (px)</label>
                                    <input type="number" id="prop-logo-height" class="form-control form-control-sm" min="20" max="150" value="50" oninput="onPropChange('height', parseInt(this.value)||50)">
                                </div>
                            </div>

                            {{-- Text props --}}
                            <div id="props-text" style="display:none;">
                                <div class="form-group">
                                    <label style="font-size:12px;font-weight:600;">Content</label>
                                    <textarea id="prop-text-content" class="form-control form-control-sm" rows="3" placeholder="Enter text or paste a token…" oninput="onPropChange('content', this.value)"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label style="font-size:12px;font-weight:600;">Font Size (px)</label>
                                            <input type="number" id="prop-text-fontsize" class="form-control form-control-sm" min="8" max="72" value="13" oninput="onPropChange('fontSize', parseInt(this.value)||13)">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label style="font-size:12px;font-weight:600;">Color</label>
                                            <input type="color" id="prop-text-color" class="form-control form-control-sm" value="#000000" oninput="onPropChange('color', this.value)" style="height:31px;padding:2px 4px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="prop-text-bold" onchange="onPropChange('fontWeight', this.checked ? 'bold' : 'normal')">
                                        <label class="custom-control-label" for="prop-text-bold" style="font-size:12px;">Bold</label>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;font-weight:600;">Alignment</label>
                                    <div class="btn-group btn-group-sm w-100" role="group">
                                        <button type="button" class="btn btn-outline-secondary align-btn" data-align="left" onclick="setPropAlign('left')"><i class="fas fa-align-left"></i></button>
                                        <button type="button" class="btn btn-outline-secondary align-btn" data-align="center" onclick="setPropAlign('center')"><i class="fas fa-align-center"></i></button>
                                        <button type="button" class="btn btn-outline-secondary align-btn" data-align="right" onclick="setPropAlign('right')"><i class="fas fa-align-right"></i></button>
                                    </div>
                                </div>
                            </div>

                            {{-- Divider props --}}
                            <div id="props-divider" style="display:none;">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-0">
                                            <label style="font-size:12px;font-weight:600;">Color</label>
                                            <input type="color" id="prop-divider-color" class="form-control form-control-sm" value="#cccccc" oninput="onPropChange('color', this.value)" style="height:31px;padding:2px 4px;">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-0">
                                            <label style="font-size:12px;font-weight:600;">Thickness (px)</label>
                                            <input type="number" id="prop-divider-thickness" class="form-control form-control-sm" min="1" max="10" value="1" oninput="onPropChange('thickness', parseInt(this.value)||1)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Spacer props --}}
                            <div id="props-spacer" style="display:none;">
                                <div class="form-group mb-0">
                                    <label style="font-size:12px;font-weight:600;">Height (px)</label>
                                    <input type="number" id="prop-spacer-height" class="form-control form-control-sm" min="4" max="120" value="16" oninput="onPropChange('height', parseInt(this.value)||16)">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex mb-4" style="gap:8px;">
                        <button type="submit" class="btn btn-warning flex-fill" style="color:#fff;">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                        <a href="{{ route('admin.report.template.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                </div>{{-- /col-lg-4 --}}

                {{-- RIGHT PANEL: A4 Canvas --}}
                <div class="col-lg-8">
                    <div class="a4-canvas-outer">
                        <div class="a4-canvas">

                            {{-- Header Zone --}}
                            <div class="canvas-zone">
                                <div class="zone-label-bar">
                                    <span class="zone-badge zone-badge-header"><i class="fas fa-angle-up mr-1"></i>Header Zone</span>
                                    <div class="zone-add-btns">
                                        <button type="button" class="btn-add-el" onclick="addElement('header','logo')"><i class="fas fa-image mr-1"></i>Logo</button>
                                        <button type="button" class="btn-add-el" onclick="addElement('header','text')"><i class="fas fa-font mr-1"></i>Text</button>
                                        <button type="button" class="btn-add-el" onclick="addElement('header','divider')"><i class="fas fa-minus mr-1"></i>Divider</button>
                                        <button type="button" class="btn-add-el" onclick="addElement('header','spacer')"><i class="fas fa-arrows-alt-v mr-1"></i>Spacer</button>
                                    </div>
                                </div>
                                <div class="zone-elements" id="header-elements"></div>
                                <div class="zone-empty-hint" id="header-empty">
                                    <i class="fas fa-arrow-up mr-1"></i>Use the buttons above to add elements to the header
                                </div>
                            </div>

                            {{-- Data area --}}
                            <div class="canvas-data-area">
                                <span class="text-muted" style="font-size:13px;"><i class="fas fa-table mr-2"></i>Report data table will appear here</span>
                            </div>

                            {{-- Footer Zone --}}
                            <div class="canvas-zone">
                                <div class="zone-label-bar">
                                    <span class="zone-badge zone-badge-footer"><i class="fas fa-angle-down mr-1"></i>Footer Zone</span>
                                    <div class="zone-add-btns">
                                        <button type="button" class="btn-add-el" onclick="addElement('footer','logo')"><i class="fas fa-image mr-1"></i>Logo</button>
                                        <button type="button" class="btn-add-el" onclick="addElement('footer','text')"><i class="fas fa-font mr-1"></i>Text</button>
                                        <button type="button" class="btn-add-el" onclick="addElement('footer','divider')"><i class="fas fa-minus mr-1"></i>Divider</button>
                                        <button type="button" class="btn-add-el" onclick="addElement('footer','spacer')"><i class="fas fa-arrows-alt-v mr-1"></i>Spacer</button>
                                    </div>
                                </div>
                                <div class="zone-elements" id="footer-elements"></div>
                                <div class="zone-empty-hint" id="footer-empty">
                                    <i class="fas fa-arrow-up mr-1"></i>Use the buttons above to add elements to the footer
                                </div>
                            </div>

                        </div>{{-- /.a4-canvas --}}
                    </div>{{-- /.a4-canvas-outer --}}
                </div>{{-- /col-lg-8 --}}

            </div>{{-- /.row --}}
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// ===== State =====
var state   = { zones: { header: [], footer: [] }, selected: null };
var elCount = 0;
var logoSrc = '{{ asset($template->logo) }}';
var sortables = {};

// ===== Logo upload =====
document.getElementById('logo-input').addEventListener('change', function () {
    if (!this.files || !this.files[0]) return;
    document.querySelector('label[for="logo-input"]').textContent = this.files[0].name;
    var reader = new FileReader();
    reader.onload = function (e) {
        logoSrc = e.target.result;
        document.getElementById('logo-preview').src = logoSrc;
        refreshAllPreviews();
    };
    reader.readAsDataURL(this.files[0]);
});

// ===== Add element =====
function addElement(zone, type) {
    var id = 'el-' + (++elCount);
    var defaults = {
        logo:    { id: id, type: 'logo',    align: 'left',  height: 50 },
        text:    { id: id, type: 'text',    align: 'left',  content: 'Enter text here…', fontSize: 13, fontWeight: 'normal', color: '#000000' },
        divider: { id: id, type: 'divider', color: '#cccccc', thickness: 1 },
        spacer:  { id: id, type: 'spacer',  height: 16 }
    };
    state.zones[zone].push(defaults[type]);
    renderZone(zone);
    selectElement(zone, id);
}

// ===== Delete element =====
function deleteElement(zone, id, evt) {
    if (evt) evt.stopPropagation();
    state.zones[zone] = state.zones[zone].filter(function (el) { return el.id !== id; });
    if (state.selected && state.selected.id === id) state.selected = null;
    renderZone(zone);
    updatePropertyPanel();
}

// ===== Select element =====
function selectElement(zone, id, evt) {
    if (evt) evt.stopPropagation();
    state.selected = { zone: zone, id: id };
    document.querySelectorAll('.canvas-element').forEach(function (el) { el.classList.remove('is-selected'); });
    var card = document.querySelector('[data-el-id="' + id + '"]');
    if (card) card.classList.add('is-selected');
    updatePropertyPanel();
}

// ===== Get element =====
function getEl(zone, id) {
    return state.zones[zone].find(function (el) { return el.id === id; }) || null;
}

// ===== Render a zone =====
function renderZone(zone) {
    var container = document.getElementById(zone + '-elements');
    container.innerHTML = '';
    state.zones[zone].forEach(function (el) { container.appendChild(makeCard(zone, el)); });
    var hint = document.getElementById(zone + '-empty');
    if (hint) hint.style.display = state.zones[zone].length === 0 ? '' : 'none';
    initSortable(zone);
}

// ===== Build element card DOM node =====
function makeCard(zone, el) {
    var labels = { logo: 'Logo', text: 'Text', divider: 'Divider', spacer: 'Spacer' };
    var icons  = { logo: 'fa-image', text: 'fa-font', divider: 'fa-minus', spacer: 'fa-arrows-alt-v' };
    var div = document.createElement('div');
    div.className = 'canvas-element';
    div.dataset.elId = el.id;
    div.dataset.zone = zone;
    div.innerHTML =
        '<div class="el-toolbar">' +
            '<div class="el-toolbar-left">' +
                '<span class="el-drag-handle" title="Drag to reorder"><i class="fas fa-grip-vertical"></i></span>' +
                '<span class="el-type-label"><i class="fas ' + icons[el.type] + ' mr-1"></i>' + labels[el.type] + '</span>' +
            '</div>' +
            '<button type="button" class="el-delete-btn" onclick="deleteElement(\'' + zone + '\',\'' + el.id + '\',event)" title="Remove"><i class="fas fa-times"></i></button>' +
        '</div>' +
        '<div class="el-preview" onclick="selectElement(\'' + zone + '\',\'' + el.id + '\',event)">' +
            previewHtml(el) +
        '</div>';
    return div;
}

// ===== Preview HTML for an element =====
function previewHtml(el) {
    switch (el.type) {
        case 'logo':
            if (!logoSrc) return '<div class="el-logo-placeholder"><i class="fas fa-image mr-1"></i>Logo preview appears after upload</div>';
            return '<div style="text-align:' + el.align + ';padding:4px 0;"><img src="' + logoSrc + '" style="height:' + el.height + 'px;max-width:100%;object-fit:contain;"></div>';
        case 'text':
            var safe = (el.content || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
            return '<div style="text-align:' + el.align + ';font-size:' + el.fontSize + 'px;font-weight:' + el.fontWeight + ';color:' + el.color + ';padding:4px 0;min-height:20px;">' +
                   (safe || '<em style="color:#aaa;font-size:12px;">Empty text block</em>') + '</div>';
        case 'divider':
            return '<hr style="border:none;border-top:' + el.thickness + 'px solid ' + el.color + ';margin:8px 0;">';
        case 'spacer':
            return '<div style="height:' + el.height + 'px;background:repeating-linear-gradient(45deg,#f5f5f5,#f5f5f5 3px,#fff 3px,#fff 10px);border:1px dashed #ddd;display:flex;align-items:center;justify-content:center;"><small style="color:#aaa;font-size:11px;">Spacer &mdash; ' + el.height + 'px</small></div>';
        default: return '';
    }
}

// ===== Refresh all previews =====
function refreshAllPreviews() {
    ['header','footer'].forEach(function (zone) {
        state.zones[zone].forEach(function (el) {
            var p = document.querySelector('[data-el-id="' + el.id + '"] .el-preview');
            if (p) p.innerHTML = previewHtml(el);
        });
    });
}

// ===== SortableJS =====
function initSortable(zone) {
    if (sortables[zone]) sortables[zone].destroy();
    sortables[zone] = Sortable.create(document.getElementById(zone + '-elements'), {
        animation: 150,
        handle: '.el-drag-handle',
        ghostClass: 'el-ghost',
        onEnd: function () {
            var newOrder = [];
            document.querySelectorAll('#' + zone + '-elements .canvas-element').forEach(function (card) {
                var el = getEl(zone, card.dataset.elId);
                if (el) newOrder.push(el);
            });
            state.zones[zone] = newOrder;
        }
    });
}

// ===== Property panel =====
function updatePropertyPanel() {
    ['logo','text','divider','spacer'].forEach(function (t) {
        var p = document.getElementById('props-' + t);
        if (p) p.style.display = 'none';
    });
    document.getElementById('props-empty').style.display = '';
    if (!state.selected) return;
    var el = getEl(state.selected.zone, state.selected.id);
    if (!el) return;
    document.getElementById('props-empty').style.display = 'none';
    document.getElementById('props-' + el.type).style.display = '';
    switch (el.type) {
        case 'logo':
            document.getElementById('prop-logo-height').value = el.height;
            highlightAlign(el.align);
            break;
        case 'text':
            document.getElementById('prop-text-content').value = el.content;
            document.getElementById('prop-text-fontsize').value = el.fontSize;
            document.getElementById('prop-text-bold').checked   = el.fontWeight === 'bold';
            document.getElementById('prop-text-color').value    = el.color;
            highlightAlign(el.align);
            break;
        case 'divider':
            document.getElementById('prop-divider-color').value     = el.color;
            document.getElementById('prop-divider-thickness').value = el.thickness;
            break;
        case 'spacer':
            document.getElementById('prop-spacer-height').value = el.height;
            break;
    }
}

function highlightAlign(align) {
    document.querySelectorAll('.align-btn').forEach(function (btn) {
        btn.classList.toggle('active', btn.dataset.align === align);
    });
}

function onPropChange(key, value) {
    if (!state.selected) return;
    var el = getEl(state.selected.zone, state.selected.id);
    if (!el) return;
    el[key] = value;
    var preview = document.querySelector('[data-el-id="' + el.id + '"] .el-preview');
    if (preview) preview.innerHTML = previewHtml(el);
}

function setPropAlign(align) {
    onPropChange('align', align);
    highlightAlign(align);
}

// ===== Copy token =====
function copyToken(token) {
    var copy = function (text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(showCopyToast);
        } else {
            var ta = document.createElement('textarea');
            ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select(); document.execCommand('copy');
            document.body.removeChild(ta); showCopyToast();
        }
    };
    copy(token);
}

function showCopyToast() {
    var t = document.getElementById('copy-toast');
    t.style.display = '';
    clearTimeout(t._tid);
    t._tid = setTimeout(function () { t.style.display = 'none'; }, 2000);
}

// ===== Form validation & submit =====
$('#designer-form').validate({
    rules: {
        name:   { required: true },
        status: { required: true }
    },
    messages: {
        name:   'Please enter a template name',
        status: 'Please select a status'
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight:   function (el) { $(el).addClass('is-invalid'); },
    unhighlight: function (el) { $(el).removeClass('is-invalid'); },
    submitHandler: function (form) {
        document.getElementById('layout-input').value = JSON.stringify(state.zones);
        form.submit();
    }
});

// ===== Hydrate canvas from existing layout JSON =====
(function () {
    var existing = @json($template->layout ?? null);
    if (!existing) { initSortable('header'); initSortable('footer'); return; }
    try {
        var parsed = JSON.parse(existing);
        ['header', 'footer'].forEach(function (zone) {
            if (!parsed[zone]) return;
            parsed[zone].forEach(function (el) {
                var num = parseInt((el.id || '').replace('el-', ''), 10);
                if (!isNaN(num) && num > elCount) elCount = num;
                state.zones[zone].push(el);
            });
        });
        renderZone('header');
        renderZone('footer');
    } catch (e) {
        initSortable('header');
        initSortable('footer');
    }
})();
</script>
@endsection
