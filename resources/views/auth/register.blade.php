@extends('layouts.authentication')
@section('judul-halaman')
    Register | 
@endsection

@section('konten')
<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1602722053020-af31042989d5?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Register</h4>
                  <div class="row mt-3">
                    
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form method="post" action="{{route('register')}}" role="form" class="text-start">
                  @csrf
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Nama</label>
                    <input name="name" type="text" class="form-control">
                  </div>
                  <div class="input-group input-group-outline mb-2">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control">
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password</label>
                    <input name="password" type="password" class="form-control">
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password Confirmation</label>
                    <input name="password_confirmation" type="password" class="form-control">
                  </div>
                 
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Register</button>
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
                </script>,
                made with <i class="fa fa-heart" aria-hidden="true"></i> 
              </div>
            </div>
            
          </div>
        </div>
      </footer>
    </div>
  </main>
@endsection
