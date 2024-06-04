@extends('dashboard.layouts.main')
@section('judul-halaman')
Kategori
@endsection

@section('konten')


<div class="container">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">

                            <div class="container">
                                <button type="button" class="btn bg-gradient-info" data-bs-toggle="modal"
                                    data-bs-target="#inputModal">
                                    Tambah Kategori
                                </button>
                            </div>
                            
                            <form action="{{ route('kategori.index') }}" method="GET">
                            <div class="container">
                              <div class="row justify-content-end">
                                  <div class="col-lg-4">
                                      <div class="input-group input-group-outline my-3">

                                              <input type="text" name="search" placeholder="Cari Kategori.." class="form-control">
                                              <button type="submit">Search</button>

                                      </div>
                                  </div>
                              </div>
                          </div>
                          </form>
                          
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

                       @if ($kategories->count())                         
                            <table class="table align-items-center mb-0">
                              <thead>
                                  <tr>
                                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                          Nama Kategori
                                      </th>
                                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                          Dibuat
                                      </th>
                                      <th class="text-secondary opacity-7"></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($kategories as $kategori)

                                  <tr>
                                     
                                      <td class="text-wrap align-middle">
                                        <span class="text-xs font-weight-bold mb-0">{{$kategori->nama_kategori}}</span>
                                      </td>
                                   
                                      <td class="align-middle text-center">
                                          <span
                                              class="text-secondary text-xs font-weight-bold">{{$kategori->created_at->format('d/m/Y')}}</span>
                                      </td>
                                      <td class="align-middle">
                                          <button class="badge bg-gradient-warning" style="border: 0ch"
                                              data-bs-toggle="modal"
                                              data-bs-target="#editModal{{$kategori->id}}">Edit</button>
                                          <button class="badge bg-gradient-danger" style="border: 0ch"
                                              data-bs-toggle="modal"
                                              data-bs-target="#deleteModal{{$kategori->id}}">Delete</button>
                                      </td>

                                  </tr>
                                
                                  @endforeach
                              </tbody>
                          </table>
                          
                              @else
                              <div class="container d-flex justify-content-center">
                                <h4 class="text-dark text-bold">Data tidak ditemukan! <span class="material-symbols-outlined">
                                  sentiment_dissatisfied
                                  </span></h4>
                              </div>
                              @endif
                          
                            
                            <div class="d-flex justify-content-center mb-3 mt-4">
                              {{ $kategories->links() }}
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- Modal Tambah Kategori --}}
<div class="modal fade" id="inputModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-4">
                    <form method="post" action="{{ route('kategori.store') }}">
                        @csrf
                        <label class="form-label">Nama Kategori</label>
                        <div class="input-group input-group-outline @error('nama_kategori') is-invalid @enderror mb-1">
                            <input type="text" name="nama_kategori" value="{{old('nama_kategori')}}" class="form-control">
                        </div>
                        @error('nama_kategori')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn bg-gradient-info">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Anggota --}}
@foreach ($kategories as $kategori)
<div class="modal fade" id="editModal{{$kategori->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Edit Kategori</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-4">
                    <form method="post" action="{{ route('kategori.update',$kategori->id) }}">
                        @csrf
                        @method('put')
                        <label class="form-label">Nama Kategori</label>
                        <div class="input-group input-group-outline @error('nama_kategori') is-invalid @enderror mb-2">
                            <input type="text" required name="nama_kategori" placeholder="Masukan nama kategori.."
                                value="{{$kategori->nama_kategori}}" class="form-control">
                        </div>
                        @error('nama_kategori')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror       
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn bg-gradient-info">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Delete Anggota --}}
@foreach ($kategories as $kategori)
<div class="modal fade" id="deleteModal{{$kategori->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Delete Data {{$kategori->nama_kategori}}
                </h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-4">
                    <p class="text-dark">Dengan mengklik Delete,maka kategori
                        <b>{{ $kategori->nama_kategori }}</b> akan terhapus secara permanen!</p>
                </div>
            </div>
            <form action="{{route('kategori.destroy', $kategori->id)}}" method="post">
                @csrf
                @method('delete')
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn bg-gradient-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

{{-- Show Input Modal--}}
@if ($errors->any())
<script>
    $(document).ready(function () {
        $('#inputModal').modal('show');
    });

</script>
@endif

{{-- Menghilangkan alert --}}
<script>
  $(document).ready(function() {
      // Mengatur timeout untuk menghilangkan alert dalam 2 detik
      setTimeout(function() {
          $('#alertContainer').fadeOut('slow');
      }, 1200);
  });
</script>