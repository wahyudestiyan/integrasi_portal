@extends('layouts.app')

@section('title', 'Tambah Data API')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Data API</h1>
    <form action="{{ route('api.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nama_instansi" class="block text-sm font-medium">Nama Instansi</label>
            <input type="text" name="nama_instansi" id="nama_instansi" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label for="nama_data" class="block text-sm font-medium">Nama Data</label>
            <input type="text" name="nama_data" id="nama_data" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label for="url_api" class="block text-sm font-medium">URL API</label>
            <input type="url" name="url_api" id="url_api" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label for="credential_key" class="block text-sm font-medium text-gray-700">Credential Key (Optional)</label>
            <input type="text" name="credential_key" id="credential_key" class="mt-1 block w-full p-2 border rounded-md">
        </div>

        <div class="mb-4">
            <label for="credential_value" class="block text-sm font-medium text-gray-700">Credential Value (Optional)</label>
            <input type="text" name="credential_value" id="credential_value" class="mt-1 block w-full p-2 border rounded-md">
        </div>
        <div class="mb-4">
            <label for="method" class="block text-sm font-medium">Method</label>
            <select name="method" id="method" class="border rounded p-2 w-full" required>
                <option value="GET">GET</option>
                <option value="POST">POST</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection
