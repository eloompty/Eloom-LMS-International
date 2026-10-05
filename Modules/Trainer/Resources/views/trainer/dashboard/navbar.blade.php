@php
    $navTheme = getSiteTheme();
    $trainer  = Auth::guard('trainer')->user();
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
            <a href="{{ route('trainer.dashboard') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right: fullscreen + user -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Fullscreen -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- User -->
        <li class="nav-item">
            <a href="{{ route('trainer.profile') }}" class="nav-link">
                <div class="user-panel d-flex align-items-center">
                    <div class="image">
                        <img src="{{ asset($trainer->image) }}"
                             class="img-circle elevation-2"
                             alt="{{ userName('Trainer', $trainer->id) }}">
                    </div>
                    <div class="d-none d-md-block ml-2">
                        <span class="d-block" style="font-size:13px;font-weight:700;line-height:1.2;">
                            {{ userName('Trainer', $trainer->id) }}
                        </span>
                        <span class="d-block" style="font-size:11px;font-weight:600;opacity:.65;">Faculty</span>
                    </div>
                </div>
            </a>
        </li>

    </ul>
</nav>
