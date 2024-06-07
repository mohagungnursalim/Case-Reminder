@extends('dashboard.layouts.main')

@section('judul-halaman')
Agenda
@endsection

@section('create-halaman')
Tambah
@endsection

@section('konten')

{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">
        <a href="/dashboard/agenda" class="btn btn-secondary">
            <-Kembali</a> 
    <div class="container">
                <form action="{{ route('agenda.store') }}" method="POST">
                    @csrf
                    <label class="form-label">Nama Jaksa</label>
                    <div class="input-group input-group-outline @error('judul_buku') is-invalid @enderror mb-1">
                        <select id="nama_jaksa" name="nama_jaksa[]" style="width: 100%;" multiple class="form-control">
                            @foreach($jaksas as $jaksa)
                                <option value="{{ $jaksa->nama }}">{{ $jaksa->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nama_jaksa')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Nomor Jaksa</label>
                    <div class="input-group input-group-outline @error('judul_buku') is-invalid @enderror mb-1">
                        <select id="nomor_jaksa" name="nomor_jaksa[]" style="width: 100%;" multiple class="form-control">
                            @foreach($jaksas as $jaksa)
                                <option value="{{ $jaksa->nomor_wa }}">{{ $jaksa->nama }} ({{ $jaksa->nomor_wa }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nomor_jaksa')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label for="case_name">Nama Kasus:</label>
                    <div class="input-group input-group-outline @error('case_name') is-invalid @enderror">
                        <input class="form-control" type="text" name="nama_kasus" id="case_name" required>
                    </div>
                    @error('nama_kasus')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Nama Saksi</label>
                    <div class="input-group input-group-outline @error('judul_buku') is-invalid @enderror mb-1">
                        <select id="nama_saksi" name="nama_saksi[]" style="width: 100%;" multiple class="form-control">
                            @foreach($saksis as $saksi)
                                <option value="{{ $saksi->nama }}">{{ $saksi->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nama_saksi')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label for="pesan">Pesan:</label>
                    <div class="input-group input-group-outline @error('pesan') is-invalid @enderror">
                        <textarea class="form-control" rows="5" name="pesan" id="pesan" required></textarea>
                    </div>
                    @error('pesan')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label for="tanggal_waktu">Tanggal & Waktu:</label>
                    <div class="input-group input-group-outline @error('tanggal_waktu') is-invalid @enderror">
                        <input class="form-control" type="datetime-local" name="tanggal_waktu" id="tanggal_waktu"
                            required>
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

@endsection
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
    $(document).ready(function () {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function () {
            $('#alertContainer').fadeOut('slow');
        }, 1200);
    });
</script>

