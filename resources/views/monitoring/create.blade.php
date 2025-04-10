@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Upload Data Monitoring (Excel)</h2>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <form action="{{ route('monitoring.import.excel') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf

        <div class="form-group">
            <label for="file">Pilih File Excel</label>
            <input type="file" name="file" class="form-control" required accept=".xls,.xlsx">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Upload</button>
    </form>
</div>
@endsection
