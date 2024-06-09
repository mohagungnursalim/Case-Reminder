@extends('dashboard.layouts.main')
@section('judul-halaman')
Jaksa
@endsection

@section('konten')

<head>
    <style>
        #refresh {
            font-size: 17px; /* Ubah ukuran sesuai kebutuhan Anda */
        }
    </style>
</head>
{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">
        
        <div class="container">
            <form action="{{ route('jaksa.index') }}" method="GET">
                <div class="container">
                  <div class="row justify-content-end">
                      <div class="col-lg-4">
                          <div class="input-group input-group-outline my-3">

                                  <input type="text" name="search" value="{{request('search')}}" placeholder="Cari Jaksa.." class="form-control">
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
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModal">
                Tambah Jaksa
              </button>
              <a href="/dashboard/jaksa" class="btn btn-dark"><span class="material-symbols-outlined" id="refresh">refresh</span>Refresh</a>
            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Jaksa</th>
                        <th>Alamat</th>
                        <th>No Wa</th>
                        <th>Jabatan</th>
                        <th>Ditambahkan</th>
                        <th>Aksi</th>

                    </tr>

                    @if ($jaksas->count())
                    @foreach ($jaksas as $jaksa )
                    <tr>
                        <td class="text-wrap small">{{ $loop->iteration }}</td>
                        <td class="small">{{ $jaksa->nama }}</td>
                        <td class="text-wrap small">{{ $jaksa->alamat}}</td>
                        <td class="text-wrap small">{{ $jaksa->nomor_wa }}</td>
                        <td class="text-wrap small">{{ $jaksa->jabatan }}</td>
                        <td class="text-wrap small">{{ $jaksa->created_at->format('d-m-Y') }}</td>

                        <td>
                            <button type="button" class="btn btn-sm bg-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $jaksa->id }}">
                                <span class="material-symbols-outlined text-white">
                                    edit_square
                                </span>
                            </button>
                            <button type="button" class="btn btn-sm bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $jaksa->id }}">
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
                                Tidak ada data Jaksa..
                                @if (request('search'))
                                <kbd>{{ request('search') }}</kbd>                
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $jaksas->links() }}
            </div>



        </div>




    </div>

</div>

{{-- Modal input Jaksa --}}
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="jaksaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Form Tambah Jaksa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('jaksa.store') }}" method="POST">
            @csrf
            <label for="nama">Nama Jaksa</label>
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
                <input class="form-control" type="text" name="nomor_wa" id="nomor_wa" >
            </div>
            @error('nomor_wa')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror

            <label for="jabatan">Jabatan</label>
            <div class="input-group input-group-outline @error('jabatan') is-invalid @enderror">
                <select class="form-control" name="jabatan" id="jabatan">
                    <option value="">--Pilih Jabatan--</option>
                    <option value="Ajun Jaksa Madya">Ajun Jaksa Madya</option>
                    <option value="Ajun Jaksa">Ajun Jaksa</option>
                    <option value="Jaksa Pratama">Jaksa Pratama</option>
                    <option value="Jaksa Muda">Jaksa Muda</option>
                    <option value="Jaksa Madya">Jaksa Madya</option>
                    <option value="Jaksa Utama Pratama">Jaksa Utama Pratama</option>
                    <option value="Jaksa Utama Muda">Jaksa Utama Muda</option>
                    <option value="Jaksa Utama Madya">Jaksa Utama Madya</option>
                </select>
            </div>
            @error('jabatan')
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

  {{-- Modal Edit Jaksa --}}
  @foreach ($jaksas as $jaksa)
  <div class="modal fade" id="editModal{{ $jaksa->id }}" tabindex="-1" aria-labelledby="jaksaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="jaksaModalLabel">Edit Data Jaksa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('jaksa.update', $jaksa->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="nama">Nama Jaksa</label>
            <div class="input-group input-group-outline @error('nama') is-invalid @enderror">
                <input class="form-control" type="text" name="nama" id="nama" value="{{ old('nama', $jaksa->nama) }}" required>
            </div>
            @error('nama')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror
  
            <label for="alamat">Alamat</label>
            <div class="input-group input-group-outline @error('alamat') is-invalid @enderror">
                <input class="form-control" type="text" name="alamat" id="alamat" value="{{ old('alamat', $jaksa->alamat) }}" required>
            </div>
            @error('alamat')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror
  
            <label for="nomor_wa">No Wa</label>
            <div class="input-group input-group-outline @error('nomor_wa') is-invalid @enderror">
                <input class="form-control" type="text" name="nomor_wa" id="nomor_wa" value="{{ old('nomor_wa', $jaksa->nomor_wa) }}" required>
            </div>
            @error('nomor_wa')
                <p class="text-danger"><small>*{{ $message }}</small></p>
            @enderror
  
            <label for="jabatan">Jabatan</label>
            <div class="input-group input-group-outline @error('jabatan') is-invalid @enderror">
                <select required class="form-control" name="jabatan" id="jabatan">
                    <option>--Pilih Jabatan--</option>
                    <option value="Ajun Jaksa Madya" {{ old('jabatan', $jaksa->jabatan) == 'Ajun Jaksa Madya' ? 'selected' : '' }}>Ajun Jaksa Madya</option>
                    <option value="Ajun Jaksa" {{ old('jabatan', $jaksa->jabatan) == 'Ajun Jaksa' ? 'selected' : '' }}>Ajun Jaksa</option>
                    <option value="Jaksa Pratama" {{ old('jabatan', $jaksa->jabatan) == 'Jaksa Pratama' ? 'selected' : '' }}>Jaksa Pratama</option>
                    <option value="Jaksa Muda" {{ old('jabatan', $jaksa->jabatan) == 'Jaksa Muda' ? 'selected' : '' }}>Jaksa Muda</option>
                    <option value="Jaksa Madya" {{ old('jabatan', $jaksa->jabatan) == 'Jaksa Madya' ? 'selected' : '' }}>Jaksa Madya</option>
                    <option value="Jaksa Utama Pratama" {{ old('jabatan', $jaksa->jabatan) == 'Jaksa Utama Pratama' ? 'selected' : '' }}>Jaksa Utama Pratama</option>
                    <option value="Jaksa Utama Muda" {{ old('jabatan', $jaksa->jabatan) == 'Jaksa Utama Muda' ? 'selected' : '' }}>Jaksa Utama Muda</option>
                    <option value="Jaksa Utama Madya" {{ old('jabatan', $jaksa->jabatan) == 'Jaksa Utama Madya' ? 'selected' : '' }}>Jaksa Utama Madya</option>
                </select>
            </div>
            @error('jabatan')
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

  {{-- Modal Delete Data Jaksa --}}
  @foreach ($jaksas as $jaksa)
  <div class="modal fade" id="deleteModal{{ $jaksa->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $jaksa->id }}" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalLabel{{ $jaksa->id }}">Hapus Data Jaksa</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <p>Apakah Anda yakin ingin menghapus data jaksa bernama <strong>{{ $jaksa->nama }}</strong>?</p>
              </div>
              <div class="modal-footer">
                  <form action="{{ route('jaksa.destroy', $jaksa->id) }}" method="POST">
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