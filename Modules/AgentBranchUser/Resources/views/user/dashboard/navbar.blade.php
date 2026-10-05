<nav class="main-header dark-mode navbar navbar-expand">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('branch-user.dashboard') }}" class="nav-link">Home</a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li>
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel d-flex nav-link">
                <div class="image">
                    <img src="{{ asset(Auth::guard('agent_branch_user')->user()->image) }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('branch-user.profile') }}" class="d-block">{{ Auth::guard('agent_branch_user')->user()->name }}</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>