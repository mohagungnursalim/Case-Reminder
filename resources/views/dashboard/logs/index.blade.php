@extends('dashboard.layouts.main')
@section('judul-halaman')
Log Status
@endsection

@section('konten')

<head>
    <style>
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
            color: #b9770e; 
        }
        .danger { 
            border: none;
            background-color: #fba8a8; 
            color: #b90e0e; }

    </style>
</head>
{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">
        
        <div class="container">
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
                <button type="button" class="btn btn-sm bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <span class="material-symbols-outlined text-white">
                        delete
                        </span>
                </button>
              <a href="/dashboard/logs" class="btn btn-dark"><span class="material-symbols-outlined" id="refresh">refresh</span>Refresh</a>
            <div class="overflow-auto">
                @if (count($messages) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-wrap small">Direction</th>
                        <th class="text-wrap small">From</th>
                        <th class="text-wrap small">To</th>
                        <th class="text-wrap small">Status</th>
                        <th class="text-wrap small">Date Sent (Waktu Server)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td class="text-wrap small">
                                @if ($message['direction'] == 'inbound')
                                    Pesan Masuk
                                @elseif ($message['direction'] == 'outbound-api')
                                    Pesan keluar(Dikirim)
                                @endif
                            </td>
                            <td class="text-wrap small">{{ $message['from'] }}</td>
                            <td class="text-wrap small">{{ $message['to'] }}</td>
                            <td class="text-wrap small">
                                @if ($message['status'] == 'read') 
                                <button class="badge success">
                                    Dibaca 
                                </button>
                                @elseif ($message['status'] == 'delivered') 
                                <button class="badge secondary">
                                    Terkirim
                                </button>
                                @elseif ($message['status'] == 'received')
                                <button class="badge info">
                                    Diterima
                                </button>
                                @elseif ($message['status'] == 'failed')
                                <button class="badge danger">
                                    Gagal Terkirim
                                </button>
                                @endif 
                            </td>
                            <td class="text-wrap small">{{ $message['date_sent'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
          
        @else
            <p>Tidak Ada Pesan.</p>
        @endif
            </div>
            <div class="text-center mt-3">
                {{ $messagesPaginated->links() }} {{-- Menampilkan link halaman paginasi --}}
            </div>
        </div>




    </div>

</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Hapus Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold">Apakah Anda yakin ingin menghapus semua data history pengiriman pesan? Data akan terhapus secara permanen!</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('delete-all') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

