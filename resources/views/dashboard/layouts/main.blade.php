<!--
=========================================================
* Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================
-->

<!DOCTYPE html>
<html lang="en">

{{-- Head --}}
@includeIf('dashboard.partials.header')
{{-- End head --}}

<body class="g-sidenav-show  bg-gray-100">

    {{-- Sidebar --}}
    @includeIf('dashboard.partials.sidebar')
    {{-- sidebar --}}

    <main class="main-content border-radius-lg ">
        <!-- Navbar -->
        @includeIf('dashboard.partials.navbar')
        <!-- End Navbar -->

        <div class="container-fluid py-4">

            {{-- Konten --}}
            @yield('konten')
            {{-- End konten --}}
            
            {{-- Footer --}}
            @includeIf('dashboard.partials.footer')
            {{-- End footer --}}

        </div>
    </main>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

    </script>

    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.1.0')}}"></script>
</body>

</html>
