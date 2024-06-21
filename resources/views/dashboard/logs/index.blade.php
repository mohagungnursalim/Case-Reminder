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
                        <th>Direction</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Date Sent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>
                                @if ($message['direction'] == 'inbound')
                                    Pesan Masuk
                                @elseif ($message['direction'] == 'outbound-api')
                                    Pesan keluar(Dikirim)
                                @endif
                            </td>
                            <td>{{ $message['from'] }}</td>
                            <td>{{ $message['to'] }}</td>
                            <td>
                                @if ($message['status'] == 'read') 
                                Dibaca 
                                @elseif ($message['status'] == 'delivered') 
                                Terkirim
                                @elseif ($message['status'] == 'received')
                                Diterima
                                @elseif ($message['status'] == 'failed')
                                Gagal Terkirim
                                @endif 
                            </td>
                            <td>{{ $message['date_sent'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $messagesPaginated->links() }} {{-- Menampilkan link halaman paginasi --}}
            </div>
          
        @else
            <p>No messages found.</p>
        @endif
            </div>
            <div class="d-flex justify-content-center">
                {{-- {{ $jaksas->links() }} --}}
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

