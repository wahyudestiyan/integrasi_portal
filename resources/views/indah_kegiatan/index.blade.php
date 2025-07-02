@extends('layouts.app')

@section('content')
<div class="mb-4">
    <form method="GET" action="{{ route('indah-kegiatan.sync') }}">
        <div class="input-group mb-3" style="max-width: 300px;">
            <input type="number" name="tahun" class="form-control" placeholder="Tahun" value="{{ request('tahun', now()->year) }}">
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
<input type="text" id="search-input" class="form-control mb-3" placeholder="Cari berdasarkan tahun, instansi, atau judul kegiatan...">

    <table class="table table-bordered table-striped" >
        <thead class="table-dark">
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Nama Kegiatan</th>
                <th class="text-center">Tahun</th>
                <th class="text-center">Jenis Statistik</th>
                <th class="text-center">Produsen Data</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody id="table-container">
            @forelse ($kegiatan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $item->judul_kegiatan ))) }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $item->tahun ))) }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $item->jenis_statistik ))) }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $item->produsen_data_name ))) }}</td>
          <td>
                <span class="badge bg-{{ $item->status == 'APPROVED' ? 'success' : 'warning' }}">
                    {{ $item->status == 'APPROVED' ? 'Disetujui' : 'Menunggu' }}
                </span>
            </td>

                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('indah-kegiatan.show', $item->id) }}">Detail Kegiatan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-success" href="{{ route('msvar.sync', $item->id) }}">🔄 Sinkron MsVar</a></li>
                            <li><a class="dropdown-item text-primary" href="{{ route('msind.sync', $item->id) }}">🔄 Sinkron MsInd</a></li>
                        </ul>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada data kegiatan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    $('#search-input').on('keyup', function () {
        let query = $(this).val();

        $.ajax({
            url: '{{ route("indah-kegiatan.index", [], true) }}',
            type: 'GET',
            data: { q: query },
            success: function (data) {
                $('#table-container').html(data);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.log("Response Text:", xhr.responseText);
            }
        });
    });
</script>


@endsection
