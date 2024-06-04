@extends('dashboard.layouts.main')
@section('judul-halaman')
Buku
@endsection

@section('konten')
 <!-- Include CSS Select2 -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

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
                                    Tambah Buku
                                </button>
                            </div>

                            <form action="{{ route('buku.index') }}" method="GET">
                                <div class="container">
                                    <div class="row justify-content-end">
                                        <div class="col-lg-4">
                                            <div class="input-group input-group-outline my-3">

                                                <input type="text" name="search" placeholder="Cari Buku.."
                                                    class="form-control">
                                                <button type="submit">Search</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            @if (session('success'))
                            <div id="alertContainer" class="alert alert-success alert-dismissible text-white fade show"
                                role="alert">
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

                            @if ($bukus->count())
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kode Buku/ISBN
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Judul Buku
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kategori Buku
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Pengarang
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Penerbit
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tahun Terbit
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Jumlah
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Ditambahkan
                                        </th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bukus as $buku)

                                    <tr>

                                        <td class="text-wrap align-middle">
                                            <span
                                                class="text-xs font-weight-bold mb-0">ISBN {{$buku->kode_buku}}</span>
                                        </td>
                                        <td class="text-wrap align-middle">
                                            <span
                                                class="text-xs font-weight-bold mb-0">{{$buku->judul_buku}}</span>
                                        </td>
                                        <td class="text-wrap align-middle">
                                            @foreach ($buku->kategori as $kategori)
                                            <small><button class="badge bg-gradient-secondary mb-1" style="border: 0ch">{{$kategori->nama_kategori}}</button></small>
                                            @endforeach
                                        </td>
                                        <td class="text-wrap align-middle">
                                            <span
                                                class="text-xs font-weight-bold mb-0">{{$buku->pengarang}}</span>
                                        </td>
                                        <td class="text-wrap align-middle">
                                            <span
                                                class="text-xs font-weight-bold mb-0">{{$buku->penerbit}}</span>
                                        </td>
                                        <td class="text-wrap align-middle">
                                            <span
                                                class="text-xs font-weight-bold mb-0">{{$buku->tahun_terbit}}</span>
                                        </td>
                                        <td class="text-wrap align-middle">
                                            <span
                                                class="text-xs font-weight-bold mb-0">{{$buku->jumlah}} Buku</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{$buku->created_at->format('d/m/Y')}}</span>
                                        </td>
                                        <td class="align-middle">
                                            <button class="badge bg-gradient-warning" style="border: 0ch"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{$buku->id}}">Edit</button>
                                            <button class="badge bg-gradient-danger" style="border: 0ch"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{$buku->id}}">Delete</button>
                                        </td>

                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>

                            @else
                            <div class="container d-flex justify-content-center">
                                <h4 class="text-dark text-bold">Data tidak ditemukan! <span
                                        class="material-symbols-outlined">
                                        sentiment_dissatisfied
                                    </span></h4>
                            </div>
                            @endif


                            <div class="d-flex justify-content-center mb-3 mt-4">
                                {{ $bukus->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- Modal Tambah buku --}}
