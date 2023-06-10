<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <a class="app-sidebar__logo" href="{{ route("admin.admins.Firewall",["route"=>"admin.dashboard.index"])}}">
            <img src="{{asset("assets/logo-color.png")}}" alt="" class="site-logo full" style="margin-top:-30%;margin-bottom: -30%;z-index: 100;min-width: 100%;border-radius: 6px;">
        </a>
        <div class="avatar-box">
            <img class="" src="{{auth()->user()->profile_photo}}" alt="" style="z-index: 1001;width:60px;height: 60px;border-radius:50%;border: 1px solid black">
        </div>
        <div >
            <p class="app-sidebar__user-name" style="text-align: center">{{auth()->user()->full_name}}</p>
        </div>
    </div>
    <ul class="app-menu">
        <!-------------------------  Dashboard -------------------------->
        @if(hasPermissions("view-dashboard"))
            <li><a class="app-menu__item @if(request()->routeIs("admin.dashboard.index")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.dashboard.index"])}}"><i class="app-menu__icon fas fa-tachometer-alt"></i><span class="app-menu__label">{{__("Dashboard")}}</span></a></li>
        @endif
        <!------------------------- Users -------------------------->
        @if(hasPermissions("view-uesrs"))
            <li class="treeview @if(request()->routeIs("admin.consultations.*") || request()->routeIs("admin.users.*")) is-expanded @endif" ><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-user-tie"></i><span class="app-menu__label">{{__("Users")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.users.index")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.users.index"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Users app")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.users.RequestConsultant")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.users.RequestConsultant"])}}"><i class="icon fa fa-circle-o"></i> {{__("Request Consultant")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.consultations.Custom")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.consultations.Custom"])}}"><i class="icon fa fa-circle-o"></i> {{__("Other Consultant")}}</a></li>

                </ul>
            </li>
        @endif

        <li class="treeview @if(request()->routeIs("admin.category_branch.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-list"></i><span class="app-menu__label">{{__('Categories Branches')}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->routeIs("admin.category_branch.create")) active @endif" href="{{ route("admin.category_branch.create")}}"> {{__('Create New Category')}}</a></li>
                <li><a class="treeview-item @if(request()->routeIs("admin.category_branch.index")) active @endif" href="{{ route("admin.category_branch.index")}}"> {{__('All Categories')}}</a></li>
            </ul>
        </li>

        <li class="treeview @if(request()->routeIs("admin.branches.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-store"></i><span class="app-menu__label">{{__('Branches')}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->routeIs("admin.branches.create")) active @endif" href="{{ route("admin.branches.create")}}"><i class="icon fa fa-circle-o"></i>{{__('Create New Branch')}} </a></li>
                <li><a class="treeview-item @if(request()->routeIs("admin.branches.index")) active @endif" href="{{ route("admin.branches.index") }}"><i class="icon fa fa-circle-o"></i>{{__('All Branches')}} </a></li>
            </ul>
        </li>

        <li class="treeview @if(request()->routeIs("admin.categories.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-list"></i><span class="app-menu__label">{{__('Categories')}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->routeIs("admin.categories.create")) active @endif" href="{{ route("admin.categories.create")}}"> {{__('Create New Category')}}</a></li>
                <li><a class="treeview-item @if(request()->routeIs("admin.categories.index")) active @endif" href="{{ route("admin.categories.index")}}"> {{__('All Categories')}}</a></li>
            </ul>
        </li>
        <!------------------------- Register Form -------------------------->
        <li class="treeview @if(request()->routeIs("admin.consultantrequest.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-plus"></i><span class="app-menu__label">{{__('Request Join')}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->routeIs("admin.branches.create")) active @endif" href="{{ route("admin.branches.create")}}"><i class="icon fa fa-circle-o"></i>{{__('Create New Branch')}} </a></li>
                <li><a class="treeview-item @if(request()->routeIs("admin.consultantrequest.index")) active @endif" href="{{ route("admin.consultantrequest.index") }}"><i class="icon fa fa-circle-o"></i>{{__('All Branches')}} </a></li>
            </ul>
{{--        @if(isPermissionsAllowed("view-drivers-requests-join", "view-institution-requests-join"))--}}
{{--            <li class="treeview @if(request()->routeIs("admin.form-register*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-plus"></i><span class="app-menu__label">{{__("Request Join")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>--}}
{{--                <ul class="treeview-menu">--}}
{{--                    @if(isPermissionsAllowed("view-institution-requests-join"))--}}
{{--                        <li><a class="treeview-item @if(request()->is("*/admin/form-register/institution*")) active @endif" href="{{ route("admin.form-register.institution.index") }}"><i class="icon fa fa-circle-o"></i> {{__("Institution")}}</a></li>--}}
{{--                    @endif--}}
{{--                    @if(isPermissionsAllowed("view-drivers-requests-join"))--}}
{{--                        <li><a class="treeview-item @if(request()->is("*/admin/form-register/driver*")) active @endif" href="{{ route("admin.form-register.driver.show") }}"><i class="icon fa fa-circle-o"></i> {{__("Driver")}}</a></li>--}}
{{--                    @endif--}}

{{--                </ul>--}}
{{--            </li>--}}
{{--        @endif--}}


        <li class="treeview @if(request()->routeIs("admin.items.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-tags"></i><span class="app-menu__label">{{__('Items')}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->routeIs("admin.items.create")) active @endif" href="{{ route("admin.items.create") }}"><i class="icon fa fa-circle-o"></i> {{__('Create New Items')}}</a></li>
                <li><a class="treeview-item @if(request()->routeIs("admin.items.index")) active @endif" href="{{ route("admin.items.index") }}"><i class="icon fa fa-circle-o"></i>{{__('All Items')}} </a></li>
            </ul>
        </li>


        <li class="treeview @if(request()->routeIs("admin.brands.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-tags"></i><span class="app-menu__label">{{__('Brands')}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->routeIs("admin.brands.create")) active @endif" href="{{ route("admin.brands.create") }}"><i class="icon fa fa-circle-o"></i> {{__('Create New Brands')}}</a></li>
                <li><a class="treeview-item @if(request()->routeIs("admin.brands.index")) active @endif" href="{{ route("admin.brands.index") }}"><i class="icon fa fa-circle-o"></i>{{__('All Brands')}} </a></li>
            </ul>
        </li>

        <li class="treeview @if(request()->routeIs("admin.orders.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-users-cog"></i><span class="app-menu__label">{{__('Orders')}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">

                <li><a class="treeview-item @if(request()->routeIs("admin.orders.index")) active @endif" href="{{ route("admin.orders.index") }}"><i class="icon fa fa-circle-o"></i>{{__('User Orders')}}</a></li>
                <li><a class="treeview-item @if(request()->routeIs("admin.orders.posOrders")) active @endif" href="{{ route("admin.orders.posOrders") }}"><i class="icon fa fa-circle-o"></i>{{__('Cashier Orders')}}</a></li>
            </ul>
        </li>


        <li class="treeview @if(request()->routeIs("admin.slider_market.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas far fa-images"></i><span class="app-menu__label">{{__('Sliders Market')}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->routeIs("admin.slider_market.create")) active @endif" href="{{ route("admin.slider_market.create")}}"><i class="icon fa fa-circle-o"></i> {{__('Create New Slider Market')}}</a></li>
                <li><a class="treeview-item @if(request()->routeIs("admin.slider_market.index")) active @endif" href="{{ route("admin.slider_market.index")}}"><i class="icon fa fa-circle-o"></i> {{__('All Sliders Market')}}</a></li>
            </ul>
        </li>
        <!------------------------- Messages -------------------------->
        @if(hasPermissions("view-messages"))
            <li class="treeview @if(request()->routeIs("admin.messages.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon  fas fa-envelope"></i><span class="app-menu__label">{{__("Messages")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.messages.consultant")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.messages.consultant"]) }}"><i class="icon fa fa-circle-o"></i> {{__("consultants")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.messages.specializedconsultant")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.messages.specializedconsultant"]) }}"><i class="icon fa fa-circle-o"></i> {{__("specialized consultants")}}</a></li>
                    @if(hasPermissions("admin-control"))
                        <li><a class="treeview-item @if(request()->is("admin.messages.users")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.messages.users"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Users Messages")}}</a></li>
                    @endif
                    <li><a class="treeview-item @if(request()->is("admin.messages.Other")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.messages.Other"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Other ")}}</a></li>

                </ul>
            </li>
        @endif


        <!------------------------- Advertisement -------------------------->

        @if(hasPermissions("view-ads"))
            <li class="treeview @if(request()->routeIs("admin.Ads.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon  fas fa-ad"></i><span class="app-menu__label">{{__("Advertisement")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.Ads.index")) active @endif" href="{{ route("admin.Ads.index") }}"><i class="icon fa fa-circle-o"></i> {{__("Advertisement")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.Ads.loadingAds")) active @endif" href="{{ route("admin.Ads.loadingAds") }}"><i class="icon fa fa-circle-o"></i> {{__("Loading Advertisement")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.Ads.create")) active @endif" href="{{ route("admin.Ads.create") }}"><i class="icon fa fa-circle-o"></i> {{__("Create New Advertisement")}}</a></li>
                </ul>
            </li>
        @endif
        <!------------------------- Social Media -------------------------->

        @if(hasPermissions("social-media"))
            <li class="treeview @if(request()->routeIs("admin.social-media.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon  fas fa-mobile-alt"></i><span class="app-menu__label">{{__("Social Media")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.social-media.index")) active @endif" href="{{ route("admin.social-media.index") }}"><i class="icon fa fa-circle-o"></i> {{__("All social media")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.social-media.create")) active @endif" href="{{ route("admin.social-media.create") }}"><i class="icon fa fa-circle-o"></i> {{__("Create New social media")}}</a></li>
                </ul>
            </li>
        @endif


        <!------------------------- Promo code -------------------------->
        @if(hasPermissions("view-promos-code"))
            <li class="treeview @if(request()->routeIs("admin.promo-code.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-percent"></i><span class="app-menu__label">{{__("promo code")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.promo-code.index")) active @endif" href="{{ route("admin.promo-code.index") }}"><i class="icon fa fa-circle-o"></i> {{__("users promo code")}}</a></li>
                </ul>
            </li>
        @endif
        <!------------------------- App Setting -------------------------->
        @if(hasPermissions("view-app-settings"))
            <li class="treeview @if(request()->routeIs("admin.App-Setting.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cogs"></i><span class="app-menu__label">{{__("App Setting")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    @if(hasPermissions("view-app-settings"))

                        <li><a class="treeview-item @if(request()->is("admin.App-Setting.Setting")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.App-Setting.Setting"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Setting")}}</a></li>
                        <li><a class="treeview-item @if(request()->is("admin.App-Setting.showMainButton")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.App-Setting.showMainButton"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Main Button")}}</a></li>
                        <li><a class="treeview-item @if(request()->is("admin.App-Setting.ButtonChatHistory")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.App-Setting.ButtonChatHistory"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Chat History Button")}}</a></li>
                        <li><a class="treeview-item @if(request()->is("admin.App-Setting.ConsultationHistory")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.App-Setting.ConsultationHistory"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Consultation Button")}}</a></li>

                    @endif
                </ul>
            </li>
        @endif

        <!------------------------- consultations -------------------------->

        {{--        @if(hasPermissions("view-consultations"))--}}
        {{--        <li class="treeview @if(request()->routeIs("admin.consultations.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">{{__("consultations")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>--}}
        {{--            <ul class="treeview-menu">--}}
        {{--                <li><a class="treeview-item @if(request()->is("admin.consultations.index")) active @endif" href="{{ route("admin.consultations.index") }}"><i class="icon fa fa-circle-o"></i> {{__("show all consultations")}}</a></li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}
        {{--        @endif--}}
        <!------------------------- Sliders -------------------------->
        @if(hasPermissions("view-sliders"))
            <li class="treeview @if(request()->routeIs("admin.sliders.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-sliders-h"></i><span class="app-menu__label">{{__("Sliders")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.sliders.index")) active @endif" href="{{ route("admin.sliders.index") }}"><i class="icon fa fa-circle-o"></i> {{__("show all slider")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.sliders.create")) active @endif" href="{{ route("admin.sliders.create") }}"><i class="icon fa fa-circle-o"></i> {{__("create new slider")}}</a></li>
                </ul>
            </li>
        @endif
        <!------------------------- admins -------------------------->
        @if(hasPermissions("view-admin-control"))
            <li class="treeview @if(request()->routeIs("admin.admins.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user-circle"></i><span class="app-menu__label">{{__("Admins")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.admins.index")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.admins.index"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Admin")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.admins.create")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.admins.create"]) }}"><i class="icon fa fa-circle-o"></i> {{__("create new Admin")}}</a></li>
                </ul>
            </li>
        @endif
        <!------------------------- roles -------------------------->
        @if(hasPermissions("admin-role"))
            <li class="treeview @if(request()->routeIs("admin.roles.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-address-card"></i><span class="app-menu__label">{{__("Roles")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.roles.index")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.roles.index"]) }}"><i class="icon fa fa-circle-o"></i> {{__("Roles")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.roles.create")) active @endif" href="{{ route("admin.admins.Firewall",["route"=>"admin.roles.create"]) }}"><i class="icon fa fa-circle-o"></i> {{__("create new Roles")}}</a></li>
                </ul>
            </li>
        @endif

        <!------------------------- Notifcations -------------------------->
        @if(hasPermissions("view-notifications"))
            <li class="treeview @if(request()->routeIs("admin.Notification.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-user-tie"></i><span class="app-menu__label">{{__("Notification")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.Notification.SendForAll")) active @endif" href="{{ route("admin.Notification.SendForAll") }}"><i class="icon fa fa-circle-o"></i> {{__("Send to all")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.Notification.SendForCustom")) active @endif" href="{{ route("admin.Notification.SendForCustom") }}"><i class="icon fa fa-circle-o"></i> {{__("Send to custom users")}}</a></li>
                </ul>
            </li>
        @endif


        <!------------------------- WelcomeMessage -------------------------->

        @if(hasPermissions("welcome-message"))
            <li class="treeview @if(request()->routeIs("admin.WelcomeMessage.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon  fas fa-envelope"></i><span class="app-menu__label">{{__("WelcomeMessage")}}</span><i class=" fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->routeIs("admin.WelcomeMessage.create")) active @endif" href="{{ route("admin.WelcomeMessage.create") }}"><i class="icon fa fa-circle-o"></i>{{__("create Welcome Message")}}</a></li>
                    <li><a class="treeview-item @if(request()->routeIs("admin.WelcomeMessage.index")) active @endif" href="{{ route("admin.WelcomeMessage.index") }}"><i class="icon fa fa-circle-o"></i>{{__("show Welcome Messages")}}</a></li>
                </ul>
            </li>
        @endif
        <li class="treeview @if(request()->routeIs("admin.timeSlotType.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-address-card"></i><span class="app-menu__label">{{__("Time Slot Types")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->is("admin.timeSlotType.index")) active @endif" href="{{ route("admin.timeSlotType.index") }}"><i class="icon fa fa-circle-o"></i> {{__("All Time Slot Types")}}</a></li>
            </ul>
        </li>
        @if(hasPermissions("view-specialization"))
            <li class="treeview @if(request()->routeIs("admin.specialization.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon  fas fa-certificate"></i><span class="app-menu__label">{{__("specializations")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.specialization.index")) active @endif" href="{{ route("admin.specialization.index") }}"><i class="icon fa fa-circle-o"></i> {{__("all-specializations")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.specialization.create")) active @endif" href="{{ route("admin.specialization.create") }}"><i class="icon fa fa-circle-o"></i> {{__("create-new-specialization")}}</a></li>
                </ul>
            </li>
        @endif
        @if(hasPermissions("view-consultant"))
            <li class="treeview @if(request()->routeIs("admin.consultant.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon  fas fa-user-md"></i><span class="app-menu__label">{{__("consultants")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.consultant.index")) active @endif" href="{{ route("admin.consultant.index") }}"><i class="icon fa fa-circle-o"></i> {{__("All Consultants")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.consultant.create")) active @endif" href="{{ route("admin.consultant.create") }}"><i class="icon fa fa-circle-o"></i> {{__("Create New Consultant")}}</a></li>
                </ul>
            </li>
        @endif
        <li class="treeview @if(request()->routeIs("admin.date.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-address-card"></i><span class="app-menu__label">{{__("Appointment")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->is("admin.date.index")) active @endif" href="{{ route("admin.date.index") }}"><i class="icon fa fa-circle-o"></i> {{__("All Appointments")}}</a></li>
                <li><a class="treeview-item @if(request()->is("admin.date.create")) active @endif" href="{{ route("admin.date.create") }}"><i class="icon fa fa-circle-o"></i> {{__("Create Appointment")}}</a></li>
            </ul>
        </li>

        @if(hasPermissions("view-notification-reminder"))
            <li class="treeview @if(request()->routeIs("admin.notification-reminder.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon  fas fa-bell"></i><span class="app-menu__label">{{__("notification-reminder")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->is("admin.notification-reminder.index")) active @endif" href="{{ route("admin.notification-reminder.index") }}"><i class="icon fa fa-circle-o"></i> {{__("all-notification-reminders")}}</a></li>
                    <li><a class="treeview-item @if(request()->is("admin.notification-reminder.create")) active @endif" href="{{ route("admin.notification-reminder.create") }}"><i class="icon fa fa-circle-o"></i> {{__("create-new-notification-reminder")}}</a></li>
                </ul>
            </li>
        @endif

    </ul>
</aside>

