@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">
        Daftar Metadata Variabel Kegiatan: <strong>{{ $kegiatan->judul_kegiatan }}</strong>
    </h2>
</div>



    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <input type="text" id="search-msvar" class="form-control mb-3" placeholder="Cari nama variabel...">

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Nama Variabel</th>
                <th class="text-center">Alias</th>
                <th class="text-center">Produsen Data</th>
                <th class="text-center">Pelapor</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="msvar-table">
            @forelse ($msvars as $index => $var)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $var->nama ))) }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $var->alias ))) }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $var->produsen_data_name ))) }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $var->requested_by ))) }}</td>
                <td>
                <span class="badge bg-{{ $var->status == 'APPROVED' ? 'success' : 'warning' }}">
                    {{ $var->status == 'APPROVED' ? 'Disetujui' : 'Menunggu' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('msvar.show', $var->id) }}"class="btn btn-sm btn-info">Lihat Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Belum ada Metadata Variabel.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#search-msvar').on('keyup', function () {
        let query = $(this).val();
        let id = {{ $kegiatan->id }};

        $.ajax({
            url: '/indah-kegiatan/' + id + '/msvar',
            type: 'GET',
            data: { q: query },
            success: function (data) {
                $('#msvar-table').html(data);
            }
        });
    });
</script>

@endsection
