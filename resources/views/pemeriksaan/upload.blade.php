@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Upload Data Prioritas SK Sekda</h4>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol Unduh Format --}}
    <div class="mb-3">
        <a href="{{ route('pemeriksaan.unduh-template') }}" class="btn btn-outline-primary">
            📥 Unduh Format Excel
        </a>
    </div>

    {{-- Form Upload --}}
    <form action="{{ route('pemeriksaan.import-excel') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Pilih File Excel</label>
            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required>
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">🚀 Upload dan Simpan</button>
    </form>
</div>
@endsection
