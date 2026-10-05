@extends('student::student.layouts.master')
@section('title', 'Student | My Grade Appeals')

@section('content')
{{-- Page Header --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>My Grade Appeals</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.gradebook.courses') }}">Gradebook</a></li>
                    <li class="breadcrumb-item active">My Appeals</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
            style="border-radius:8px; border:1px solid #a7f3d0; background:#f0fdf4; color:#065f46; font-size:13px; font-weight:700;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#065f46;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if($appeals->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-inbox"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No appeals submitted yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">
                    You can submit a grade appeal from your
                    <a href="{{ route('student.gradebook.courses') }}" style="color:#2563eb; font-weight:700;">Gradebook</a>.
                </p>
            </div>
        </div>
        @else
        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-flag"></i>
                    Appeals History
                </h3>
                <div class="card-tools">
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        {{ $appeals->count() }} {{ Str::plural('appeal', $appeals->count()) }}
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th class="text-center" style="width:140px;">Status</th>
                            <th>Trainer Response</th>
                            <th>Admin Notes</th>
                            <th style="width:110px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appeals as $appeal)
                        @php
                            $statusBadge = [
                                'pending'          => 'warning',
                                'trainer_reviewed' => 'info',
                                'resolved'         => 'success',
                                'rejected'         => 'danger',
                            ][$appeal->status] ?? 'secondary';
                            $statusLabel = ucfirst(str_replace('_', ' ', $appeal->status));
                        @endphp
                        <tr>
                            <td style="color:#94a3b8; font-weight:700;">{{ $appeal->id }}</td>
                            <td>
                                <span class="badge badge-{{ $appeal->mark_type === 'subject' ? 'primary' : 'success' }}"
                                      style="font-size:11px; font-weight:800; padding:0.35rem 0.6rem; border-radius:999px;">
                                    <i class="fas {{ $appeal->mark_type === 'subject' ? 'fa-book-open' : 'fa-layer-group' }} mr-1"
                                       style="font-size:10px;"></i>{{ ucfirst($appeal->mark_type) }}
                                </span>
                            </td>
                            <td style="max-width:260px;">
                                <span style="font-size:13px; color:#374151; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $appeal->reason }}">
                                    {{ $appeal->reason }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $statusBadge }}" style="font-size:11px; font-weight:800; padding:0.38rem 0.65rem; border-radius:999px; white-space:nowrap;">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td style="font-size:13px; color:#4b5563; max-width:220px;">
                                @if($appeal->trainer_response)
                                <span title="{{ $appeal->trainer_response }}" style="display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $appeal->trainer_response }}
                                </span>
                                @else
                                <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td style="font-size:13px; color:#4b5563; max-width:220px;">
                                @if($appeal->admin_notes)
                                <span title="{{ $appeal->admin_notes }}" style="display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $appeal->admin_notes }}
                                </span>
                                @else
                                <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td style="font-size:12px; color:#6b7280; white-space:nowrap;">
                                {{ $appeal->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Status legend --}}
            <div class="card-footer" style="background:#f8fafc; border-top:1px solid #e5e7eb; padding:12px 16px;">
                <div class="d-flex flex-wrap align-items-center" style="gap:16px;">
                    <span style="font-size:11px; font-weight:800; color:#6b7280; text-transform:uppercase;">Status guide:</span>
                    <span><span class="badge badge-warning" style="font-size:11px;">Pending</span><span style="font-size:11px; color:#6b7280; margin-left:5px;">Awaiting review</span></span>
                    <span><span class="badge badge-info" style="font-size:11px;">Trainer Reviewed</span><span style="font-size:11px; color:#6b7280; margin-left:5px;">Trainer has responded</span></span>
                    <span><span class="badge badge-success" style="font-size:11px;">Resolved</span><span style="font-size:11px; color:#6b7280; margin-left:5px;">Appeal closed</span></span>
                    <span><span class="badge badge-danger" style="font-size:11px;">Rejected</span><span style="font-size:11px; color:#6b7280; margin-left:5px;">Appeal declined</span></span>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
