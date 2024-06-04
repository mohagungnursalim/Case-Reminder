@extends('dashboard.layouts.main')
@section('judul-halaman')
Kelola User
@endsection

@section('konten')

<head>
    <script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"
        integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>
</head>


<div class="card card-frame">
    <div class="card-body">
        <div class="container">
            <div class="card-body">
                <div class="alert alert-secondary text-white text-nowrap" style="width: 18rem;" role="alert">
                    <i class="fas fa-fw fa-info-circle"></i> Password bawaan: 12345678
                </div>
    
                <form class="form-inline" action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-2 input-group input-group-outline">
                                <label for="staticEmail2" class="sr-only">Email</label>
                                <input type="text" name="email" class="form-control" id="staticEmail2" placeholder="email@example.com">
                            </div>
                            @error('email')
                                <p class="text-danger small-text">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-2 input-group input-group-outline">
                                <label for="" class="sr-only">Nama</label>
                                <input type="text"  class="form-control" name="name" id="inputNama2" placeholder="Nama">
                            </div>
                            @error('name')
                            <p class="text-danger small-text">{{$message}}</p>
                            @enderror
                        </div>
                            <div class="col-md-4">
                                <input type="hidden" name="is_admin" value="0">
                                <button type="submit" class="btn btn-outline-primary mb-2">Buat</button>
                            </div>
                           
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

    

{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">

        <div class="container">

            <form action="{{ route('user.index') }}" method="GET">
                <div class="container">
                  <div class="row justify-content-end">
                      <div class="col-lg-4">
                          <div class="input-group input-group-outline my-3">

                                  <input type="text" name="search" value="{{request('search')}}" placeholder="Cari User.." class="form-control">
                                  <button type="submit">Search</button>

                          </div>
                      </div>
                  </div>
              </div>
              </form>
            
              {{-- Alert Sukses --}}
              @if (session('success'))
              <div id="alertContainer" class="alert alert-success alert-dismissible text-white fade show" role="alert">
                  <span class="alert-icon align-middle">
                      <span class="material-icons text-md">
                          thumb_up_off_alt
                      </span>
                  </span>
                  <span class="alert-text"><strong>Success!</strong> {{ session('success') }}</span>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              @endif

            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th>No</th>
                        <th>Email</th>
                        <th>Nama User</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>

                    </tr>

                    @if ($users->count())
                    @foreach ($users as $user )
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                            @if ($user->is_admin == false)
                            <kbd class="bg-success">Pustakawan</kbd>
                            @else
                            <kbd class="bg-primary">Admin</kbd>
                            @endif
                            
                        </td>
                    
                        <td>{{ ($user->created_at)->format('d-m-Y') }}</td>

                        <td>
                            <button type="button" class="btn bg-info" data-bs-toggle="modal" data-bs-target="#resetModal{{ $user->id }}">
                                <span class="material-symbols-outlined text-white">
                                    restart_alt
                                </span>
                            </button>
                            <button type="button" class="btn bg-danger" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                <span class="material-symbols-outlined text-white">
                                    delete
                                    </span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">
                                Tidak ada user 
                                @if (request('search'))
                                <kbd>{{ request('search') }}</kbd>                
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>



        </div>




    </div>

</div>

@foreach ($users as $user )
<!-- Modal Delete-->
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Delete User</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
            <h4>Dengan mengklik hapus maka data user <kbd>{{$user->name}}</kbd> akan terhapus secara permanen!</h4>

            <form method="post" action="{{ route('user.destroy',$user->id) }}}}" class="mb-5"
                enctype="multipart/form-data">
                @csrf
                @method('delete')
           <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-danger">Hapus</button>
           </div>
            </form>
        </div>
      </div>
    </div>
  </div>

@endforeach

@foreach ($users as $user )
<!-- Modal Edit-->
<div class="modal fade" id="resetModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Delete User</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
            <h4>Dengan mengklik reset maka password user <kbd>{{$user->name}}</kbd> akan menjadi password default!</h4>

            <form method="post" action="{{ route('user.update',$user->id) }}}}" class="mb-5"
                enctype="multipart/form-data">
                @csrf
                @method('put')
    
          
           <div class="d-flex justify-content-center">
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn bg-gradient-info">Reset</button>
            </div>
           </div>
            </form>
        </div>
      </div>
    </div>
  </div>

@endforeach



@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

{{-- Menghilangkan alert --}}
<script>
    $(document).ready(function() {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function() {
            $('#alertContainer').fadeOut('slow');
        }, 1200);
    });
  </script>