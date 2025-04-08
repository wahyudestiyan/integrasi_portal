@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3"><strong>Riwayat Perubahan Judul Data </strong>- {{ $instansi->nama_instansi }}</h1>
    <a href="{{ route('monitoring.index', ['instansi' => $instansi->id]) }}" class="btn btn-secondary mb-3">← Kembali</a>

    {{-- Form Pencarian Judul --}}
    <form method="GET" action="{{ route('monitoring.logs', $instansi->id) }}" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="🔍 Cari Judul Lama / Baru..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>
        </div>
    </form>

    @if ($logs->isEmpty())
        <div class="alert alert-info mt-3">
            Instansi ini tidak mengalami perubahan judul.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>ID API</th>
                        <th>Judul Lama</th>
                        <th>Judul Baru</th>
                        <th>Jenis Perubahan</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $index => $log)
                        <tr>
                            <td class="text-center">{{ $logs->firstItem() + $index }}</td>
                            <td>{{ $log->dataApi->id_api ?? '-' }}</td>
                            <td style="word-break: break-word;">{{ $log->judul_lama ?? '-' }}</td>
                            <td style="word-break: break-word;">{{ $log->judul_baru ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $log->tipe_perubahan == 'Judul Baru' ? 'success' : ($log->tipe_perubahan == 'Judul Berubah' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($log->tipe_perubahan) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $logs->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
