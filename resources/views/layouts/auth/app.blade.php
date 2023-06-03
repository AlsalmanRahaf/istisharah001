<!DOCTYPE html>
<html>
@include("layouts.auth.parts.header")
@hasSection("css-links")
    @yield("css-links")
@endif
<body>
<section class="material-half-bg" style="background-image: url('{{asset("assets/img/unsplash/andre-benz-1214056-unsplash.jpg")}}')">
    <div class="cover"></div>
</section>
<section class="login-content">
    <div class="logo">
    </div>
    @yield("content")
</section>
<!-- Essential javascripts for application to work-->
@include("layouts.auth.parts.footer")
</body>
</html>
