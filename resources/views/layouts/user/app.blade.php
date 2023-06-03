<!DOCTYPE html>
<html lang="en">
<head>
@include("layouts.user.parts.header")
</head>
<body class="app sidebar-mini rtl">
<!-- Navbar-->
@include("layouts.user.parts.navbar")

<!-- Sidebar menu-->
@include("layouts.user.parts.slidebar")

<main class="app-content">
    @hasSection("page-nav-title")
        @yield("page-nav-title")
    @endif
    @yield("content")
</main>
@include("layouts.user.parts.footer")
</body>
</html>
