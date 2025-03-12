@extends('layouts.app')

@section('title', 'Konfirmasi Kirim Data API BPS')

@section('content')
<style>
    .card-title {
        font-weight: bold;
        font-size: 20px;
    }

    .response-box {
        max-height: 380px;
        overflow-y: auto;
        background-color: #000000;
        color: #00ff00;
        padding: 10px;
        border-radius: 5px;
        font-family: "Courier New", Courier, monospace;
        font-size: 14px;
        line-height: 1.5;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Konfirmasi Detail API BPS</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p><strong>Nama Instansi:</strong></p>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $apibps->nama_instansi }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p><strong>Nama Data:</strong></p>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $apibps->nama_data }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p><strong>ID Data:</strong></p>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $apibps->id_data }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p><strong>URL API:</strong></p>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $apibps->url_api }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p><strong>Token:</strong></p>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $apibps->token }}</p>
                        </div>
                    </div>

                    <h5>Response JSON dari Data Mapping:</h5>
                    @if($responseData)
                        <pre class="response-box">
                            <code class="language-json">
                                {{ json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                            </code>
                        </pre>
                    @else
                        <p>Tidak ada data untuk ditampilkan.</p>
                    @endif
                </div>

                <div class="card-footer">
                    <form action="{{ route('apibps.kirim', $apibps->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Kirim</button>
                        <a href="{{ route('apibps.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
