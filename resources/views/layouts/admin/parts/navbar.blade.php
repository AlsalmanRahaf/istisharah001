<header class="app-header">
    <a class="app-header__logo" href="{{ route("admin.dashboard.index") }}">
        <img src="{{asset("assets/logo2.png")}}" alt="" class="site-logo"></a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"><i class="fas fa-bars"></i></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">



        <!--Notification Menu-->
{{--        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications"><i class="notification-icon fas fa-bell fa-lg"></i></a>--}}
{{--            <ul class="app-notification dropdown-menu dropdown-menu-right" style="width: 330px">--}}
{{--                <li class="app-notification__title">You have <span class="notification-number-non-show"></span> new notifications.</li>--}}
{{--                <div class="app-notification__content">--}}
{{--                    @foreach(request()->user("admin")->adminNotification()["all"] as $notification)--}}
{{--                        <li class="@if(!$notification->is_open) un-open @endif">--}}
{{--                            <a class="app-notification__item notification-box" href="{{$notification->url}}" data-id="{{$notification->id}}">--}}
{{--                                <div class="notification">--}}
{{--                                    <p class="app-notification__header">{{$notification->title}}</p>--}}
{{--                                    <p class="app-notification__message">{{$notification->body}}</p>--}}
{{--                                    <span class="app-notification__meta">{{$notification->created}}</span>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--                <li class="app-notification__footer"><a href="#">See all notifications.</a></li>--}}
{{--            </ul>--}}
{{--        </li>--}}


        <!-- Language Menu-->
        <li class="dropdown lang"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fas fa-globe" style=""></i><span class="lang-name">{{__(\Illuminate\Support\Facades\App::getLocale())}}</span></a>

            <ul class="dropdown-menu settings-menu">
                @foreach(config()->get("app.languages") as $langKey => $langText)
                    @if(app()->getLocale() !== $langKey)
                        <li><a class="dropdown-item @if(isArabic($langText)) arabic-font @endif" href="{{getSameWithNewLanguage($langKey)}}"><img src="{{asset("assets/img/flags/{$langKey}.png")}}" style="width: 20px" alt="" > {{$langText}}</a></li>
                    @endif

                @endforeach
            </ul>
        </li>

        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>

            <ul class="dropdown-menu settings-menu">
                <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}"><i class="fa fa-user fa-lg"></i> Profile</a></li>

                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-lg"></i> Logout</a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </ul>
        </li>

    </ul>

</header>
