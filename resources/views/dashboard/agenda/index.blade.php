@extends('dashboard.layouts.main')
@section('judul-halaman')
Agenda
@endsection

@section('konten')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">
        
        <div class="container">
            <form action="{{ route('agenda.index') }}" method="GET">
                <div class="container">
                  <div class="row justify-content-end">
                      <div class="col-lg-4">
                          <div class="input-group input-group-outline my-3">

                                  <input type="text" name="search" value="{{request('search')}}" placeholder="Cari Agenda.." class="form-control">
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

              {{-- Tombol ke halaman form --}}
              <a href="/dashboard/agenda/create" class="btn btn-info">Tambah Agenda+</a>
            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Jaksa</th>
                        <th>No WA</th>
                        <th>Nama Kasus</th>
                        <th>Nama Saksi</th>
                        <th>Keterangan</th>
                        <th>Tanggal & Waktu Pelaksanaan</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>

                    </tr>

                    @if ($reminders->count())
                    @foreach ($reminders as $reminder )
                    <tr>
                        <td class="text-wrap">{{ $loop->iteration }}</td>
                        <td class="text-wrap">{{ $reminder->prosecutor_name }}</td>
                        <td class="text-wrap">{{ $reminder->phone_number}}</td>
                        <td class="text-wrap">{{ $reminder->case_name }}</td>
                        <td class="text-wrap">{{ $reminder->witnesses }}</td>
                        <td class="text-wrap">{{ $reminder->message }}</td>
                        <td class="text-wrap">{{ $reminder->scheduled_time }}</td>
                        <td class="text-wrap">{{ ($reminder->created_at)->format('d-m-Y') }}</td>

                        <td>
                            <button type="button" class="btn bg-info" data-bs-toggle="modal" data-bs-target="#resetModal{{ $reminder->id }}">
                                <span class="material-symbols-outlined text-white">
                                    restart_alt
                                </span>
                            </button>
                            <button type="button" class="btn bg-danger" data-bs-toggle="modal" data-bs-target="#editModal{{ $reminder->id }}">
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
                                Tidak ada agenda
                                @if (request('search'))
                                <kbd>{{ request('search') }}</kbd>                
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $reminders->links() }}
            </div>



        </div>




    </div>

</div>

{{-- @foreach ($users as $user )
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Delete User</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
            <h4>Dengan mengklik hapus maka data user <kbd>{{$user->name}}</kbd> akan terhapus secara permanen!</h4>

            <form method="post" action="{{ route('user.destroy',$user->id) }}}}" class="mb-5"
                enctype="multipart/form-data">
                @csrf
                @method('delete')
           <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-danger">Hapus</button>
           </div>
            </form>
        </div>
      </div>
    </div>
  </div>

@endforeach --}}

{{-- @foreach ($users as $user )
<div class="modal fade" id="resetModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Delete User</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
            <h4>Dengan mengklik reset maka password user <kbd>{{$user->name}}</kbd> akan menjadi password default!</h4>

            <form method="post" action="{{ route('user.update',$user->id) }}}}" class="mb-5"
                enctype="multipart/form-data">
                @csrf
                @method('put')
    
          
           <div class="d-flex justify-content-center">
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn bg-gradient-info">Reset</button>
            </div>
           </div>
            </form>
        </div>
      </div>
    </div>
  </div>

@endforeach --}}



@endsection




{{-- Menghilangkan alert --}}
<script>
    $(document).ready(function() {
        // Mengatur timeout untuk menghilangkan alert dalam 2 detik
        setTimeout(function() {
            $('#alertContainer').fadeOut('slow');
        }, 1200);
    });
  </script>