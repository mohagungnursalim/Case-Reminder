@extends('dashboard.layouts.main')
@section('judul-halaman')
Agenda
@endsection

@section('konten')

<head>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
</head>

{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">
        
        <div class="container">
            <form action="{{ route('agenda.index') }}" method="GET">
                <div class="container">
                  <div class="row justify-content-end">
                      <div class="col-lg-4">
                          <div class="input-group input-group-outline my-3">

                                  <input type="text" name="search" value="{{request('search')}}" placeholder="Cari Agenda.." class="form-control">
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
              <a href="/dashboard/agenda/create" class="btn btn-info">Tambah Agenda+</a>
            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th class="text-wrap small">No</th>
                        <th class="text-wrap small">Nama Jaksa</th>
                        <th class="text-wrap small">No WA</th>
                        <th class="text-wrap small">Nama Kasus</th>
                        <th class="text-wrap small">Nama Saksi</th>
                        <th class="text-wrap small">Pesan</th>
                        <th class="text-wrap small">Tanggal & Waktu Pelaksanaan</th>
                        <th class="text-wrap small">Dibuat</th>
                        <th class="text-wrap small">Aksi</th>

                    </tr>

                    @if ($reminders->count())
                    @foreach ($reminders as $reminder )
                    <tr>
                        <td class="text-wrap small">{{ $loop->iteration }}</td>
                        <td class="text-wrap small">
                            @foreach(json_decode($reminder->nama_jaksa) as $nama_jaksa)
                                <button type="button" class="badge bg-secondary mt-1" style="border:none">
                                    {{ $nama_jaksa }}
                                </button>
                            @endforeach
                        </td>
                        <td class="text-wrap small">
                            @foreach(json_decode($reminder->nomor_jaksa) as $nomor_jaksa)
                            <button type="button" class="badge bg-secondary mt-1" style="border:none">
                                {{ $nomor_jaksa }}
                            </button>
                            @endforeach
                        </td>
                        <td class="text-wrap small">{{ $reminder->nama_kasus }}</td>
                        <td class="text-wrap small">
                            @foreach(json_decode($reminder->nama_saksi) as $nama_saksi)
                            <button type="button" class="badge bg-secondary mt-1" style="border:none">
                                {{ $nama_saksi }}
                            </button>
                            @endforeach
                        </td>
                        <td class="text-wrap small">{{ $reminder->pesan }}</td>
                        <td class="text-wrap small">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold">Tgl:</span>
                                <span>{{ $reminder->tanggal_waktu->format('d-m-Y') }}</span>
                            </div>
                            <div class="d-flex flex-column mt-1">
                                <span class="font-weight-bold">Pukul:</span>
                                <span>{{ $reminder->tanggal_waktu->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="text-wrap small">{{ ($reminder->created_at)->format('d-m-Y') }}</td>

                        <td>
                            <button type="button" class="btn btn-sm bg-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $reminder->id }}">
                                <span class="material-symbols-outlined text-white">
                                    edit_square
                                </span>
                            </button>
                            <button type="button" class="btn btn-sm bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $reminder->id }}">
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
                                Tidak ada agenda sidang
                                @if (request('search'))
                                <kbd>{{ request('search') }}</kbd>                
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $reminders->links() }}
            </div>



        </div>




    </div>

</div>

@endsection






{{-- Modal Edit Agenda --}}
@foreach ($reminders as $reminder)
<div class="modal fade" id="editModal{{ $reminder->id }}" tabindex="-1" aria-labelledby="reminderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jaksaModalLabel">Edit Data Agenda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('agenda.update', $reminder->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <label class="form-label">Nama Jaksa</label>
            <div class="input-group input-group-outline @error('nama_jaksa') is-invalid @enderror mb-1">
                <select id="nama_jaksa" name="nama_jaksa[]" style="width: 100%;" multiple class="form-control">
                    @foreach($jaksas as $jaksa)
                        <option value="{{ $jaksa->nama }}" {{ in_array($jaksa->nama, (array)$reminder->nama_jaksa) ? 'selected' : '' }}>
                            {{ $jaksa->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('nama_jaksa')
                <p class="text-bold text-xs text-danger">{{ $message }}</p>
            @enderror
        
            <label class="form-label">Nomor Jaksa</label>
            <div class="input-group input-group-outline @error('nomor_jaksa') is-invalid @enderror mb-1">
                <select id="nomor_jaksa" name="nomor_jaksa[]" style="width: 100%;" multiple class="form-control">
                    @foreach($jaksas as $jaksa)
                        <option value="{{ $jaksa->nomor_wa }}" {{ in_array($jaksa->nomor_wa, (array)$reminder->nomor_jaksa) ? 'selected' : '' }}>
                            {{ $jaksa->nama }} ({{ $jaksa->nomor_wa }})
                        </option>
                    @endforeach
                </select>
            </div>
            @error('nomor_jaksa')
                <p class="text-bold text-xs text-danger">{{ $message }}</p>
            @enderror
        
            <label for="case_name">Nama Kasus:</label>
            <div class="input-group input-group-outline @error('nama_kasus') is-invalid @enderror">
                <input class="form-control" type="text" name="nama_kasus" id="case_name" value="{{ $reminder->nama_kasus }}" required>
            </div>
            @error('nama_kasus')
                <p class="text-bold text-xs text-danger">{{ $message }}</p>
            @enderror
        
            <label class="form-label">Nama Saksi</label>
            <div class="input-group input-group-outline @error('nama_saksi') is-invalid @enderror mb-1">
                <select id="nama_saksi" name="nama_saksi[]" style="width: 100%;" multiple class="form-control">
                    @foreach($saksis as $saksi)
                        <option value="{{ $saksi->nama }}" {{ in_array($saksi->nama, (array)$reminder->nama_saksi) ? 'selected' : '' }}>
                            {{ $saksi->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('nama_saksi')
                <p class="text-bold text-xs text-danger">{{ $message }}</p>
            @enderror
        
            <label for="pesan">Pesan:</label>
            <div class="input-group input-group-outline @error('pesan') is-invalid @enderror">
                <textarea class="form-control" rows="5" name="pesan" id="pesan" required>{{ $reminder->pesan }}</textarea>
            </div>
            @error('pesan')
                <p class="text-bold text-xs text-danger">{{ $message }}</p>
            @enderror
        
            <label for="tanggal_waktu">Tanggal & Waktu:</label>
            <div class="input-group input-group-outline @error('tanggal_waktu') is-invalid @enderror">
                <input class="form-control" type="datetime-local" name="tanggal_waktu" id="tanggal_waktu" value="{{ date('Y-m-d\TH:i', strtotime($reminder->tanggal_waktu)) }}" required>
            </div>
            @error('tanggal_waktu')
                <p class="text-bold text-xs text-danger">{{ $message }}</p>
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


{{-- Modal Delete Data Agenda --}}
@foreach ($reminders as $jaksa)
<div class="modal fade" id="deleteModal{{ $reminder->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $reminder->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Agenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus agenda pada kasus <strong>{{ $reminder->nama_kasus }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('agenda.destroy', $reminder->id) }}" method="POST">
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

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#nama_jaksa').select2({
            allowClear: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#nomor_jaksa').select2({
            allowClear: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#nama_saksi').select2({
            allowClear: true
        });
    });
</script>

{{-- Menghilangkan alert --}}
<script>
    $(document).ready(function() {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function() {
            $('#alertContainer').fadeOut('slow');
        }, 1200);
    });
  </script>