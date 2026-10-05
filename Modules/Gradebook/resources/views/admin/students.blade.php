@extends('user::layouts.master')
@section('title', 'Admin | Gradebook — Students')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Gradebook</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Gradebook</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Enrolled Students</h3>
                <a href="{{ route('admin.gradebook.appeals') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-flag"></i> Grade Appeals
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Intake</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($intakeCourses as $sic)
                        <tr>
                            <td>
                                {{ $sic->student->first_name ?? '' }} {{ $sic->student->last_name ?? '' }}
                            </td>
                            <td>{{ optional(optional($sic->intakeCourse)->course)->course_name ?? '—' }}</td>
                            <td>{{ optional(optional($sic->intakeCourse)->intake)->name ?? '—' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.gradebook.show', [$sic->student_id, $sic->id]) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-chart-line"></i> View Gradebook
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No enrolled students found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($intakeCourses->hasPages())
            <div class="card-footer">
                {{ $intakeCourses->links() }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