<div class="modal fade" id="inputModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Tambah Buku</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-4">
                    <form method="post" action="{{ route('buku.store') }}">
                        @csrf
                        <label class="form-label">Kode Buku/ISBN</label>
                        <div class="input-group input-group-outline @error('kode_buku') is-invalid @enderror mb-1">
                            <input type="text" name="kode_buku" value="{{old('kode_buku')}}"
                                class="form-control">
                        </div>
                        @error('kode_buku')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Judul Buku</label>
                        <div class="input-group input-group-outline @error('judul_buku') is-invalid @enderror mb-1">
                            <input type="text" name="judul_buku" value="{{old('judul_buku')}}"
                                class="form-control">
                        </div>
                        @error('judul_buku')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Kategori Buku</label>
                        <div class="input-group input-group-outline @error('judul_buku') is-invalid @enderror mb-1">
                              <select id="categories" name="kategori[]" style="width: 100%;" multiple class="form-control">
                                @foreach($kategories as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('kategori')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror
 
                        <label class="form-label">Pengarang</label>
                        <div class="input-group input-group-outline @error('pengarang') is-invalid @enderror mb-1">
                            <input type="text" name="pengarang" value="{{old('pengarang')}}"
                                class="form-control">
                        </div>
                        @error('pengarang')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Penerbit</label>
                        <div class="input-group input-group-outline @error('penerbit') is-invalid @enderror mb-1">
                            <input type="text" name="penerbit" value="{{old('penerbit')}}"
                                class="form-control">
                        </div>
                        @error('penerbit')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Tahun Terbit</label>
                        <div class="input-group input-group-outline @error('tahun_terbit') is-invalid @enderror mb-1">
                            <input type="year" name="tahun_terbit" value="{{old('tahun_terbit')}}"
                                class="form-control">
                        </div>
                        @error('tahun_terbit')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <label class="form-label">Jumlah</label>
                        <div class="input-group input-group-outline @error('jumlah') is-invalid @enderror mb-1">
                            <input type="text" name="jumlah" value="{{old('jumlah')}}"
                                class="form-control">
                        </div>
                        @error('jumlah')
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

{{-- Modal Edit buku --}}
@foreach ($bukus as $buku)
<div class="modal fade" id="editModal{{$buku->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Edit Buku</h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="p-4">
                <form method="post" action="{{ route('buku.update',$buku->id) }}">
                    @csrf
                    @method('put')
                    <label class="form-label">Kode Buku/ISBN</label>
                    <div class="input-group input-group-outline @error('kode_buku') is-invalid @enderror mb-1">
                        <input type="text" name="kode_buku" value="{{old('kode_buku',$buku->kode_buku)}}"
                            class="form-control">
                    </div>
                    @error('kode_buku')
                    <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Judul Buku</label>
                    <div class="input-group input-group-outline @error('judul_buku') is-invalid @enderror mb-1">
                        <input type="text" name="judul_buku" value="{{old('judul_buku',$buku->judul_buku)}}"
                            class="form-control">
                    </div>
                    @error('judul_buku')
                    <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Kategori Buku</label>
                    <div class="input-group input-group-outline @error('judul_buku') is-invalid @enderror mb-1">
                        <select id="" name="kategori[]" style="width: 100%;" multiple class="form-control categoriesedit">
                            @foreach($kategories as $availableKategori)
                                {{-- Cek apakah kategori saat ini sudah terpilih --}}
                                @php
                                    $isSelected = in_array($availableKategori->id, $buku->kategori->pluck('id')->toArray());
                                @endphp
                                <option value="{{ $availableKategori->id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $availableKategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('kategori')
                    <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Pengarang</label>
                    <div class="input-group input-group-outline @error('pengarang') is-invalid @enderror mb-1">
                        <input type="text" name="pengarang" value="{{old('pengarang', $buku->pengarang)}}"
                            class="form-control">
                    </div>
                    @error('pengarang')
                    <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Penerbit</label>
                    <div class="input-group input-group-outline @error('penerbit') is-invalid @enderror mb-1">
                        <input type="text" name="penerbit" value="{{old('penerbit',$buku->penerbit)}}"
                            class="form-control">
                    </div>
                    @error('penerbit')
                    <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Tahun Terbit</label>
                    <div class="input-group input-group-outline @error('tahun_terbit') is-invalid @enderror mb-1">
                        <input type="year" name="tahun_terbit" value="{{old('tahun_terbit',$buku->tahun_terbit)}}"
                            class="form-control">
                    </div>
                    @error('tahun_terbit')
                    <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Jumlah</label>
                    <div class="input-group input-group-outline @error('jumlah') is-invalid @enderror mb-1">
                        <input type="text" name="jumlah" value="{{old('jumlah',$buku->jumlah)}}"
                            class="form-control">
                    </div>
                    @error('jumlah')
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
@foreach ($bukus as $buku)
<div class="modal fade" id="deleteModal{{$buku->id}}" tabindex="-1" role="dialog"
aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Delete Data {{$buku->judul_buku}}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="p-4">
                <p class="text-dark">Dengan mengklik Delete,maka buku
                    <b>{{ $buku->judul_buku }}</b> akan terhapus secara permanen!</p>
            </div>
        </div>
        <form action="{{route('buku.destroy', $buku->id)}}" method="post">
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

 <!-- Include JavaScript Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script>
    // Inisialisasi Select2
    $(document).ready(function() {
        $('#categories').select2();
    });
</script>
<script>
    // Inisialisasi Select2
    $(document).ready(function() {
        $('.categoriesedit').select2();
    });
</script>

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
    $(document).ready(function () {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function () {
            $('#alertContainer').fadeOut('slow');
        }, 1200);
    });

</script>
