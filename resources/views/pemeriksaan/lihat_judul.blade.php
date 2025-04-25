@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Daftar Detail Pemeriksaan</h4>

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
                        <td colspan="5" class="text-center">Tidak ada data yang tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
