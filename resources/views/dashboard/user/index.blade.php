@extends('dashboard.layouts.main')
@section('judul-halaman')
Kelola User
@endsection

@section('konten')

<head>
    <style>
        .info {
            border: none;
            background-color: #85c1e9;
            /* Ganti dengan warna latar belakang yang Anda inginkan */
            color: #0056b3;
            /* Ganti dengan warna teks yang Anda inginkan */
        }

        .success {
            border: none;
            background-color: #a3e4a9;
            color: #196f3d;
            /* Ganti dengan warna teks yang Anda inginkan */
        }

        .secondary {
            border: none;
            background-color: #d2d0d0;
            color: #595959;
            /* Ganti dengan warna teks yang Anda inginkan */
        }

        .warning {
            border: none;
            background-color: #fbdba8;
            color: #b9770e;
        }

    </style>
</head>

<div class="card card-frame">
    <div class="card-body">
        <div class="container">
            <div class="card-body">
                <div class="alert alert-secondary text-white text-nowrap" style="width: 18rem;" role="alert">
                    <i class="fas fa-fw fa-info-circle"></i> Password bawaan: 12345678
                </div>

                <form class="form-inline" action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group mb-2 input-group input-group-outline">
                                <label for="staticEmail2" class="sr-only">Email</label>
                                <input type="email" name="email" class="form-control" id="staticEmail2"
                                    placeholder="email@example.com" required>
                            </div>
                            @error('email')
                            <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2 input-group input-group-outline">
                                <label for="inputNama2" class="sr-only">Nama</label>
                                <input type="text" class="form-control" name="name" id="inputNama2" placeholder="Nama"
                                    required>
                            </div>
                            @error('name')
                            <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-outline @error('is_admin') is-invalid @enderror mb-1">
                                <label for="role" class="sr-only">Peran</label>
                                <select id="role" name="is_admin" style="width: 100%;" class="form-control" required>
                                    <option value="">-Pilih Peran-</option>
                                    <option value="1">Admin</option>
                                    <option value="0">Operator</option>
                                </select>
                            </div>
                            @error('is_admin')
                            <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        @if (Auth::user()->email == 'mohagungnursalim@gmail.com')
                        <div class="col-md-2">
                            <div
                                class="input-group input-group-outline @error('kejari_nama') is-invalid @enderror mb-1">
                                <label for="kejari_nama" class="sr-only">Lokasi Kejaksaan</label>
                                <select id="kejari_nama" name="kejari_nama" style="width: 100%;" class="form-control"
                                    required>
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
                            @error('kejari_nama')
                            <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        @elseif (Auth::user()->is_admin == true)
                        <input type="hidden" name="kejari_nama" value="{{ Auth::user()->kejari_nama }}">
                        @endif

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-primary mb-2">Buat</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>



{{-- Card Table --}}
<div class="card shadow mt-4">
    <div class="card-body">

        <div class="container">

            <form action="{{ route('user.index') }}" method="GET">
                <div class="container">
                    <div class="row justify-content-end">
                        <div class="col-lg-4">
                            <div class="input-group input-group-outline my-3">

                                <input type="text" name="search" value="{{request('search')}}" placeholder="Cari User.."
                                    class="form-control">
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

            <div class="overflow-auto">
                <table id="myTable" class="table text-dark">
                    <tr>
                        <th class="text-wrap small">No</th>
                        <th class="text-wrap small">Status</th>
                        <th class="text-wrap small">Email</th>
                        <th class="text-wrap small">Nama User</th>
                        <th class="text-wrap small">Peran</th>
                        <th class="text-wrap small">Lokasi</th>
                        <th class="text-wrap small">Dibuat</th>
                        <th class="text-wrap small">Aksi</th>

                    </tr>

                    @if ($users->count())
                    @foreach ($users as $user )
                    <tr>
                        <td class="text-wrap small">{{ $loop->iteration }}</td>
                        <td class="text-wrap small">
                            @if ($user->is_online)
                            <span class="badge btn-sm text-success success">Online</span>
                            @elseif (is_null($user->last_seen))
                            <a class="text-muted">Non Aktif</a>
                            @else
                            <a class="text-muted">Terakhir dilihat {{ $user->last_seen->diffForHumans() }}</a>
                            @endif
                        </td>

                        <td class="text-wrap small">{{ $user->email }}</td>
                        <td class="text-wrap small">{{ $user->name }}</td>
                        <td class="text-wrap small">
                            @if ($user->is_admin == false)
                            <button type="button" required class="badge secondary btn-sm" style="border: none"
                                data-bs-toggle="modal" data-bs-target="#editperanModal{{ $user->id }}">
                                Operator
                            </button>
                            @else
                            <button type="button" required class="badge success btn-sm" style="border: none"
                                data-bs-toggle="modal" data-bs-target="#editperanModal{{ $user->id }}">
                                Admin
                            </button>
                            @endif

                        </td>
                        <td class="text-wrap small">{{ $user->kejari_nama }}</td>

                        <td class="text-wrap small">{{ ($user->created_at)->format('d-m-Y') }}</td>

                        <td class="text-wrap small">

                            <button type="button" class="btn bg-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#resetModal{{ $user->id }}">
                                <span class="material-symbols-outlined text-white">
                                    restart_alt
                                </span>
                            </button>
                            <button type="button" class="btn bg-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $user->id }}">
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
                            Tidak ada data user

                            @if(request('search'))
                            <kbd>{{ request('search') }}</kbd> ditemukan!
                            <a class="text-info text-underlined font-italic" href="/dashboard/user">Kembali</a>
                            @endif
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>

        </div>

    </div>

</div>

@foreach ($users as $user )
<!-- Modal Update Peran-->
<div class="modal fade" id="editperanModal{{ $user->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Update Peran {{ $user->name }}</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" action="{{ route('user.peran',$user->id) }}" class="mb-2">
                    @csrf
                    @method('put')
                    <div class="form-check form-switch">
                        @if ($user->is_admin == true)
                        <input class="form-check-input" type="checkbox" name="is_admin" value="0"
                            id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Operator</label>
                        @else
                        <input class="form-check-input" type="checkbox" name="is_admin" value="1"
                            id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                        @endif
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-info">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endforeach
@foreach ($users as $user )
<!-- Modal Delete-->
<div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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

@endforeach

@foreach ($users as $user )
<!-- Modal Reset-->
<div class="modal fade" id="resetModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Reset Password User
                    <kbd>{{ $user->email }}</kbd></h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <h4>Dengan mengklik reset maka password user <kbd>{{$user->name}}</kbd> akan menjadi password bawaan!
                </h4>

                <form method="post" action="{{ route('user.update',$user->id) }}}}" class="mb-5"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')


                    <div class="d-flex justify-content-center">
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn bg-gradient-info">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach



@endsection

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Menghilangkan alert --}}
<script>
    $(document).ready(function () {
        // Check if the alert exists
        if ($('#alertContainer').length) {
            // Set timeout to hide the alert after 2 seconds
            setTimeout(function () {
                $('#alertContainer').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, 1500);
        }
    });

</script>

<audio id="success-audio" src="{{ asset('assets/audio/send.mp3') }}" preload="auto"></audio>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cek jika ada pesan sukses di session
        @if(session('success'))
        // Dapatkan elemen audio
        var audio = document.getElementById('success-audio');
        // Putar audio
        audio.play();
        @endif
    });

</script>
