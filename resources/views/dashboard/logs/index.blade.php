@extends('dashboard.layouts.main')
@section('judul-halaman')
Login Logs
@endsection

@section('konten')

<div class="card shadow mt-4">
        <div class="card-header pb-0">
            <h6>Login Logs 
                <span class="material-symbols-outlined">
                schedule
                </span>
            </h6>
            <p class="text-sm">
                <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                
            </p>
        </div>
        <div class="card-body p-3">
            <div class="timeline timeline-one-side" style="flex-direction: column-reverse;">
                @foreach($userLogs as $log)
                <div class="timeline-block mb-3">
                    
                    <div class="timeline-content">
                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $log['Nama'] }} -> {{ $log['email'] }}</h6>
                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $log['waktu_login'] ? \Carbon\Carbon::parse($log['waktu_login'])->format('d/m/Y - H:i') : '-' }}</p>
                    </div>
                    <span class="timeline-step">
                        <!--<span class="material-symbols-outlined">-->
                        <!--    schedule-->
                        <!--</span>-->
                        
                        
                        ⌛
                    </span>
                </div>
                @endforeach
               
            </div>
            @empty($userLogs)
            Tidak ada data yang tersedia
            @endempty
        </div>
        
</div> 
@endsection
