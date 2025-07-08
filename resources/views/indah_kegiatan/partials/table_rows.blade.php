@forelse ($kegiatan as $index => $item)
<tr>
    <td class="text-center">{{ $kegiatan->firstItem() + $index }}</td>
    <td class="text-start">{{ ucwords(strtolower(str_replace('_', ' ', $item->judul_kegiatan))) }}</td>
    <td class="text-center">{{ $item->tahun }}</td>
    <td class="text-center">{{ ucwords(strtolower(str_replace('_', ' ', $item->jenis_statistik))) }}</td>
    <td class="text-start">{{ ucwords(strtolower(str_replace('_', ' ', $item->produsen_data_name))) }}</td>
    <td class="text-center">
        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
    </td>
    <td class="text-center">
        <span class="badge bg-{{ $item->status == 'APPROVED' ? 'success' : 'warning' }}">
            {{ $item->status == 'APPROVED' ? 'Disetujui' : 'Menunggu' }}
        </span>
    </td>
    <td class="text-center">
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
    <td colspan="8" class="text-center text-muted">Tidak ada data kegiatan.</td>
</tr>
@endforelse
