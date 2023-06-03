<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <a class="app-sidebar__logo" href="{{ route("dashboard.index") }}">
        <img src="{{asset("assets/logo2.png")}}" alt="" class="site-logo full">
        <img src="{{asset("assets/logo1.png")}}" alt="" class="site-logo small">
    </a>
    <div class="app-sidebar__user">
        <div class="avatar-box">
            <img class="app-sidebar__user-avatar" src="" alt="">
        </div>
        <div>
            <p class="app-sidebar__user-name">Ahmad Mohammad</p>
            <p class="app-sidebar__user-name" style="font-size: 14px; color: #f2f2f2">Admin</p>
        </div>
    </div>
    <ul class="app-menu">

            <li><a class="app-menu__item @if(request()->routeIs("dashboard.index")) active @endif" href="{{route("dashboard.index")}}"><i class="app-menu__icon fas fa-tachometer-alt"></i><span class="app-menu__label">Dashboard</span></a></li>

            <!------------------------- Cities -------------------------->
            <li class="treeview @if(request()->routeIs("admin.city.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-city"></i><span class="app-menu__label">Services Cities</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->routeIs("admin.city.create")) active @endif" href="{{ route("admin.city.create") }}"><i class="icon fa fa-circle-o"></i> Create City</a></li>
                    <li><a class="treeview-item @if(request()->routeIs("admin.city.index")) active @endif" href="{{ route("admin.city.index") }}"><i class="icon fa fa-circle-o"></i> All Cities </a></li>
                </ul>
            </li>

            <!------------------------- Sections -------------------------->
            <li class="treeview @if(request()->routeIs("admin.sections.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-th-large"></i><span class="app-menu__label">Sections</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->routeIs("admin.sections.create")) active @endif" href="{{ route("admin.sections.create") }}"><i class="icon fa fa-circle-o"></i> Create Section</a></li>
                    <li><a class="treeview-item @if(request()->routeIs("admin.sections.index")) active @endif" href="{{ route("admin.sections.index") }}"><i class="icon fa fa-circle-o"></i> All Sections</a></li>
                </ul>
            </li>


            <!------------------------- Categories -------------------------->
            <li class="treeview @if(request()->routeIs("admin.main_categories.*", "admin.sub_categories.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-list-ul"></i><span class="app-menu__label">Categories</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->routeIs("admin.main_categories.create")) active @endif" href="{{ route("admin.main_categories.create") }}"><i class="icon fa fa-circle-o"></i> Create Main Category</a></li>
                    <li><a class="treeview-item @if(request()->routeIs("admin.main_categories.index", "admin.sub_categories.*")) active @endif" href="{{ route("admin.main_categories.index") }}"><i class="icon fa fa-circle-o"></i> All Main Categories</a></li>
                </ul>
            </li>


            <!------------------------- Services -------------------------->
            <li class="treeview @if(request()->routeIs("admin.services.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-tools"></i><span class="app-menu__label">Services</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->routeIs("admin.services.create")) active @endif" href="{{ route("admin.services.create") }}"><i class="icon fa fa-circle-o"></i> Create Service</a></li>
                    <li><a class="treeview-item @if(request()->routeIs("admin.services.index")) active @endif" href="{{ route("admin.services.index") }}"><i class="icon fa fa-circle-o"></i> All Services</a></li>
                </ul>
            </li>

    </ul>
</aside>
