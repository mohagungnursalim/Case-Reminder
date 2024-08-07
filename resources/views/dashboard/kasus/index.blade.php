@extends('dashboard.layouts.main')
@section('judul-halaman')
Kasus
@endsection

@section('konten')

<head>
    <style>
        #refresh {
            font-size: 17px;
            /* Ubah ukuran sesuai kebutuhan Anda */
        }

    </style>
    <style>
        .info {
            border: none;
            background-color: #85c1e9;
            /* Ganti dengan warna latar belakang yang Anda inginkan */
            color: #0056b3;
            /* Ganti dengan warna teks yang Anda inginkan */
        }

        .success {
            border: none;
            background-color: #a3e4a9;
            color: #196f3d;
            /* Ganti dengan warna teks yang Anda inginkan */
        }

        .secondary {
            border: none;
            background-color: #d2d0d0;
            color: #595959;
            /* Ganti dengan warna teks yang Anda inginkan */
        }

    </style>
</head>
{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">

        <div class="container">
            <form action="{{ route('kasus.index') }}" method="GET">
                <div class="container">
                    <div class="row justify-content-end">
                        <div class="col-lg-4">
                            <div class="input-group input-group-outline my-3">

                                <input type="text" name="search" value="{{request('search')}}"
                                    placeholder="Cari Kasus.." class="form-control">
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

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModal">
                Tambah Kasus
            </button>
            <a href="/dashboard/kasus" class="btn btn-dark"><span class="material-symbols-outlined"
                    id="refresh">refresh</span>Refresh</a>
            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th class="text-wrap small">No</th>
                        <th class="text-wrap small">Kasus</th>
                        @if (Auth::user()->email === 'mohagungnursalim@gmail.com')
                        @can('is_admin')
                        <th class="text-wrap small">Lokasi</th>
                        @endcan
                        @endif
                        <th class="text-wrap small">Ditambahkan</th>
                        <th class="text-wrap small">Aksi</th>

                    </tr>

                    @if ($kasuss->count())
                    @foreach ($kasuss as $kasus )
                    <tr>
                        <td class="text-wrap small">{{ ($kasuss->currentPage() - 1) * $kasuss->perPage() + $loop->iteration }}</td>
                        <td class="text-wrap small">{{ $kasus->nama }}</td>
                        @if (Auth::user()->email === 'mohagungnursalim@gmail.com')
                        @can('is_admin')
                        <td class="small">{{ $kasus->lokasi }}</td>
                        @endcan
                        @endif
                        <td class="text-wrap small">{{ $kasus->created_at->format('d-m-Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm bg-warning" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $kasus->id }}">
                                <span class="material-symbols-outlined text-white">
                                    edit_square
                                </span>
                            </button>
                            <button type="button" class="btn btn-sm bg-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $kasus->id }}">
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
                            Tidak ada data kasus..
                            @if (request('search'))
                            <kbd>{{ request('search') }}</kbd>
                            @endif
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="d-flex justify-content-center text-center mt-3">
                {{ $kasuss->links() }}
            </div>



        </div>

    </div>

</div>

{{-- Modal input Kasus --}}
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="KasusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Kasus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kasus.store') }}" method="POST">
                    @csrf

                    @if (Auth::user()->email == 'mohagungnursalim@gmail.com')
                    <label class="form-label">Tetapkan Lokasi</label>
                    <div class="input-group input-group-outline @error('lokasi') is-invalid @enderror mb-1">
                        <select required id="lokasi" name="lokasi" style="width: 100%;" required class="form-control">
                            <option value="">-Pilih Lokasi-</option>
                            <option value="Kejati Sulteng">Kejati Sulteng</option>
                            <option value="Kejari Palu">Kejari Palu</option>
                            <option value="Kejari Poso">Kejari Poso</option>
                            <option value="Kejari Tolitoli">Kejari Tolitoli</option>
                            <option value="Kejari Banggai">Kejari Banggai</option>
                            <option value="Kejari Parigi">Kejari Parigi</option>
                            <option value="Kejari Donggala">Kejari Donggala</option>
                            <option value="Kejari Buol">Kejari Buol</option>
                            <option value="Kejari Morowali">Kejari Morowali</option>
                        </select>
                    </div>
                    @error('lokasi')
                    <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    @elseif (Auth::user()->is_admin == true)
                    <input type="hidden" name="kejari_nama" value="{{ Auth::user()->kejari_nama }}">
                    @endif

                    <label for="nama">Kasus</label>
                    <div class="input-group input-group-outline @error('nama') is-invalid @enderror">
                        <input required class="form-control" type="text" name="nama" id="nama">
                    </div>
                    @error('nama')
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

{{-- Modal Edit Kasus --}}
@foreach ($kasuss as $kasus)
<div class="modal fade" id="editModal{{ $kasus->id }}" tabindex="-1" aria-labelledby="kasusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="KasusModalLabel">Edit Data Kasus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kasus.update', $kasus->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label for="lokasi">Lokasi</label>
                    <div class="input-group input-group-outline @error('lokasi') is-invalid @enderror">
                        <select required class="form-control" name="lokasi" id="lokasi">
                            <option value="">--Pilih Lokasi--</option>
                            <option value="Kejati Sulteng"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejati Sulteng' ? 'selected' : '' }}>Kejati Sulteng</option>
                            <option value="Kejari Palu"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejari Palu' ? 'selected' : '' }}>Kejari Palu</option>
                            <option value="Kejari Poso"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejari Poso' ? 'selected' : '' }}>Kejari Poso</option>
                            <option value="Kejari Tolitoli"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejari Tolitoli' ? 'selected' : '' }}>Kejari Tolitoli</option>
                            <option value="Kejari Banggai"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejari Banggai' ? 'selected' : '' }}>Kejari Banggai</option>
                            <option value="Kejari Parigi"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejari Parigi' ? 'selected' : '' }}>Kejari Parigi</option>
                            <option value="Kejari Donggala"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejari Donggala' ? 'selected' : '' }}>Kejari Donggala</option>
                            <option value="Kejari Buol"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejari Buol' ? 'selected' : '' }}>Kejari Buol</option>
                            <option value="Kejari Morowali"
                                {{ old('lokasi', $kasus->lokasi) == 'Kejari Morowali' ? 'selected' : '' }}>Kejari Morowali</option>
                        </select>
                    </div>
                    @error('lokasi')
                    <p class="text-danger"><small>*{{ $message }}</small></p>
                    @enderror

                    <label for="nama">Kasus</label>
                    <div class="input-group input-group-outline @error('nama') is-invalid @enderror">
                        <input required class="form-control" type="text" name="nama" id="nama" required
                            value="{{ old('nama', $kasus->nama) }}">
                    </div>
                    @error('nama')
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


{{-- Modal Delete Data kasus --}}
@foreach ($kasuss as $kasus)
<div class="modal fade" id="deleteModal{{ $kasus->id }}" tabindex="-1"
    aria-labelledby="deleteModalLabel{{ $kasus->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $kasus->id }}">Hapus Data Kasus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data kasus <strong>{{ $kasus->nama }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('kasus.destroy', $kasus->id) }}" method="POST">
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
    $(document).ready(function () {
        $('#inputModal').modal('show');
    });

</script>
@endif

@if (session('editModal'))
<script>
    $(document).ready(function () {
        var editModalId = {
            {
                session('editModal')
            }
        };
        $('#editModal' + editModalId).modal('show');
    });

</script>
@endif

<script>
    $(document).ready(function () {
        // Check if the alert exists
        if ($('#alertContainer').length) {
            // Set timeout to hide the alert after 2 seconds
            setTimeout(function () {
                $('#alertContainer').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, 1500);
        }
    });

</script>

<audio id="success-audio" src="{{ asset('assets/audio/send.mp3') }}" preload="auto"></audio>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cek jika ada pesan sukses di session
        @if(session('success'))
        // Dapatkan elemen audio
        var audio = document.getElementById('success-audio');
        // Putar audio
        audio.play();
        @endif
    });

</script>