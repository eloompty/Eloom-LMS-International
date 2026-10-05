<nav class="main-header navbar navbar-expand @if( Auth::guard('user')->user()->theme == 'dark') navbar-dark @endif">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Toogle Switch -->
        <li><!-- Sidebar user panel (optional) -->
            <div class="user-panel d-flex nav-link">
                <div class="image">
                    <img src="{{ asset(Auth::guard('user')->user()->image) }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="">
                    <a href="{{ route('admin.user') }}" class="d-block">{{ Auth::guard('user')->user()->first_name }} {{ Auth::guard('user')->user()->family_name }}</a>
                </div>
            </div>
        </li>
        @if( Auth::guard('user')->user()->theme == 'light' || Auth::guard('user')->user()->theme == 'dark')
        <li class="nav-item d-none d-sm-inline-block">
            <div class="nav-link" id="themeText">
                Dark Mode
            </div>
        </li>
        <li class="nav-item">
            <!-- Rounded switch -->
            @if( Auth::guard('user')->user()->theme == 'light')
            <label class="switch">
                <input type="checkbox" id="themeSelector" onclick="themeSelector()">
                <span class="slider round"></span>
            </label>
            @else
            <label class="switch">
                <input type="checkbox" checked id="themeSelector" onclick="themeSelector()">
                <span class="slider round"></span>
            </label>
            @endif
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>