@extends('layouts.authentication')
@section('judul-halaman')
    Login | SI-RIKSALIDIK
@endsection

@section('konten')
<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://si-riksalidik.com/assets/img/Kantor-Kejati.jpg');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Login SI-RIKSALIDIK</h4>
                  <div class="row mt-3">
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form method="post" action="{{ route('login') }}" role="form" class="text-start">
                  @csrf
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" required  class="form-control" value="{{ old('email') }}">
                  </div>
                  @error('email')
                     <p class="text-danger small">{{ $message }}</p>
                  @enderror
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" required class="form-control">
                  </div>
                  @error('password')
                    <p class="text-danger small">{{ $message }}</p>
                  @enderror
                  <div class="form-check form-switch d-flex align-items-center mb-3">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label mb-0 ms-3" for="rememberMe">Ingat Akun Saya</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-success w-100 my-4 mb-2">Masuk</button>
                  </div>
                </form>
                
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script> SI-RIKSALIDIK,
                 All rights reserved.
              </div>
            </div>
            
          </div>
        </div>
      </footer>
    </div>
  </main>
@endsection
