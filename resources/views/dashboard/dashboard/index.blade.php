@extends('dashboard.layouts.main')
@section('judul-halaman')
{{ Auth::user()->name }}
@endsection

@section('konten')

<div class="container">
    <h3>Dashboard</h3>
</div>

<div class="container-fluid py-4">
    <div class="row">

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-dark shadow-success text-center border-radius-xl mt-n4 position-absolute">
                        <span class="material-symbols-outlined text-white" style="font-size: 34pt; width: 34pt;">
                            cases
                        </span>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize text-bold text-dark">Kasus</p>
                        <h4 class="mb-0">{{ $data['total_kasus'] }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-danger shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                        <span class="material-symbols-outlined text-white" style="font-size: 34pt; width: 34pt;">
                            supervisor_account
                        </span>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize text-bold text-dark">Atasan</p>
                        <h4 class="mb-0">{{ $data['total_atasan'] }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-info shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                        <span class="material-symbols-outlined text-white" style="font-size: 34pt; width: 34pt;">
                            group
                        </span>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize text-bold text-dark">Jaksa</p>
                        <h4 class="mb-0">{{ $data['total_jaksa'] }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-warning shadow-info text-center border-radius-xl mt-n4 position-absolute">
                        <span class="material-symbols-outlined text-white" style="font-size: 34pt; width: 34pt;">
                            adaptive_audio_mic
                        </span>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize text-bold text-dark">Total Saksi</p>
                        <h4 class="mb-0">{{ $data['total_saksi'] }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">
<!-- Chart container -->
<div style="position: relative; height: 50vh; width: 100%;">
    <canvas id="agendaChart"></canvas>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        drawAgendaChart();
    });

    function drawAgendaChart() {
        $.when(
            $.ajax({ url: "{{ url('/agenda-terkirim-sesuai-jadwal') }}", dataType: "json" }),
            $.ajax({ url: "{{ url('/agenda-belum-terkirim-sesuai-jadwal') }}", dataType: "json" })
        ).done(function(terkirimResponse, belumTerkirimResponse) {
            var terkirimData = terkirimResponse[0];
            var belumTerkirimData = belumTerkirimResponse[0];

            var labels = [];
            var terkirimValues = [];
            var belumTerkirimValues = [];

            var totalTerkirimCount = 0;
            var totalBelumTerkirimCount = 0;

            var dataMap = {};

            // Process Terkirim Data
            $.each(terkirimData, function(index, row) {
                var date = new Date(row.tanggal_waktu).toLocaleString();
                var value = parseFloat(row.jumlah);
                dataMap[date] = dataMap[date] || { terkirim: 0, belumTerkirim: 0 };
                dataMap[date].terkirim += value;
                totalTerkirimCount += value;
            });

            // Process Belum Terkirim Data
            $.each(belumTerkirimData, function(index, row) {
                var date = new Date(row.tanggal_waktu).toLocaleString();
                var value = parseFloat(row.jumlah);
                dataMap[date] = dataMap[date] || { terkirim: 0, belumTerkirim: 0 };
                dataMap[date].belumTerkirim += value;
                totalBelumTerkirimCount += value;
            });

            // Populate labels and dataset arrays
            for (var date in dataMap) {
                labels.push(date);
                terkirimValues.push(dataMap[date].terkirim);
                belumTerkirimValues.push(dataMap[date].belumTerkirim);
            }

            // Ensure the labels array is sorted
            labels.sort((a, b) => new Date(a) - new Date(b));

            var ctx = document.getElementById('agendaChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Terkirim',
                            data: terkirimValues,
                            fill: false,
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        },
                        {
                            label: 'Belum Terkirim',
                            data: belumTerkirimValues,
                            fill: false,
                            borderColor: 'rgb(255, 99, 132)',
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Agenda Terkirim (' + totalTerkirimCount + ') & Belum Terkirim (' + totalBelumTerkirimCount + ')'
                        },
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    }
</script>

        
           
 

    </div>

    @endsection
