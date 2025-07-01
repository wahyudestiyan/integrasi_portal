@php
    $currentRoute = request()->route()->getName();
    $jumlahVar = $kegiatan->variabels->count() ?? 0;
    $jumlahInd = $kegiatan->indikator->count() ?? 0;
@endphp

<style>
    .custom-tabs .nav-link {
        border: 1px solid #ccc;
        color: #1F2937;
        background-color: white;
        margin-right: 5px;
    }

    .custom-tabs .nav-link.active {
        background-color: #2B6BC4; /* biru dongker */
        color: white;
        border-color: #1F2937;
    }

    .custom-tabs .nav-link:hover {
        background-color:rgb(4, 34, 65);
        color: white;
    }
</style>

<div class="mb-4">
    <ul class="nav nav-tabs custom-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'indah-kegiatan.show' ? 'active' : '' }}"
               href="{{ route('indah-kegiatan.show', $kegiatan->id) }}">
                Metadata Statistik Kegiatan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'msvar.index' ? 'active' : '' }}"
               href="{{ route('msvar.index', $kegiatan->id) }}">
                Metadata Statistik Variabel
                <span class="badge bg-secondary ms-1">{{ $jumlahVar }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'msind.index' ? 'active' : '' }}"
               href="{{ route('msind.index', $kegiatan->id) }}">
                Metadata Statistik Indikator
                <span class="badge bg-secondary ms-1">{{ $jumlahInd }}</span>
            </a>
        </li>
    </ul>
</div>
