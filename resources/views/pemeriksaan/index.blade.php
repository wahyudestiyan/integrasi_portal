@extends('layouts.app')

@section('content')
<div class="container">
<h4 class="mb-5 text-2xl font-semibold"><strong>Rekapitulasi Pemeriksaan Data</strong></h4>



{{-- Form Filter Tahun dan Tombol Download Excel Sejajar --}}
<div class="flex justify-between items-center mb-6">
    {{-- Form Filter Tahun --}}
    <form method="GET" action="{{ route('pemeriksaan.index') }}" class="flex items-center space-x-4">
        <label for="tahun" class="text-gray-700">Pilih Tahun</label>
        <select name="tahun" id="tahun" onchange="this.form.submit()" class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">-- Pilih Tahun --</option>
            @foreach($tahunList as $tahun)
                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                    {{ $tahun }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Tombol Download Excel --}}
    @if($tahun)  <!-- Pastikan tombol hanya muncul jika tahun dipilih -->
        <a href="{{ route('pemeriksaan.export') }}" class="btn btn-sm btn-success">Download Excel</a>
    @endif
</div>

</div>
<br>


{{-- Tabel Rekapitulasi Pemeriksaan Data --}}
<div class="table-responsive">
    @if($tahun)  <!-- Pastikan tabel hanya tampil jika tahun dipilih -->
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Instansi</th>
                    <th>Data Prioritas SK Sekda</th>
                    <th>Judul Data Prioritas Terdaftar</th>
                    <th>Data Prioritas Masuk</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapitulasi as $index => $rekap)
                    <tr>
                    <td class="text-center">{{ $rekapitulasi->firstItem() + $loop->index }}</td>
                    <td>{{ $rekap->instansi->nama_instansi }}</td>
                    <td class="text-center">{{ $rekap->jumlah_sk_sekda }}</td>
                    <td class="text-center">{{ $rekap->jumlah_terdaftar_di_portal }}</td>
                    <td class="text-center">{{ $rekap->jumlah_data_terisi }}</td>
                    <td class="text-center">
                            <span class="badge {{ $rekap->status === 'Lengkap' ? 'bg-success' : 'bg-danger' }}">
                                {{ $rekap->status }}
                            </span>
                    </td>
                    <td class="text-center">{{ $rekap->keterangan ?? '-' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <form method="POST" action="{{ route('pemeriksaan.periksa', ['instansi' => $rekap->instansi->id, 'tahun' => request('tahun', date('Y'))]) }}">
                                @csrf
                                <input type="hidden" name="tahun" value="{{ request('tahun') ?? date('Y') }}">
                                <input type="hidden" name="page" value="{{ request('page') }}">
                                <button type="submit" class="btn btn-sm btn-warning">Periksa</button>
                            </form>
                            <a href="{{ route('pemeriksaan.lihatJudul', ['instansi' => $rekap->instansi->id, 'tahun' => $tahun]) }}" class="btn btn-sm btn-primary ms-1">
                                Lihat
                            </a>
                        </div>
                    </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data pemeriksaan untuk tahun ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <p>Silakan pilih tahun untuk melihat data.</p>
    @endif
</div>


    {{-- Pagination --}}
    <div class="mt-3">
        {{ $rekapitulasi->withQueryString()->links() }}
    </div>
</div>
@endsection
