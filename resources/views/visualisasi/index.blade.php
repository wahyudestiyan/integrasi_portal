@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">Daftar Data Instansi Jateng pada Portal Data Jawa Tengah</h1>

    <form method="GET" action="{{ route('visualisasi.index') }}">
        <select name="instansiId" class="border p-2 rounded w-full" onchange="this.form.submit()">
            <option value="">-- Pilih Instansi --</option>
            @foreach($instansiTokens as $instansi)
                <option value="{{ $instansi->id }}" 
                    {{ request('instansiId') == $instansi->id ? 'selected' : '' }}>
                    {{ $instansi->nama_instansi }}
                </option>
            @endforeach
        </select>
    </form>

    @if(isset($instansiId) && isset($data) && count($data) > 0)
        <div class="p-4 border rounded-lg mt-4">
            <h2 class="text-lg font-semibold mb-2">
                {{ $instansiTokens->where('id', $instansiId)->first()->nama_instansi ?? 'Instansi Tidak Diketahui' }}
                memiliki {{ count($data) }} Judul Data
            </h2>

            <div class="relative border rounded-lg" style="max-height: 500px; overflow: hidden; position: relative;">
                <!-- Tabel Header -->
                <table class="w-full border-collapse border" style="table-layout: fixed;">
                    <thead class="bg-gray-200 sticky top-0 z-50">
                        <tr>
                            <th class="border p-2 bg-gray-300 text-center" style="width: 10%;"></th>
                            <th class="border p-2 bg-gray-200 text-center" style="width: 60%;">
                                <input type="text" class="w-full p-1 border text-center" placeholder="Cari Judul Data" onkeyup="filterTable(this, 1)">
                            </th>
                            <th class="border p-2 bg-gray-300 text-center" style="width: 30%;"></th>
                        </tr>
                        <tr>
                            <th class="border p-2 bg-gray-300 text-center">No</th>
                            <th class="border p-2 bg-gray-300 text-center">Judul Data</th>
                            <th class="border p-2 bg-gray-300 text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>

                <!-- Wrapper untuk Scroll -->
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="w-full border-collapse border" style="table-layout: fixed;">
                        <tbody>
                            @foreach($data as $index => $item)
                            <tr>
                                <td class="border p-2 text-center" style="width: 10%;">{{ $index + 1 }}</td>
                                <td class="border p-2 text-center" style="width: 60%;">{{ $item['judul'] }}</td>
                                <td class="border p-2 text-center" style="width: 30%;">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('visualisasi.detail', ['instansiId' => $instansiId, 'dataId' => $item['id']]) }}" 
                                            class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600 transition">
                                            Lihat Data
                                        </a>
                                        <a href="{{ route('visualisasi.tabulasi', ['instansiId' => $instansiId, 'dataId' => $item['id']]) }}"
                                         class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600 transition">
                                            Tabulasi
                                        </a>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif(isset($instansiId))
        <p class="mt-4 text-gray-500">Tidak ada data tersedia untuk instansi ini.</p>
    @endif
</div>

<script>
    function filterTable(input, columnIndex) {
        let filter = input.value.toLowerCase();
        let table = document.querySelector("tbody");
        let rows = table.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            let cell = rows[i].getElementsByTagName("td")[columnIndex];
            if (cell) {
                let textValue = cell.textContent || cell.innerText;
                if (textValue.toLowerCase().includes(filter)) {
                    rows[i].style.visibility = "visible";
                    rows[i].style.height = "auto";
                } else {
                    rows[i].style.visibility = "collapse";
                    rows[i].style.height = "0";
                }
            }
        }
    }
</script>
@endsection
