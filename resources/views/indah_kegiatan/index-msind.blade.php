@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">
        Daftar Metadata Indikator Kegiatan: <strong>{{ $kegiatan->judul_kegiatan }}</strong>
    </h2>
</div>



    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
<input type="text" id="search-msind" class="form-control mb-3" placeholder="Cari nama indikator...">

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Nama Indikator</th>
                <th class="text-center">Produsen Data</th>
                <th class="text-center">Tanggal Diajukan</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="msind-table">
            @forelse ($msinds as $index => $ind)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $ind->nama ))) }}</td>
                <td>{{ ucwords(strtolower(str_replace('_', ' ', $ind->produsen_data_name ))) }}</td>
                 <td>
                    {{ \Carbon\Carbon::parse($ind->created_at)->translatedFormat('d F Y') }}
                </td>
                <td>
                <span class="badge bg-{{ $ind->status == 'APPROVED' ? 'success' : 'warning' }}">
                    {{ $ind->status == 'APPROVED' ? 'Disetujui' : 'Menunggu' }}
                </span>
            </td>
                <td>
                    <a href="{{ route('msind.show', $ind->id) }}" class="btn btn-sm btn-info">Lihat Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Belum ada Metadata Indikator.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#search-msind').on('keyup', function () {
        let query = $(this).val();
        let id = {{ $kegiatan->id }};

        $.ajax({
            url: '/indah-kegiatan/' + id + '/msind',
            type: 'GET',
            data: { q: query },
            success: function (data) {
                $('#msind-table').html(data);
            }
        });
    });
</script>

@endsection
