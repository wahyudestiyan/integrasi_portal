@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">Detail Data</h1>

    <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-lg font-semibold mb-4">
            <strong>Nama Instansi:</strong> {{ $instansi->nama_instansi }}
        </h2>
        <h3 class="text-md font-semibold mb-4">
            <strong>Judul Data:</strong> {{ $judul }}
        </h3>
        <button id="downloadExcelMain" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">
                            <i class="fas fa-file-excel text-green-500 mr-2"></i> Unduh Excel
                            </button>
        @if (!empty($detailData) && count($detailData) > 0)
            <div class="overflow-x-auto">
                <div class="relative border rounded-lg" style="max-height: 500px; overflow-y: auto;">
                    <table id="dataTable" class="w-full border-collapse border">
                        <thead class="bg-gray-200 sticky top-0 text-center">
                            <tr>
                                @foreach(array_keys($detailData[0]) as $key)
                                    <th class="border p-2 sticky top-0 bg-gray-200">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailData as $item)
                            <tr>
                                @foreach($item as $value)
                                    <td class="border p-2 text-center">
                                        {{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-2">Tambah Filter</h3>
                <div id="filterContainer"></div>
                <button id="addFilter" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Tambah Filter</button>
                <button id="applyFilters" class="mt-4 bg-green-500 text-white px-4 py-2 rounded hidden">Terapkan Filter</button>
            </div>

            <div class="mt-6 hidden" id="filteredTableContainer">
                <h3 class="text-lg font-semibold mb-2">Hasil Filter</h3>
                <button id="downloadExcelFiltered" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">
                <i class="fas fa-file-excel text-green-500 mr-2"></i> Unduh Excel 
                </button>
                <table id="filteredTable" class="w-full border-collapse border bg-white">
                    <thead class="bg-gray-200"></thead>
                    <tbody></tbody>
                </table>
            </div>
        @else
            <p class="text-center">Tidak ada data yang tersedia</p>
        @endif
    </div>

    <a href="{{ url()->previous() }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Kembali</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function exportTableToExcel(tableId, fileName) {
            let table = document.getElementById(tableId);
            let ws = XLSX.utils.table_to_sheet(table);
            let wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
            XLSX.writeFile(wb, fileName);
        }

        document.getElementById("downloadExcelMain").addEventListener("click", function () {
            exportTableToExcel("dataTable", "data_main.xlsx");
        });

        document.getElementById("downloadExcelFiltered").addEventListener("click", function () {
            exportTableToExcel("filteredTable", "data_filtered.xlsx");
        });

        const addFilterBtn = document.getElementById("addFilter");
        const applyFiltersBtn = document.getElementById("applyFilters");
        const filterContainer = document.getElementById("filterContainer");
        const filteredTableContainer = document.getElementById("filteredTableContainer");
        const downloadFilteredBtn = document.getElementById("downloadExcelFiltered");

        addFilterBtn.addEventListener("click", function () {
            let filterDiv = document.createElement("div");
            filterDiv.classList.add("flex", "items-center", "gap-2", "mt-2");

            let select = document.createElement("select");
            select.classList.add("border", "p-2", "rounded", "w-1/3");
            document.querySelectorAll("#dataTable thead th").forEach((header, index) => {
                let option = document.createElement("option");
                option.value = index;
                option.textContent = header.textContent.trim();
                select.appendChild(option);
            });

            let input = document.createElement("input");
            input.type = "text";
            input.classList.add("border", "p-2", "rounded", "w-1/3");
            input.placeholder = "Masukkan nilai filter";

            let removeBtn = document.createElement("button");
            removeBtn.textContent = "Hapus";
            removeBtn.classList.add("bg-red-500", "text-white", "px-3", "py-1", "rounded");
            removeBtn.addEventListener("click", () => filterDiv.remove());

            filterDiv.appendChild(select);
            filterDiv.appendChild(input);
            filterDiv.appendChild(removeBtn);
            filterContainer.appendChild(filterDiv);
            applyFiltersBtn.classList.remove("hidden");
        });

        applyFiltersBtn.addEventListener("click", function () {
            let filters = Array.from(filterContainer.children).map(filterDiv => {
                return {
                    columnIndex: parseInt(filterDiv.children[0].value),
                    value: filterDiv.children[1].value.toLowerCase()
                };
            });

            let rows = Array.from(document.querySelectorAll("#dataTable tbody tr"));
            let filteredData = rows.filter(row => {
                return filters.every(filter => {
                    let cellText = row.cells[filter.columnIndex].textContent.toLowerCase();
                    return cellText.includes(filter.value);
                });
            });

            let filteredTable = document.getElementById("filteredTable");
            filteredTable.innerHTML = "<thead><tr>" + document.getElementById("dataTable").querySelector("thead").innerHTML + "</tr></thead><tbody></tbody>";
            filteredData.forEach(row => {
                filteredTable.querySelector("tbody").appendChild(row.cloneNode(true));
            });
            
            if (filteredData.length > 0) {
                filteredTableContainer.classList.remove("hidden");
                downloadFilteredBtn.classList.remove("hidden");
            } else {
                filteredTableContainer.classList.add("hidden");
                downloadFilteredBtn.classList.add("hidden");
            }
        });
    });
</script>
@endsection
