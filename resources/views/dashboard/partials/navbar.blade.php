<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('judul-halaman')</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">@yield('sub-halaman')</h6>

                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                        {{-- <div class="input-group input-group-outline">
                            <label class="form-label">Cari cepat..</label>
                            <input type="text" class="form-control">
                        </div> --}}

                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        
                        <li>
                            <span> <small><i class="fa fa-user me-sm-1" aria-hidden="true"></i> {{Auth::user()->name}}</small></span>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        
                        
                        
                    </ul>
                </div>
            </div>
        </nav>