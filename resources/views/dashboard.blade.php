@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Chartjs', true)


@section('content')
    <div class="container-fluid">
        <div class="row py-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Dashboard</h1>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="card border mb-4">
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            <h5 class="card-title">Sampah Serela Hotel</h5>
                                            <small class="text-muted">5 hari terakhir (dalam kg)</small>
                                        </div>
                                        <div style="position: relative; height: 200px;">
                                            <canvas id="weightChart"></canvas>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card border mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center border"
                                                    style="width: 60px; height: 60px; margin-right: 15px;">
                                                    <h3 class="mb-0 font-weight-bold">{{ $countKeputusan }}</h3>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Jumlah form SPK diajukan</h6>
                                                    <small class="text-muted">Keputusan</small>
                                                </div>
                                            </div>
                                            <i class="fas fa-clipboard-list fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center border"
                                                    style="width: 60px; height: 60px; margin-right: 15px;">
                                                    <h3 class="mb-0 font-weight-bold">{{ $countTPA }}</h3>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">TPA</h6>
                                                    <small class="text-muted">Total Terdaftar</small>
                                                </div>
                                            </div>
                                            <i class="fas fa-warehouse fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center border"
                                                    style="width: 60px; height: 60px; margin-right: 15px;">
                                                    <h3 class="mb-0 font-weight-bold">{{ $countJenisSampah }}</h3>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Jenis Sampah</h6>
                                                    <small class="text-muted">Total Terdaftar</small>
                                                </div>
                                            </div>
                                            <i class="fas fa-trash fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Keputusan Terbaru</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Nama TPA</th>
                                                        <th>Jenis Sampah</th>
                                                        <th>Berat (kg)</th>
                                                        <th>Nama Pegawai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($latestKeputusan as $item)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->created_at->locale('id')->translatedFormat('l, d M Y') }}
                                                            </td>
                                                            <td>{{ $item->nama }}</td>
                                                            <td>{{ $item->jenis_sampah }}</td>
                                                            <td>{{ $item->jumlah_sampah }}</td>
                                                            <td>{{ $item->nama_pengguna }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Distribusi Jenis Sampah</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="pieChart" style="min-height: 100px;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    @foreach ($topFourJenisSampah as $item)
                                        <div class="col-md-6">
                                            <div class="card border mb-4">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0 text-truncate" style="max-width: 150px;">
                                                                {{ $item->jenis_sampah }}
                                                            </h6>
                                                            <h2 class="mb-0 text-lg">{{ $item->total_weight }} kg</h2>
                                                            <small>Total Berat</small>
                                                        </div>
                                                        <i class="fas fa-trash fa-lg text-success"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    @foreach ($topTPA as $item)
                        <div class="col-md-2">
                            <div class="card border mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 text-truncate" style="max-width: 150px;">{{ $item->nama }}
                                            </h6>
                                            <h2 class="mb-0">{{ $item->total_wins }}x</h2>
                                            <small>Terpilih</small>
                                            <div class="text-muted mt-1">Total: {{ $item->total_weight }} kg</div>
                                        </div>
                                        <i class="fas fa-recycle fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Get the canvas element
            var ctx = document.getElementById('weightChart').getContext('2d');

            // Get chart data
            var dates = @json($fiveDaysSampah['dates']);
            var totals = @json($fiveDaysSampah['totals']);

            // Create the chart
            var weightChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Berat Sampah',
                        data: totals,
                        backgroundColor: 'rgba(75, 192, 192, 0.8)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        borderRadius: 5,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' kg';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' kg';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var ctx = document.getElementById('pieChart').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($topFourJenisSampah->pluck('jenis_sampah')),
                    datasets: [{
                        data: @json($topFourJenisSampah->pluck('total_weight')),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                    }]
                }
            });
        });
    </script>
@endsection
