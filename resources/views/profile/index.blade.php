@extends('dashboard.layouts.main')
{{-- JUDUL --}}
@section('judul-halaman')
Edit Profil
@endsection

@section('konten')

<div class="container mt-3">
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Informasi Profil</h6>

                </div>
                <!-- Card Body -->
                @if (session('success_informasi'))
                <div class="container">
                    <div id="informasiContainer" class="alert alert-success alert-dismissible text-white fade show"
                    role="alert">
                    <span class="alert-icon align-middle">
                        <span class="material-icons text-md">
                            thumb_up_off_alt
                        </span>
                    </span>
                    <span class="alert-text"><strong>Success!</strong> {{ session('success_informasi') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>
                @endif
                <div class="card-body">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        <label for="exampleInputEmail1">Nama</label>
                        <div class="form-group input-group input-group-outline">
                            <input type="text" value="{{ auth()->user()->name }}" name="name" class="form-control"
                                id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">

                        </div>

                        <label for="exampleInputPassword1">Email</label>
                        <div class="form-group input-group input-group-outline">
                            <input type="email" class="form-control" name="email" placeholder="Email"
                                value="{{ auth()->user()->email }}">
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>

                </div>
                <!-- Card Body -->
                @if (session('success_password'))
                <div class="container">
                    <div id="passwordContainer" class="alert alert-success alert-dismissible text-white fade show"
                    role="alert">
                    <span class="alert-icon align-middle">
                        <span class="material-icons text-md">
                            thumb_up_off_alt
                        </span>
                    </span>
                    <span class="alert-text"><strong>Success!</strong> {{ session('success_password') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>
                @endif
                <div class="card-body">

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <label for="password">Password Sekarang</label>
                        <div class="form-group input-group input-group-outline">
                            <input type="password" name="current_password" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Masukan password sekarang">
                        </div>
                        @error('current_password')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label for="password baru">Password Baru</label>
                        <div class="form-group input-group input-group-outline">

                            <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                                placeholder="Masukan password baru">
                        </div>
                        @error('password')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label for="konfirmasi password">Konfirmasi Password Baru</label>
                        <div class="form-group input-group input-group-outline">
                            <input type="password" name="password_confirmation" class="form-control"
                                id="exampleInputPassword1" placeholder="Konfirmasi password baru">
                        </div>
                        @error('password_confirmation')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

{{-- Menghilangkan alert otomatis --}}
<script>
    $(document).ready(function () {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function () {
            $('#informasiContainer').fadeOut('slow');
        }, 1200);
    });

</script>

<script>
    $(document).ready(function () {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function () {
            $('#passwordContainer').fadeOut('slow');
        }, 1200);
    });

</script>