@extends('dashboard.layouts.main')
@section('judul-halaman')
Saksi
@endsection

@section('konten')

{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">
        
        <div class="container">
            <form action="{{ route('saksi.index') }}" method="GET">
                <div class="container">
                  <div class="row justify-content-end">
                      <div class="col-lg-4">
                          <div class="input-group input-group-outline my-3">

                                  <input type="text" name="search" value="{{request('search')}}" placeholder="Cari Saksi.." class="form-control">
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

              {{-- Tombol ke halaman form --}}
              <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#inputModal">
                Tambah Saksi
              </button>
            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Saksi</th>
                        <th>Alamat</th>
                        <th>No Wa</th>
                        <th>Pekerjaan</th>
                        <th>Ditambahkan</th>
                        <th>Aksi</th>

                    </tr>

                    @if ($saksis->count())
                    @foreach ($saksis as $saksi )
                    <tr>
                        <td class="text-wrap small">{{ $loop->iteration }}</td>
                        <td class="small">{{ $saksi->nama }}</td>
                        <td class="text-wrap small">{{ $saksi->alamat}}</td>
                        <td class="text-wrap small">{{ $saksi->nomor_wa }}</td>
                        <td class="text-wrap small">{{ $saksi->pekerjaan }}</td>
                        <td class="text-wrap small">{{ $saksi->created_at->format('d-m-Y') }}</td>

                        <td>
                            <button type="button" class="btn btn-sm bg-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $saksi->id }}">
                                <span class="material-symbols-outlined text-white">
                                    edit_square
                                </span>
                            </button>
                            <button type="button" class="btn btn-sm bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $saksi->id }}">
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
                                Tidak ada data Saksi..
                                @if (request('search'))
                                <kbd>{{ request('search') }}</kbd>                
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $saksis->links() }}
            </div>



        </div>




    </div>

</div>

{{-- Modal input Saksi --}}
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="SaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Form Tambah Saksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('saksi.store') }}" method="POST">
            @csrf
            <label for="nama">Nama Saksi</label>
            <div class="input-group input-group-outline @error('nama') is-invalid @enderror">
                <input class="form-control" type="text" name="nama" id="nama" >
            </div>
            @error('nama')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror

            <label class="alamat">Alamat</label>
            <div class="input-group input-group-outline @error('alamat') is-invalid @enderror">
                <input class="form-control" type="text" name="alamat" id="alamat" >
            </div>
            @error('alamat')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror

            <label for="nomor_wa">No Wa</label>
            <div class="input-group input-group-outline @error('nomor_wa') is-invalid @enderror">
                <input class="form-control" type="number" name="nomor_wa" id="nomor_wa" >
            </div>
            @error('nomor_wa')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror

            <label for="pekerjaan">pekerjaan</label>
            <div class="input-group input-group-outline @error('pekerjaan') is-invalid @enderror">
                <input class="form-control" type="text" name="pekerjaan" id="pekerjaan" >
            </div>
            @error('pekerjaan')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror


            <div class="text-center mt-3">
                <button type="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

  {{-- Modal Edit Saksi --}}
  @foreach ($saksis as $saksi)
  <div class="modal fade" id="editModal{{ $saksi->id }}" tabindex="-1" aria-labelledby="saksiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="jaksaModalLabel">Edit Data Saksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('saksi.update', $saksi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="nama">Nama Saksi</label>
            <div class="input-group input-group-outline @error('nama') is-invalid @enderror">
                <input class="form-control" type="text" name="nama" id="nama" required value="{{ old('nama', $saksi->nama) }}">
            </div>
            @error('nama')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror
  
            <label for="alamat">Alamat</label>
            <div class="input-group input-group-outline @error('alamat') is-invalid @enderror">
                <input class="form-control" type="text" name="alamat" id="alamat" required value="{{ old('alamat', $saksi->alamat) }}">
            </div>
            @error('alamat')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror
  
            <label for="nomor_wa">No Wa</label>
            <div class="input-group input-group-outline @error('nomor_wa') is-invalid @enderror">
                <input class="form-control" type="number" name="nomor_wa" id="nomor_wa" required value="{{ old('nomor_wa', $saksi->nomor_wa) }}">
            </div>
            @error('nomor_wa')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror
  
            <label for="jabatan">Pekerjaan</label>
            <div class="input-group input-group-outline @error('pekerjaan') is-invalid @enderror">
                <input class="form-control" type="text" name="pekerjaan" id="pekerjaan" required value="{{ old('pekerjaan', $saksi->pekerjaan) }}">
            </div>
            @error('pekerjaan')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror
  
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-info">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endforeach

  {{-- Modal Delete Data saksi --}}
  @foreach ($saksis as $saksi)
  <div class="modal fade" id="deleteModal{{ $saksi->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $saksi->id }}" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalLabel{{ $saksi->id }}">Hapus Data Saksi</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <p>Apakah Anda yakin ingin menghapus data saksi bernama <strong>{{ $saksi->nama }}</strong>?</p>
              </div>
              <div class="modal-footer">
                  <form action="{{ route('saksi.destroy', $saksi->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-danger">Hapus</button>
                  </form>
              </div>
          </div>
      </div>
  </div>
  @endforeach
  

@endsection


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@if (session('inputModal'))
<script>
    $(document).ready(function() {
        $('#inputModal').modal('show');
    });
</script>
@endif

@if (session('editModal'))
<script>
    $(document).ready(function() {
        var editModalId = {{ session('editModal') }};
        $('#editModal' + editModalId).modal('show');
    });
</script>
@endif

<script>
    $(document).ready(function(){
        // Check if the alert exists
        if ($('#alertContainer').length) {
            // Set timeout to hide the alert after 2 seconds
            setTimeout(function(){
                $('#alertContainer').fadeOut('slow', function(){
                    $(this).remove();
                });
            }, 1500);
        }
    });
</script>