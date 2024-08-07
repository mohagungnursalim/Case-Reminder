@extends('dashboard.layouts.main')
@section('judul-halaman')
Activity Logs
@endsection

@section('konten')

<div class="card shadow mt-4">
        <div class="card-header pb-0">
            <h6>User Activity Logs
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
                        <h6 class="text-dark text-sm font-weight-bold mb-0">By:{{ $log['name'] }} </h6>
                        <p class="text-secondary text-xs font-weight-bold">"
                             
                            {{ $log['message'] ?? 'No message available' }} 
                            @isset($log['email_user']) 
                            => 
                            @endisset 
                            {{ $log['email_user'] ?? '' }} " 
                            <a class="text-dark font-weight-bold text-xs mt-1 mb-0">&nbsp;&nbsp;{{ $log['created_at'] ? \Carbon\Carbon::parse($log['created_at'])->format('H:i - d/m/Y') : '-' }}</a>
                        </p>
                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">📍{{ $log['lokasi']}}</p>
                    </div>
                    <span class="timeline-step">
                        <!--<span class="material-symbols-outlined">-->
                        <!--    schedule-->
                        <!--</span>-->
                        
                        
                        🕒
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
