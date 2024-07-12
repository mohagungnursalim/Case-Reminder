@extends('dashboard.layouts.main')
@section('judul-halaman')
Agenda
@endsection

@section('konten')

<head>
    <link rel="icon" href="{{ asset('assets/favicon_io/favicon.ico') }}" type="image/x-icon">
    <style>
        .status {
            font-size: 12px; /* Ubah ukuran sesuai kebutuhan Anda */
        }

        #refresh {
            font-size: 17px; /* Ubah ukuran sesuai kebutuhan Anda */
        }
    </style>
    <style>
       .info {
            border: none;
            background-color: #85c1e9; /* Ganti dengan warna latar belakang yang Anda inginkan */
            color: #0056b3; /* Ganti dengan warna teks yang Anda inginkan */
        }
        .success {
            border: none;
            background-color: #a3e4a9; 
            color: #196f3d; /* Ganti dengan warna teks yang Anda inginkan */
        }
        .secondary {
            border: none;
            background-color: #d2d0d0; 
            color: #595959; /* Ganti dengan warna teks yang Anda inginkan */
        }
        .warning { 
            border: none;
            background-color: #fbdba8; 
            color: #b9770e; }
    </style>
    <style>
        .hidden-text {
            display: none;
        }
        .read-more-btn, .read-less-btn {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
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
              <a href="/dashboard/agenda/create" class="btn btn-primary">Tambah Agenda+</a>
              <a href="/dashboard/agenda" class="btn btn-dark"><span class="material-symbols-outlined" id="refresh">refresh</span>Refresh</a>
            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th class="text-wrap small">No</th>
                        <th class="text-wrap small">Atasan</th>
                        <th class="text-wrap small">No Atasan</th>
                        <th class="text-wrap small">Jaksa</th>
                        <th class="text-wrap small">No Jaksa</th>
                        <th class="text-wrap small">Kasus</th>
                        <th class="text-wrap small">Saksi</th>
                        <th class="text-wrap small">Pesan</th>
                        <th class="text-wrap small">Tanggal & Waktu Pelaksanaan</th>
                        <th class="text-wrap small">Status</th>
                        @can('is_admin') <th class="text-wrap small">Lokasi</th> @endcan
                        <th class="text-wrap small">Dibuat</th>
                        <th class="text-wrap small">Aksi</th>

                    </tr>

                    @if ($reminders->count())
                    @foreach ($reminders as $reminder )
                    <tr>
                        <td class="text-wrap small">{{ $loop->iteration }}</td>
                        <td class="text-wrap small">
                            @foreach(json_decode($reminder->nama_atasan) as $nama_atasan)
                                <button type="button" class="badge mt-1 info">
                                    {{ $nama_atasan }}
                                </button>
                            @endforeach
                        </td>
                        <td class="text-wrap small">
                            @foreach(json_decode($reminder->nomor_atasan) as $nomor_atasan)
                            <button type="button" class="badge bg-success mt-1" style="border:none">
                                {{ $nomor_atasan }}
                            </button>
                            @endforeach
                        </td>
                        <td class="text-wrap small">
                            @foreach(json_decode($reminder->nama_jaksa) as $nama_jaksa)
                                <button type="button" class="badge mt-1 info">
                                    {{ $nama_jaksa }}
                                </button>
                            @endforeach
                        </td>
                        <td class="text-wrap small">
                            @foreach(json_decode($reminder->nomor_jaksa) as $nomor_jaksa)
                            <button type="button" class="badge bg-success mt-1" style="border:none">
                                {{ $nomor_jaksa }}
                            </button>
                            @endforeach
                        </td>
                        <td class="text-wrap small">{{ $reminder->nama_kasus }}</td>
                        <td class="text-wrap small">
                            @foreach(json_decode($reminder->nama_saksi) as $nama_saksi)
                            <button type="button" class="badge warning mt-1" style="border:none">
                                {{ $nama_saksi }}
                            </button>
                            @endforeach
                        </td>
                        <td class="text-wrap small">
                            <span class="short-text">
                                {{ implode(' ', array_slice(explode(' ', $reminder->pesan), 0, 6)) }}
                                @if (str_word_count($reminder->pesan) > 6)
                                <span class="read-more-btn">Read more</span>
                                @endif
                            </span>
                            <span class="full-text hidden-text">
                                {{ $reminder->pesan }}
                                <span class="read-less-btn">Read less</span>
                            </span>
                        </td>
                        <td class="text-wrap small">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold">Tgl:</span>
                                <span>{{ $reminder->tanggal_waktu->format('d-m-Y') }}</span>
                            </div>
                            <div class="d-flex flex-column mt-1">
                                <span class="font-weight-bold">Pukul:</span>
                                <span>{{ $reminder->tanggal_waktu->format('H:i A') }}</span>
                            </div>
                        </td>
                        <td class="text-wrap small">
                           @if ($reminder->is_sent == true)
                            <button type="button" class="badge success mt-1" style="border:none">
                                <span class="material-symbols-outlined status">check</span> Terkirim
                            </button>
                            @else
                            <button type="button" class="badge secondary mt-1" style="border:none">
                                <span class="material-symbols-outlined status">schedule</span> Penjadwalan
                            </button>
                           @endif
                        </td>
                        @can('is_admin') <td class="small">{{ $reminder->lokasi }}</td> @endcan
                        <td class="small">{{ ($reminder->created_at)->format('d-m-Y') }}</td>

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
                            <td colspan="12" class="text-center">
                                Tidak ada agenda sidang
                                @if (request('search'))
                                <kbd>{{ request('search') }}</kbd>                
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="text-center mt-3">
                {{ $reminders->links() }}
            </div>

        </div>
    </div>

</div>

@endsection


{{-- Modal Edit Agenda --}}
@foreach ($reminders as $reminder)
    @php
        $selectedNamaAtasan = $reminder->nama_atasan ? json_decode($reminder->nama_atasan, true) : [];
        $selectedNomorAtasan = $reminder->nomor_atasan ? json_decode($reminder->nomor_atasan, true) : [];
        $selectedNamaJaksa = $reminder->nama_jaksa ? json_decode($reminder->nama_jaksa, true) : [];
        $selectedNomorJaksa = $reminder->nomor_jaksa ? json_decode($reminder->nomor_jaksa, true) : [];
        $selectedNamaSaksi = $reminder->nama_saksi ? json_decode($reminder->nama_saksi, true) : [];
    @endphp

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
                        
                        <label class="form-label">Atasan</label>
                        <div class="input-group input-group-outline @error('nama_atasan') is-invalid @enderror mb-1">
                            <select id="nama_atasan{{ $reminder->id }}" name="nama_atasan[]" style="width: 100%;" multiple class="form-control">
                                @foreach($atasans as $atasan)
                                    <option value="{{ $atasan->nama }}" {{ in_array($atasan->nama, $selectedNamaAtasan) ? 'selected' : '' }}>{{ $atasan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('nama_atasan')
                            <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Nomor Atasan</label>
                        <div class="input-group input-group-outline @error('nomor_atasan') is-invalid @enderror mb-1">
                            <select id="nomor_atasan{{ $reminder->id }}" name="nomor_atasan[]" style="width: 100%;" multiple class="form-control">
                                @foreach($atasans as $atasan)
                                    <option value="{{ $atasan->nomor_wa }}" {{ in_array($atasan->nomor_wa, $selectedNomorAtasan) ? 'selected' : '' }}>{{ $atasan->nama }} ({{ $atasan->nomor_wa }})</option>
                                @endforeach
                            </select>
                        </div>
                        @error('nomor_atasan')
                            <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Jaksa</label>
                        <div class="input-group input-group-outline @error('nama_jaksa') is-invalid @enderror mb-1">
                            <select id="nama_jaksa{{ $reminder->id }}" name="nama_jaksa[]" style="width: 100%;" multiple class="form-control">
                                @foreach($jaksas as $jaksa)
                                    <option value="{{ $jaksa->nama }}" {{ in_array($jaksa->nama, $selectedNamaJaksa) ? 'selected' : '' }}>{{ $jaksa->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('nama_jaksa')
                            <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Nomor Jaksa</label>
                        <div class="input-group input-group-outline @error('nomor_jaksa') is-invalid @enderror mb-1">
                            <select id="nomor_jaksa{{ $reminder->id }}" name="nomor_jaksa[]" style="width: 100%;" multiple class="form-control">
                                @foreach($jaksas as $jaksa)
                                    <option value="{{ $jaksa->nomor_wa }}" {{ in_array($jaksa->nomor_wa, $selectedNomorJaksa) ? 'selected' : '' }}>{{ $jaksa->nama }} ({{ $jaksa->nomor_wa }})</option>
                                @endforeach
                            </select>
                        </div>
                        @error('nomor_jaksa')
                            <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Kasus</label>
                        <div class="input-group input-group-outline @error('nama_kasus') is-invalid @enderror mb-1">
                            <select id="nama_kasus{{ $reminder->id }}" name="nama_kasus" style="width: 100%;" class="form-control">
                                <option value="" disabled>---Pilih Kasus---</option>
                                @foreach($kasuss as $kasus)
                                    <option value="{{ $kasus->nama }}" {{ $reminder->nama_kasus == $kasus->nama ? 'selected' : '' }}>{{ $kasus->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('nama_kasus')
                            <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Saksi</label>
                        <div class="input-group input-group-outline @error('nama_saksi') is-invalid @enderror mb-1">
                            <select id="nama_saksi{{ $reminder->id }}" name="nama_saksi[]" style="width: 100%;" multiple class="form-control">
                                @foreach($saksis as $saksi)
                                    <option value="{{ $saksi->nama }}" {{ in_array($saksi->nama, $selectedNamaSaksi) ? 'selected' : '' }}>{{ $saksi->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('nama_saksi')
                            <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label for="pesan">Pesan:</label>
                        <div class="input-group input-group-outline @error('pesan') is-invalid @enderror">
                            <textarea class="form-control" rows="5" name="pesan" id="pesan" required>{{ old('pesan', $reminder->pesan) }}</textarea>
                        </div>
                        @error('pesan')
                            <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label for="tanggal_waktu">Tanggal & Waktu:</label>
                        <div class="input-group input-group-outline @error('tanggal_waktu') is-invalid @enderror">
                            <input class="form-control" type="datetime-local" name="tanggal_waktu" id="tanggal_waktu"
                                value="{{ old('tanggal_waktu', date('Y-m-d\TH:i', strtotime($reminder->tanggal_waktu))) }}" required>
                        </div>
                        <p class="text-xs text-bold text-secondary mt-2">*PM Mulai dari 00.00 hingga 11.59 | AM Mulai dari 12.00 hingga 23.59</p>
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
@foreach ($reminders as $reminder)
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

@foreach ($reminders as $reminder)
<script>
    $(document).ready(function() {
        $('#nama_atasan{{ $reminder->id }}').select2({
            allowClear: true
        });

        $('#nomor_atasan{{ $reminder->id }}').select2({
            allowClear: true
        });

        $('#nama_jaksa{{ $reminder->id }}').select2({
            allowClear: true
        });

        $('#nomor_jaksa{{ $reminder->id }}').select2({
            allowClear: true
        });

        $('#nama_saksi{{ $reminder->id }}').select2({
            allowClear: true
        });

        $('#nama_kasus{{ $reminder->id }}').select2({
            placeholder: '---Pilih Kasus---',
            allowClear: true
        });
    });
</script>
@endforeach

{{-- Menghilangkan alert --}}
<script>
    $(document).ready(function() {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function() {
            $('#alertContainer').fadeOut('slow');
        }, 1200);
    });
  </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var readMoreButtons = document.querySelectorAll('.read-more-btn');
        var readLessButtons = document.querySelectorAll('.read-less-btn');

        readMoreButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var shortText = button.parentElement;
                var fullText = shortText.nextElementSibling;
                shortText.style.display = 'none';
                fullText.style.display = 'inline';
            });
        });

        readLessButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var fullText = button.parentElement;
                var shortText = fullText.previousElementSibling;
                fullText.style.display = 'none';
                shortText.style.display = 'inline';
            });
        });
    });
</script>