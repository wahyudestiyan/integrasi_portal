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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedInstansi->dataApis as $index => $data)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $data->id_api }}</td>
                                    <td>{{ $data->judul }}</td>
                                    <td>{{ $data->tahun_data }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    Instansi ini tidak memiliki data API.
                </div>
            @endif
        </div>
    </div>
@elseif($selectedInstansiId)
    <div class="alert alert-warning">Instansi tidak ditemukan.</div>
@endif



</div>
@endsection
