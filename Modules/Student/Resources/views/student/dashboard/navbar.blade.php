@php
    $navTheme = getSiteTheme();
    $notifs   = notification('Student');
    $student  = Auth::guard('student')->user();
@endphp
<nav class="main-header navbar navbar-expand {{ $navTheme !== 'theme3' ? 'dark-mode' : '' }}">

    <!-- Left: sidebar toggle + home -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('student.dashboard') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right: notifications + user -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true">
                <i class="far fa-bell"></i>
                @if($notifs->count() > 0)
                <span class="badge badge-warning navbar-badge">{{ $notifs->count() }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    {{ $notifs->count() }} Notification{{ $notifs->count() !== 1 ? 's' : '' }}
                </span>
                @forelse($notifs as $value)
                <div class="dropdown-divider"></div>
                @if($value->type == 'Assignment')
                <a href="{{ $value->assignment_url }}" class="dropdown-item">
                    <i class="fas fa-file-alt mr-2"></i>
                    <span>{{ $value->title }}</span>
                    <span class="float-right text-muted text-sm">{{ dateFormat($value->created_at) }}</span>
                </a>
                @elseif($value->type == 'OnlineClass')
                @php $zoomUrl = 'https://us06web.zoom.us/j/' . $value->link; @endphp
                <a href="{{ $zoomUrl }}" class="dropdown-item" target="_blank">
                    <i class="fas fa-video mr-2"></i>
                    <span>Zoom Class</span>
                    <span class="float-right text-muted text-sm">{{ dateFormat($value->created_at) }}</span>
                </a>
                @endif
                @empty
                <div class="dropdown-divider"></div>
                <span class="dropdown-item text-center text-muted">No new notifications</span>
                @endforelse
                <div class="dropdown-divider"></div>
                <a href="{{ route('student.notification.index') }}" class="dropdown-item dropdown-footer">
                    See All Notifications
                </a>
            </div>
        </li>

        <!-- Fullscreen -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- User -->
        <li class="nav-item">
            <a href="{{ route('student.profile') }}" class="nav-link">
                <div class="user-panel d-flex align-items-center">
                    <div class="image">
                        <img src="{{ asset($student->image) }}"
                             class="img-circle elevation-2"
                             alt="{{ userName('Student', $student->id) }}">
                    </div>
                    <div class="d-none d-md-block ml-2">
                        <span class="d-block" style="font-size:13px;font-weight:700;line-height:1.2;">
                            {{ userName('Student', $student->id) }}
                        </span>
                        <span class="d-block" style="font-size:11px;font-weight:600;opacity:.65;">Student</span>
                    </div>
                </div>
            </a>
        </li>

    </ul>
</nav>
