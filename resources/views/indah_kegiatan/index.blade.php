@extends('layouts.app')

@section('content')

<style>
    table.table {
        table-layout: fixed;
        width: 100%;
    }

    table.table th, table.table td {
        word-wrap: break-word;
        vertical-align: middle;
    }

    td.text-start {
        text-align: left;
    }
</style>

{{-- Sinkronisasi Metadata --}}
<div class="mb-4">
    <form method="GET" action="{{ route('indah-kegiatan.sync') }}">
        <div class="input-group mb-3" style="max-width: 300px;">
            <input type="number" name="tahun" class="form-control" placeholder="Tahun"
                   value="{{ request('tahun', now()->year) }}">
            <button type="submit" class="btn btn-success">Sinkronkan Data</button>
        </div>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <h1 class="mb-4">Daftar Metadata Kegiatan Statistik</h1>

    {{-- Search & Filter --}}
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
        <form id="filter-form" method="GET" action="{{ route('indah-kegiatan.index') }}" class="d-flex flex-wrap gap-2 w-100">
            {{-- Input Pencarian --}}
            <div class="flex-grow-1" style="min-width: 200px;">
                <input type="text" id="search-input" name="q" class="form-control"
                       placeholder="Cari instansi atau judul..." value="{{ request('q') }}">
            </div>

            {{-- Filter Tahun --}}
            <div style="min-width: 200px;">
                <select name="tahun" id="tahun-select" class="form-select">
                    <option value="">Periode: Semua Tahun</option>
                    @foreach (range(now()->year, 2022) as $th)
                        <option value="{{ $th }}" {{ request('tahun', now()->year - 1) == $th ? 'selected' : '' }}>
                            Tahun {{ $th }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Tabel Kegiatan --}}
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th class="text-center" style="width: 5%;">No.</th>
                <th class="text-start" style="width: 35%;">Nama Kegiatan</th>
                <th class="text-center" style="width: 7%;">Tahun</th>
                <th class="text-center" style="width: 15%;">Jenis Statistik</th>
                <th class="text-start" style="width: 23%;">Produsen Data</th>
                <th class="text-center" style="width: 7%;">Status</th>
                <th class="text-center" style="width: 7%;">Aksi</th>
            </tr>
        </thead>
        <tbody id="table-container">
            @include('indah_kegiatan.partials.table_rows')
        </tbody>
    </table>

    <div id="pagination-container">
        {{ $kegiatan->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
let timer;

// Event pencarian (dengan debounce)
$('#search-input').on('keyup', function () {
    clearTimeout(timer);
    timer = setTimeout(() => {
        fetchFilteredData(1);
    }, 300);
});

// Event filter tahun
$('#tahun-select').on('change', function () {
    fetchFilteredData(1);
});

// Pagination link
$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    const page = $(this).attr('href').split('page=')[1];
    fetchFilteredData(page);
});

function fetchFilteredData(page = 1) {
    const q = $('#search-input').val();
    const tahun = $('#tahun-select').val();

    const url = `{{ route('indah-kegiatan.index') }}?page=${page}&q=${encodeURIComponent(q)}&tahun=${encodeURIComponent(tahun)}`;

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            $('#table-container').html(data.rows);
            $('#pagination-container').html(data.pagination);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
}
</script>

@endsection
