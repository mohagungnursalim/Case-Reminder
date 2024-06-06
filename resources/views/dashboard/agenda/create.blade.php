@extends('dashboard.layouts.main')

@section('judul-halaman')
Agenda
@endsection

@section('create-halaman')
Tambah
@endsection

@section('konten')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">
        <a href="/dashboard/agenda" class="btn btn-secondary">
            <-Kembali</a> 
    <div class="container">
                <form action="{{ route('agenda.store') }}" method="POST">
                    @csrf
                    <label for="prosecutor_name">Nama Jaksa:</label>
                    <div class="input-group input-group-outline @error('prosecutor_name') is-invalid @enderror">
                        <input class="form-control" type="text" name="prosecutor_name" id="prosecutor_name" required>
                    </div>

                    <label class="phone_number">No WA</label>
                    <div class="input-group input-group-outline @error('phone_number') is-invalid @enderror">
                        <input class="form-control" type="text" name="phone_number" id="phone_number" required>
                    </div>

                    <label for="case_name">Nama Kasus:</label>
                    <div class="input-group input-group-outline @error('case_name') is-invalid @enderror">
                        <input class="form-control" type="text" name="case_name" id="case_name" required>
                    </div>

                    <label for="witnesses">Nama Saksi:</label>
                    <div id="witnesses-wrapper" class="input-group input-group-outline @error('witnesses') is-invalid @enderror">
                        <input class="form-control" type="text" name="witnesses" required>
                    </div>

                    <label for="message">Pesan:</label>
                    <div class="input-group input-group-outline @error('message') is-invalid @enderror">
                        <textarea class="form-control" rows="5" name="message" id="message" required></textarea>
                    </div>

                    <label for="scheduled_time">Tanggal & Waktu:</label>
                    <div class="input-group input-group-outline @error('scheduled_time') is-invalid @enderror">
                        <input class="form-control" type="datetime-local" name="scheduled_time" id="scheduled_time"
                            required>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-info">Simpan</button>
                    </div>
                </form>
    </div>
</div>
</div>

@endsection

{{-- Menghilangkan alert --}}
<script>
    $(document).ready(function () {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function () {
            $('#alertContainer').fadeOut('slow');
        }, 1200);
    });
</script>
