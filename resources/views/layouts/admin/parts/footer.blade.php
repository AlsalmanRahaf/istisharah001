<!-- Essential javascripts for application to work-->
<script src="{{asset("assets/js/jquery-3.2.1.min.js")}}"></script>
<script src="{{asset("assets/js/popper.min.js")}}"></script>
<script src="{{asset("assets/js/bootstrap.min.js")}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset("assets/js/main.js")}}"></script>
<script type="module" src="{{asset("assets/js/master.js")}}"></script>

<!-- The javascript plugin to display page loading on top-->
<script src="{{asset("assets/js/plugins/pace.min.js")}}"></script>
<script type="module" src="{{asset("assets/js/modules/helpers.js")}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://cdn.socket.io/4.4.0/socket.io.min.js" integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous"></script>    <script type="module">
// import { io } from "socket.io-client";
</script>
<!-- Page specific javascripts-->
@livewireScripts
@hasSection("scripts")
    @yield("scripts")
@endif
