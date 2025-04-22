@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Data Belum Lengkap</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Instansi</th>
                    <th>Data Prioritas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataBelum as $index => $data)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $data->instansi->nama_instansi }}</td>
                        <td>{{ $data->judul_data }}</td>
                        <td class="text-center">
                            <span class="badge bg-danger">Belum Lengkap</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data yang belum lengkap.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
