@extends('dashboard.layouts.main')
@section('judul-halaman')
Jaksa
@endsection

@section('konten')

<head>
    <style>
        #refresh {
            font-size: 17px;
            /* Ubah ukuran sesuai kebutuhan Anda */
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

                                <input type="text" name="search" value="{{request('search')}}"
                                    placeholder="Cari Jaksa.." class="form-control">
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

            {{-- Tombol Modal Input --}}
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#inputModal">
                Tambah Jaksa
            </button>
            <a href="/dashboard/jaksa" class="btn btn-dark btn-sm"><span class="material-symbols-outlined"
                    id="refresh">refresh</span>Refresh</a>
            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th class="text-wrap small">No</th>
                        <th class="text-wrap small">Nama Jaksa</th>
                        <th class="text-wrap small">No Wa</th>
                        <th class="text-wrap small">Pangkat</th>
                        @can('is_admin')
                        <th class="text-wrap small">Ditambahkan Oleh</th> 
                        @if (Auth::user()->email === 'mohagungnursalim@gmail.com')
                        <th class="text-wrap small">Lokasi</th>                
                        @endif
                        @endcan
                        <th class="text-wrap small">Dibuat</th>
                        <th class="text-wrap small">Aksi</th>

                    </tr>

                    @if ($jaksas->count())
                    @foreach ($jaksas as $jaksa )
                    <tr>
                        <td class="text-wrap small">{{ ($jaksas->currentPage() - 1) * $jaksas->perPage() + $loop->iteration }}</td>
                        <td class="small">{{ $jaksa->nama }}</td>
                        <td class="text-wrap small">{{ $jaksa->nomor_wa }}</td>
                        <td class="text-wrap small">{{ $jaksa->pangkat }}</td>
                        @can('is_admin')
                        <td class="small">{{ optional(optional($jaksa)->user)->name }}</td>
                        @if (Auth::user()->email === 'mohagungnursalim@gmail.com')
                        <td class="small">{{ $jaksa->lokasi }}</td>
                        @endif
                        @endcan
                        <td class="text-wrap small">{{ $jaksa->created_at->format('d-m-Y') }}</td>

                        <td>
                            <button type="button" class="btn btn-sm bg-warning" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $jaksa->id }}">
                                <span class="material-symbols-outlined text-white">
                                    edit_square
                                </span>
                            </button>
                            <button type="button" class="btn btn-sm bg-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $jaksa->id }}">
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
                            Tidak ada data jaksa..
                            @if (request('search'))
                            <kbd>{{ request('search') }}</kbd>
                            @endif
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="text-center mt-3">
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

                    <label for="nama">Nama Jaksa</label>
                    <div class="input-group input-group-outline @error('nama') is-invalid @enderror">
                        <input required class="form-control" type="text" name="nama" id="nama" placeholder="Masukan Nama..">
                    </div>
                    @error('nama')
                    <p class="text-danger"><small>*{{ $message }}</small></p>
                    @enderror

                    <label for="nomor_wa">No Wa</label>
                    <div class="input-group input-group-outline @error('nomor_wa') is-invalid @enderror">
                        <input required class="form-control" type="text" name="nomor_wa" id="nomor_wa"
                            placeholder="e.g.08575706xxxx">
                    </div>
                    @error('nomor_wa')
                    <p class="text-danger"><small>*{{ $message }}</small></p>
                    @enderror

                    <label for="pangkat">Pangkat</label>
                    <div class="input-group input-group-outline @error('pangkat') is-invalid @enderror">
                        <select required class="form-control" name="pangkat" id="pangkat">
                            <option value="">--Pilih Pangkat--</option>
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
                    @error('pangkat')
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
<div class="modal fade" id="editModal{{ $jaksa->id }}" tabindex="-1" aria-labelledby="jaksaModalLabel"
    aria-hidden="true">
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
                    <label for="lokasi">Lokasi</label>
                    <div class="input-group input-group-outline @error('lokasi') is-invalid @enderror">
                        <select required class="form-control" name="lokasi" id="lokasi">
                            <option value="">--Pilih Lokasi--</option>
                            <option value="Kejati Sulteng"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejati Sulteng' ? 'selected' : '' }}>Kejati Sulteng</option>
                            <option value="Kejari Palu"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejari Palu' ? 'selected' : '' }}>Kejari Palu</option>
                            <option value="Kejari Poso"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejari Poso' ? 'selected' : '' }}>Kejari Poso</option>
                            <option value="Kejari Tolitoli"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejari Tolitoli' ? 'selected' : '' }}>Kejari Tolitoli</option>
                            <option value="Kejari Banggai"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejari Banggai' ? 'selected' : '' }}>Kejari Banggai</option>
                            <option value="Kejari Parigi"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejari Parigi' ? 'selected' : '' }}>Kejari Parigi</option>
                            <option value="Kejari Donggala"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejari Donggala' ? 'selected' : '' }}>Kejari Donggala</option>
                            <option value="Kejari Buol"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejari Buol' ? 'selected' : '' }}>Kejari Buol</option>
                            <option value="Kejari Morowali"
                                {{ old('lokasi', $jaksa->lokasi) == 'Kejari Morowali' ? 'selected' : '' }}>Kejari Morowali</option>
                        </select>
                    </div>
                    @error('lokasi')
                    <p class="text-danger"><small>*{{ $message }}</small></p>
                    @enderror

                    <label for="nama">Nama Jaksa</label>
                    <div class="input-group input-group-outline @error('nama') is-invalid @enderror">
                        <input required class="form-control" type="text" name="nama" id="nama"
                            value="{{ old('nama', $jaksa->nama) }}" placeholder="Masukan Nama.." required>
                    </div>
                    @error('nama')
                    <p class="text-danger"><small>*{{ $message }}</small></p>
                    @enderror


                    <label for="nomor_wa">No Wa</label>
                    <div class="input-group input-group-outline @error('nomor_wa') is-invalid @enderror">
                        <input required class="form-control" type="number" inputmode="numeric" name="nomor_wa" id="nomor_wa"
                            value="{{ old('nomor_wa', $jaksa->nomor_wa) }}" placeholder="e.g.08575706xxxx" required>
                    </div>
                    @error('nomor_wa')
                    <p class="text-danger"><small>*{{ $message }}</small></p>
                    @enderror

                    <label for="pangkat">Pangkat</label>
                    <div class="input-group input-group-outline @error('pangkat') is-invalid @enderror">
                        <select required class="form-control" name="pangkat" id="pangkat">
                            <option>--Pilih Pangkat--</option>
                            <option value="Ajun Jaksa Madya"
                                {{ old('pangkat', $jaksa->pangkat) == 'Ajun Jaksa Madya' ? 'selected' : '' }}>Ajun Jaksa
                                Madya</option>
                            <option value="Ajun Jaksa"
                                {{ old('pangkat', $jaksa->pangkat) == 'Ajun Jaksa' ? 'selected' : '' }}>Ajun Jaksa
                            </option>
                            <option value="Jaksa Pratama"
                                {{ old('pangkat', $jaksa->pangkat) == 'Jaksa Pratama' ? 'selected' : '' }}>Jaksa Pratama
                            </option>
                            <option value="Jaksa Muda"
                                {{ old('pangkat', $jaksa->pangkat) == 'Jaksa Muda' ? 'selected' : '' }}>Jaksa Muda
                            </option>
                            <option value="Jaksa Madya"
                                {{ old('pangkat', $jaksa->pangkat) == 'Jaksa Madya' ? 'selected' : '' }}>Jaksa Madya
                            </option>
                            <option value="Jaksa Utama Pratama"
                                {{ old('pangkat', $jaksa->pangkat) == 'Jaksa Utama Pratama' ? 'selected' : '' }}>Jaksa
                                Utama Pratama</option>
                            <option value="Jaksa Utama Muda"
                                {{ old('pangkat', $jaksa->pangkat) == 'Jaksa Utama Muda' ? 'selected' : '' }}>Jaksa
                                Utama Muda</option>
                            <option value="Jaksa Utama Madya"
                                {{ old('pangkat', $jaksa->pangkat) == 'Jaksa Utama Madya' ? 'selected' : '' }}>Jaksa
                                Utama Madya</option>
                        </select>
                    </div>
                    @error('pangkat')
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
<div class="modal fade" id="deleteModal{{ $jaksa->id }}" tabindex="-1"
    aria-labelledby="deleteModalLabel{{ $jaksa->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $jaksa->id }}">Hapus Data Jaksa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data jaksa <strong>{{ $jaksa->nama }}</strong>?</p>
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