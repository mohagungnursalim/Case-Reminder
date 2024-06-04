<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Style tambahan */
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">Kartu Anggota Siperpus</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Nama:</strong> {{ $anggota->nama_lengkap }}</p>
                        <p class="card-text"><strong>Kelas:</strong> {{ $anggota->kelas }}</p>
                        <p class="card-text"><strong>Jurusan:</strong> {{ $anggota->jurusan }}</p>
                        <p class="card-text"><strong>Alamat:</strong> {{ $anggota->alamat }}</p>
                        <p class="card-text"><strong>Telepon:</strong> {{ $anggota->telepon }}</p>
                        <p class="card-text"><strong>Email:</strong> {{ $anggota->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>