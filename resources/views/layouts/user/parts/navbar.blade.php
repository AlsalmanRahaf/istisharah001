<header class="app-header">
    <a class="app-header__logo" href="{{ route("dashboard.index") }}">
        <img src="{{asset("assets/logo_text.png")}}" alt="" class="site-logo"></a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"><i class="fas fa-bars"></i></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="#"><i class="fa fa-user fa-lg"></i> Profile</a></li>

                <li><a class="dropdown-item" href="#" onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-lg"></i> Logout</a>
                </li>
                <form id="logout-form" action="{{ route('user.auth.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </ul>
        </li>
    </ul>
</header>
