@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-700 mb-6">Kelola Instansi & Token</h2>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
            {{ session('warning') }}
        </div>
    @endif

    {{-- Form Import --}}
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('visualisasi.download-template') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 whitespace-nowrap">
            Download Template
        </a>
    
        <form action="{{ route('visualisasi.import') }}" method="POST" enctype="multipart/form-data" class="flex-grow">
            @csrf
            <div class="flex items-center space-x-4">
                <input type="file" name="file" class="border rounded px-3 py-2 w-full focus:ring focus:ring-blue-300">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 whitespace-nowrap">
                    Import Data
                </button>
            </div>
            @error('file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>

    {{-- Tabel Data Instansi & Token --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-left w-16">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-left">Nama Instansi</th>
                    <th class="border border-gray-300 px-4 py-3 text-left">Bearer Token</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($instansiTokens as $index => $instansi)
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $instansi->nama_instansi }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="w-full bg-gray-50 text-gray-600 px-2 py-1 rounded" 
                                   value="{{ $instansi->bearer_token }}" readonly>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="border border-gray-300 px-4 py-3 text-center text-gray-500">
                            Tidak ada data instansi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
