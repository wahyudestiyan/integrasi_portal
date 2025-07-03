@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .dashboard-container {
    display: flex;
    gap: 30px;
    overflow-x: auto;
    padding: 10px 0;
    max-width: 1200px; /* agar lebarnya setara dengan bagian bawah */
    margin: 0 auto;     /* agar berada di tengah halaman */
}


    .dashboard-card {
        min-width: 200px;
        flex-shrink: 0;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 2px 2px 6px rgba(0,0,0,0.05);
        background-color: #fff;
    }

    .dashboard-header {
        background-color: #003366;
        color: #fff;
        font-weight: bold;
        padding: 10px;
        text-align: center;
        font-size: 14px;
    }
    .dashboard-header1 {
        background-color:rgb(12, 148, 0);
        color: #fff;
        font-weight: bold;
        padding: 10px;
        text-align: center;
        font-size: 14px;
    }

    .dashboard-header2 {
        background-color:rgb(12, 148, 0);
        color: #fff;
        font-weight: bold;
        padding: 10px;
        text-align: center;
        font-size: 14px;
    }

    .dashboard-value {
        font-size: 45px;
        font-weight: bold;
        color: #003366;
        text-align: center;
        padding: 20px 0;
    }
    .dashboard-value1 {
        font-size: 45px;
        font-weight: bold;
        color:rgb(5, 107, 48);
        text-align: center;
        padding: 20px 0;
    }
    .dashboard-value2 {
        font-size: 45px;
        font-weight: bold;
        color:rgb(5, 107, 48);
        text-align: center;
        padding: 20px 0;
    }

        .chart-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        width: 100%;
        flex-wrap: wrap;
        padding-top: 20px;
        box-sizing: border-box;
        max-width: 1200px; /* Menambahkan batasan maksimal lebar kontainer */
        margin: 0 auto; /* Untuk memastikan kontainer terpusat */
    }

    /* Ukuran grafik yang lebih fleksibel */
    .chart-container canvas {
        width: 45vw;  /* Menggunakan viewport width agar lebih responsif */
        height: 30vh;  /* Menggunakan viewport height untuk proporsionalitas */
        max-width: 500px; /* Batas maksimal lebar untuk canvas */
        max-height: 300px; /* Batas maksimal tinggi untuk canvas */
    }

    /* Media Query untuk perangkat kecil */
    @media (max-width: 768px) {
        .chart-container canvas {
            width: 48vw;
            height: 25vh;
            max-width: 400px;
            max-height: 250px;
        }
    }

    @media (max-width: 480px) {
        .chart-container canvas {
            width: 100%;  /* Grafik akan mengisi 100% lebar layar */
            height: 200px;  /* Menyesuaikan tinggi grafik */
        }
    }

</style>

<div class="dashboard-container">
    <div class="dashboard-card">
        <div class="dashboard-header">Total Data PORTAL</div>
        <div class="dashboard-value">{{ $totalDataApi }}</div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-header">Total API OPD</div>
        <div class="dashboard-value">{{ $totalApi }}</div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-header">Total API BPS</div>
        <div class="dashboard-value">{{ $totalApiBps }}</div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-header1">Status Terkirim (API OPD)</div>
        <div class="dashboard-value1">{{ $totalTerkirimApi }}</div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-header2">Status Terkirim (API BPS)</div>
        <div class="dashboard-value2">{{ $totalTerkirimBps }}</div>
    </div>
</div>

<!-- Chart Section -->
<div class="chart-container">
    <!-- API OPD Chart -->
    <canvas id="apiOpdChart"></canvas>
    <!-- API BPS Chart -->
    <canvas id="apiBpsChart"></canvas>
</div>

<!-- Data List Section - Adjusted to match chart width -->
<div class="mt-10 max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-semibold text-blue-900 mb-4 flex items-center gap-2">
        Jumlah Data Masuk Portal Berdasarkan Tahun Data
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($tahunJumlahFiltered as $tahun => $jumlah)
            <div class="flex items-center justify-between bg-gray-100 hover:bg-blue-50 transition-colors p-3 rounded-md shadow-sm">
                <span class="text-blue-800 font-medium">Tahun {{ $tahun }}</span>
                <span class="text-gray-700">{{ $jumlah }} judul</span>
            </div>
        @endforeach
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // API OPD Chart
    var ctxOpd = document.getElementById('apiOpdChart').getContext('2d');
    var apiOpdChart = new Chart(ctxOpd, {
        type: 'bar',
        data: {
            labels: ['API OPD'],
            datasets: [
                {
                    label: 'Total API OPD',
                    data: [{{ $totalApi }}],
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'API OPD Terkirim',
                    data: [{{ $totalTerkirimApi }}],
                    backgroundColor: 'rgba(40, 167, 69, 0.5)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // API BPS Chart
    var ctxBps = document.getElementById('apiBpsChart').getContext('2d');
    var apiBpsChart = new Chart(ctxBps, {
        type: 'bar',
        data: {
            labels: ['API BPS'],
            datasets: [
                {
                    label: 'Total API BPS',
                    data: [{{ $totalApiBps }}],
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'API BPS Terkirim',
                    data: [{{ $totalTerkirimBps }}],
                    backgroundColor: 'rgba(40, 167, 69, 0.5)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>

@endsection
