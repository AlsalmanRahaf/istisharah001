<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
@include("layouts.admin.parts.header")
</head>
<body id="body" class="app sidebar-mini rtl">
<!-- Navbar-->
<script>
    const box = document.getElementById('body');
    var target = document.querySelector("#body");
    {{--target.innerHTML += '<div class="loader-div"><span class="loader"><img class="app-sidebar__user-avatar" src="{{env("APP_URL")}}/uploads/logo/istockphoto-1127993641-170667a.jpg" alt="User Image"></span></div>';--}}
</script>


@include("layouts.admin.parts.navbar")

<!-- Sidebar menu-->
@include("layouts.admin.parts.slidebar")

<main class="app-content">
    @hasSection("page-nav-title")
        @yield("page-nav-title")
    @endif
    @yield("content")
</main>
@include("layouts.admin.parts.footer")
</body>

<script>
    $(window).on('load', function(){
        setTimeout(removeLoader, 200); //wait for page load PLUS two seconds.
    });

    function removeLoader(){
        $( ".loader-div" ).fadeOut(200, function() {
            // fadeOut complete. Remove the loading div
            $( ".loader-div" ).remove(); //makes page more lightweight
        });
    }
</script>

</html>
