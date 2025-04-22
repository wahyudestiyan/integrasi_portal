@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Daftar Judul Lengkap</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Instansi</th>
                    <th>Judul Data</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataLengkap as $index => $data)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $data->instansi->nama_instansi }}</td>
                        <td>{{ $data->judul_data }}</td>
                        <td class="text-center">
                            <span class="badge {{ $data->status == 'Lengkap' ? 'bg-success' : 'bg-danger' }}">
                                {{ $data->status }}
                            </span>
                        </td>
                        <td>{{ $data->keterangan }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data yang tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
