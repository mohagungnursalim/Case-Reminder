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
        <a href="/dashboard/agenda" class="btn btn-outline-secondary btn-sm">
            <-Kembali</a> 
    <div class="container">
                <form action="{{ route('agenda.store') }}" method="POST">
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

                    @endif

                    <label class="form-label">Kasus</label>
                    <div class="input-group input-group-outline @error('nama_kasus') is-invalid @enderror mb-1">
                        <select required id="nama_kasus" name="nama_kasus" style="width: 100%;" class="form-control">
                            <option value="" disabled selected>---Pilih Kasus---</option>
                            @foreach($kasuss as $kasus)
                                <option value="{{ $kasus->nama }}">{{ $kasus->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nama_kasus')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror
                    
                    <label class="form-label">Atasan</label>
                    <div class="input-group input-group-outline @error('nama_atasan') is-invalid @enderror mb-1">
                        <select required id="nama_atasan" name="nama_atasan[]" style="width: 100%;" multiple class="form-control">
                            @foreach($atasans as $atasan)
                                <option value="{{ $atasan->nama }}">{{ $atasan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nama_atasan')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Nomor Atasan</label>
                    <div class="input-group input-group-outline @error('nomor_atasan') is-invalid @enderror mb-1">
                        <select required id="nomor_atasan" name="nomor_atasan[]" style="width: 100%;" multiple class="form-control">
                            @foreach($atasans as $atasan)
                                <option value="{{ $atasan->nomor_wa }}">{{ $atasan->nama }} ({{ $atasan->nomor_wa }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nomor_atasan')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Jaksa</label>
                    <div class="input-group input-group-outline @error('nama_jaksa') is-invalid @enderror mb-1">
                        <select required id="nama_jaksa" name="nama_jaksa[]" style="width: 100%;" multiple class="form-control">
                            @foreach($jaksas as $jaksa)
                                <option value="{{ $jaksa->nama }}">{{ $jaksa->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nama_jaksa')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Nomor Jaksa</label>
                    <div class="input-group input-group-outline @error('nomor_jaksa') is-invalid @enderror mb-1">
                        <select required id="nomor_jaksa" name="nomor_jaksa[]" style="width: 100%;" multiple class="form-control">
                            @foreach($jaksas as $jaksa)
                                <option value="{{ $jaksa->nomor_wa }}">{{ $jaksa->nama }} ({{ $jaksa->nomor_wa }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nomor_jaksa')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Saksi</label>
                    <div class="input-group input-group-outline @error('judul_buku') is-invalid @enderror mb-1">
                        <select required id="nama_saksi" name="nama_saksi[]" style="width: 100%;" multiple class="form-control">
                            @foreach($saksis as $saksi)
                                <option value="{{ $saksi->nama }}">{{ $saksi->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nama_saksi')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label class="form-label">Nomor Saksi</label>
                    <div class="input-group input-group-outline @error('nomor_saksi') is-invalid @enderror mb-1">
                        <select required id="nomor_saksi" name="nomor_saksi[]" style="width: 100%;" multiple class="form-control">
                            @foreach($saksis as $saksi)
                                <option value="{{ $saksi->nomor_wa }}">{{ $saksi->nama }} ({{ $saksi->nomor_wa }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('nomor_saksi')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label for="pesan">Pesan:</label>
                    <div class="input-group input-group-outline @error('pesan') is-invalid @enderror">
                        <textarea required class="form-control" rows="5" name="pesan" id="pesan"></textarea>
                    </div>
                    @error('pesan')
                        <p class="text-bold text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <label for="tanggal_waktu">Tanggal & Waktu:</label>
                    <div class="input-group input-group-outline @error('tanggal_waktu') is-invalid @enderror">
                        <input required class="form-control" type="datetime-local" name="tanggal_waktu" id="tanggal_waktu">
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

@endsection
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
     $(document).ready(function() {
        $('#nama_atasan').select2({
            allowClear: true
        });
    });

    $(document).ready(function() {
        $('#nomor_atasan').select2({
            allowClear: true
        });
    });

    $(document).ready(function() {
        $('#nama_jaksa').select2({
            allowClear: true
        });
    });

    $(document).ready(function() {
        $('#nomor_jaksa').select2({
            allowClear: true
        });
    });

    $(document).ready(function() {
        $('#nama_saksi').select2({
            allowClear: true
        });
    });

    $(document).ready(function() {
        $('#nomor_saksi').select2({
            allowClear: true
        });
    });

    $(document).ready(function() {
        $('#nama_kasus').select2({
            placeholder: '---Pilih Kasus---',
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

