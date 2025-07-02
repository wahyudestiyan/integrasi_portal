@extends('layouts.app')

@section('content')

<div class="container">


<h1 class="mb-2"><strong>MONITORING JUDUL PORTAL DATA</strong></h1>



    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
   

    {{-- Form Filter --}}
<form action="{{ route('monitoring.index') }}" method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="instansi" class="form-control" onchange="this.form.submit()">
                <option value="">-- Pilih Instansi --</option>
                @foreach($instansis as $instansi)
                    <option value="{{ $instansi->id }}" {{ $selectedInstansiId == $instansi->id ? 'selected' : '' }}>
                        {{ $instansi->nama_instansi }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</form>

   
{{-- Tabel Data --}}
@if($selectedInstansi)
    <div class="card mb-4 shadow">
    <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
    <strong class="text-uppercase">{{ $selectedInstansi->nama_instansi }}</strong>
    <div class="d-flex gap-2">
    <form action="{{ route('monitoring.lihatlogs', $selectedInstansi->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-info btn-sm">
                🔍 Lihat Log dan Update
            </button>
        </form>

        <form action="{{ route('monitoring.sync.instansi', $selectedInstansi->id) }}" method="POST" id="form-update">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm" id="btn-update">
                🔄
            </button>
        </form>

        <form action="{{ route('monitoring.exportExcel', $selectedInstansi->id) }}" method="GET" style="display:inline;">
    <button type="submit" class="btn btn-success btn-sm">
                📥 Download Excel
            </button>
        </form>


        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form = document.getElementById("form-update");
                const button = document.getElementById("btn-update");

                form.addEventListener("submit", function (e) {
                    e.preventDefault(); // Cegah submit langsung
                    button.disabled = true;
                    button.innerText = "⏳ Memproses...";
                    form.submit(); // Submit form setelah tombol di-disable
                });
            });
        </script>

    </div>
</div>


        {{-- Form pencarian judul --}}
        <form method="GET" action="{{ route('monitoring.index') }}" class="p-3">
            <input type="hidden" name="instansi" value="{{ $selectedInstansiId }}">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="🔍 Cari Judul..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
            </div>
        </form>

        <div class="card-body">
            @if ($selectedInstansi->dataApis->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="bg-light text-dark text-center">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>ID API</th>
                                <th>Judul</th>
                                <th>Tahun Data</th>
                                <th>Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedInstansi->dataApis as $index => $data)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $data->id_api }}</td>
                                    <td>{{ $data->judul }}</td>
                                    <td>{{ $data->tahun_data }}</td>
                                    <td>
                                    @php
                                        $tahunList = explode(',', $data->tahun_data);
                                    @endphp

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Lihat Tahun
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($tahunList as $tahun)
                                                <li>
                                                    <a href="#" class="dropdown-item" onclick="lihatDataTahun('{{ $data->id_api }}', '{{ $tahun }}')">
                                                        {{ $tahun }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    Data Tidak Tersedia
                </div>
            @endif
        </div>
    </div>
@elseif($selectedInstansiId)
    <div class="alert alert-warning">Instansi tidak ditemukan.</div>
@endif



</div>

<!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="dataModalLabel">Detail Data Tahun <span id="modalTahun"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="modalContent">
            <p class="text-center">Memuat data...</p>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function lihatDataTahun(id_api, tahun) {
    document.getElementById('modalContent').innerHTML = '<p>⏳ Memuat data...</p>';
    document.getElementById('modalTahun').textContent = tahun;

    const fetchUrl = `{{ route('ambildata', ['id_api' => '__ID__', 'tahun' => '__YEAR__'], true) }}`
        .replace('__ID__', id_api)
        .replace('__YEAR__', tahun);

    fetch(fetchUrl)
        .then(res => res.json())
        .then(res => {
            const data = res.data_filtered;
            if (!data || data.length === 0) {
                document.getElementById('modalContent').innerHTML = '<p class="text-danger">Tidak ada data ditemukan untuk tahun ini.</p>';
                return;
            }

            const headers = [...new Set(data.flatMap(item => Object.keys(item)))];

            let html = `<div class="table-responsive"><table class="table table-bordered table-striped table-sm">
                <thead class="table-light"><tr>`;
            headers.forEach(h => {
                html += `<th>${h.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</th>`;
            });
            html += `</tr></thead><tbody>`;

            data.forEach(item => {
                html += `<tr>`;
                headers.forEach(h => {
                    html += `<td>${item[h] !== undefined ? item[h] : ''}</td>`;
                });
                html += `</tr>`;
            });

            html += `</tbody></table></div>`;
            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(err => {
            console.error("FETCH ERROR:", err);
            document.getElementById('modalContent').innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
        });

    const modal = new bootstrap.Modal(document.getElementById('dataModal'));
    modal.show();
}
</script>


@endsection
