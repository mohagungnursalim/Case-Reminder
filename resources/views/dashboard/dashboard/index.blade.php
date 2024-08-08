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
        <div id="chart_terkirim_div"></div>
        <div id="chart_belum_terkirim_div"></div>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script type="text/javascript">
            // Load Google Charts
            google.charts.load('current', {'packages':['corechart']});
        
            // Draw the charts when Google Charts is loaded
            google.charts.setOnLoadCallback(initCharts);
        
            function initCharts() {
                drawTerkirimChart();
                drawBelumTerkirimChart();
            }
        
            function drawTerkirimChart() {
                $.ajax({
                    url: "{{ url('/agenda-terkirim-sesuai-jadwal') }}", // jika menggunakan HTTPS => tambahkan "[], true"
                    dataType: "json",
                    success: function(data) {
                        var dataTable = new google.visualization.DataTable();
                        dataTable.addColumn('datetime', 'Tanggal Waktu');
                        dataTable.addColumn('number', 'Jumlah');
        
                        var maxValue = 0;
                        var totalCount = 0;
        
                        $.each(data, function(index, row) {
                            var value = parseFloat(row.jumlah);
                            dataTable.addRow([new Date(row.tanggal_waktu), value]);
                            totalCount += value; // Sum the values for total count
                            if (value > maxValue) {
                                maxValue = value;
                            }
                        });
        
                        var options = {
                            title: 'Agenda Terkirim (' + totalCount + ')', // Display total count in the title
                            curveType: 'function',
                            legend: { position: 'bottom' },
                            vAxis: {
                                viewWindow: {
                                    max: maxValue + 2
                                }
                            }
                        };
        
                        var chart = new google.visualization.AreaChart(document.getElementById('chart_terkirim_div'));
                        chart.draw(dataTable, options);
                    }
                });
            }
        
            function drawBelumTerkirimChart() {
                $.ajax({
                    url: "{{ url('/agenda-belum-terkirim-sesuai-jadwal') }}", // jika menggunakan HTTPS => tambahkan "[], true"
                    dataType: "json",
                    success: function(data) {
                        var dataTable = new google.visualization.DataTable();
                        dataTable.addColumn('datetime', 'Tanggal Waktu');
                        dataTable.addColumn('number', 'Jumlah');
        
                        var maxValue = 0;
                        var totalCount = 0;
        
                        $.each(data, function(index, row) {
                            var value = parseFloat(row.jumlah);
                            dataTable.addRow([new Date(row.tanggal_waktu), value]);
                            totalCount += value; // Sum the values for total count
                            if (value > maxValue) {
                                maxValue = value;
                            }
                        });
        
                        var options = {
                            title: 'Agenda Belum Terkirim (' + totalCount + ')', // Display total count in the title
                            curveType: 'function',
                            legend: { position: 'bottom' },
                            vAxis: {
                                viewWindow: {
                                    max: maxValue + 2
                                }
                            }
                        };
        
                        var chart = new google.visualization.AreaChart(document.getElementById('chart_belum_terkirim_div'));
                        chart.draw(dataTable, options);
                    }
                });
            }
        </script>
    </div>

    @endsection
