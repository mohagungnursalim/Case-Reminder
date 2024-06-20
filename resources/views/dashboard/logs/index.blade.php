@extends('dashboard.layouts.main')
@section('judul-halaman')
Logs
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
              <a href="/dashboard/logs" class="btn btn-dark"><span class="material-symbols-outlined" id="refresh">refresh</span>Refresh</a>
            <div class="overflow-auto">
                <pre>
                    @foreach($logs as $log)
                        {{ $log }}<br>
                    @endforeach
                </pre>
            </div>
            <div class="d-flex justify-content-center">
                {{-- {{ $jaksas->links() }} --}}
            </div>
        </div>
    </div>
</div>


  

@endsection

