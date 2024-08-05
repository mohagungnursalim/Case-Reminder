<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- Image Favicon --}}
    <link rel="icon" href="{{ asset('assets/favicon_io/favicon.ico') }}" type="image/x-icon">
    <title>
        @yield('judul-halaman')
    </title>

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- Nucleo Icons -->
    {{-- <link href="{{ asset('assets/css/nucleo-icons.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" /> --}}

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- CSS Files -->

    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.1.0')}}" rel="stylesheet" />
    
    <style>
        .whatsapp-float {
    position: fixed;
    width: 60px;
    height: 60px;
    bottom: 20px;
    right: 20px;
    background-color: #25d366;
    color: #FFF;
    border-radius: 50px;
    text-align: center;
    font-size: 30px;
    box-shadow: 2px 2px 3px #999;
    z-index: 1000;
}

.whatsapp-icon {
    width: 100%;
    height: 100%;
    padding: 10px;
}

    </style>
</head>