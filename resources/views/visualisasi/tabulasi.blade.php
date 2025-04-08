@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6" x-data="tableHandler">
    <h1 class="text-xl font-semibold mb-4">Detail Data</h1>
    
    <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-lg font-semibold mb-4">
            <strong>Nama Instansi:</strong> {{ $instansi->nama_instansi }}
        </h2>
        <h3 class="text-md font-semibold mb-4">
            <strong>Judul Data:</strong> {{ $judul }}
        </h3>

        @if (!empty($detailData) && count($detailData) > 0)
    <h2 class="text-lg font-bold mb-2">Data Mentah (Raw Data)</h2>
    <div class="overflow-x-auto mb-5">
        <div class="relative border rounded-lg" style="max-height: 400px; overflow-y: auto;">
            <table class="w-full border-collapse border">
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
@endif
<h2 class="text-lg font-bold mb-2">Data Hasil Transformasi</h2>
        <div x-data="tableHandler">
            <!-- Pilih Data yang Akan Ditampilkan -->
            <div class="mt-4">
                <h3 class="text-lg font-semibold mb-2">Pilih Data yang Akan Ditampilkan</h3>
                <select x-model="selectedDataColumn" @change="updateTableHeaders()" class="border p-2 rounded">
                    <option value="">Pilih Data</option>
                    <template x-for="column in columns" :key="column.key">
                        <option :value="column.key" x-text="column.label"></option>
                    </template>
                </select>
            </div>

            <!-- Pilih Kategori -->
            <div class="mt-4">
                <h3 class="text-lg font-semibold mb-2">Pilih Kategori</h3>
                <select x-model="categoryColumn" @change="updateTableHeaders()" class="border p-2 rounded">
                    <option value="">Pilih Kategori</option>
                    <template x-for="column in columns" :key="column.key">
                        <option :value="column.key" x-text="column.label"></option>
                    </template>
                </select>
            </div>

            <!-- Pilih Kolom Sebagai Header -->
            <div class="mt-4">
                <h3 class="text-lg font-semibold mb-2">Pilih Kolom sebagai Header</h3>
                <div class="space-y-2">
                    <template x-for="(header, index) in headers" :key="index">
                        <div class="flex items-center space-x-2">
                            <select x-model="header.column" @change="updateValues(index)" class="border p-2 rounded">
                                <option value="">Pilih Kolom</option>
                                <template x-for="column in columns" :key="column.key">
                                    <option :value="column.key" x-text="column.label"></option>
                                </template>
                            </select>
                            
                            <select x-model="header.value" @change="updateTableHeaders()" class="border p-2 rounded">
                                <option value="">Pilih Nilai</option>
                                <template x-for="value in header.values" :key="value">
                                    <option :value="value" x-text="value"></option>
                                </template>
                            </select>

                            <button @click="removeHeader(index)" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                        </div>
                    </template>
                </div>
                <button @click="addHeader()" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Tambah Header</button>
            </div>
            <div class="mt-4">
            <button @click="exportToExcel()"  class="px-4 py-2 bg-green-500 text-white rounded">
            <i class="fas fa-file-excel mr-2"></i> Unduh Excel
            </button>
            </div>
            <!-- Tabel Data -->
            <div class="overflow-x-auto mt-4">
                <h3 class="text-lg font-semibold mb-2">Tabel Data</h3>
                <table class="w-full border-collapse border">
                    <thead class="bg-gray-200 text-center">
                        <tr>
                        <th class="border p-2" x-text="kategoriHeader"></th>
                            <template x-for="header in tableHeaders" :key="header">
                                <th class="border p-2" x-text="header"></th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index) in transformedData" :key="index">
                            <tr>
                            <td class="border p-2" x-text="row[kategoriHeader]"></td>
                                <template x-for="header in tableHeaders" :key="header">
                                    <td class="border p-2 text-center" x-text="row[header] || '-'" ></td>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
       
    <a href="{{ url()->previous() }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Kembali</a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.addEventListener("alpine:init", () => {
    Alpine.data("tableHandler", () => ({
        columns: [],
        headers: [],
        data: @json($detailData),
        categoryColumn: "",
        selectedDataColumn: "",
        tableHeaders: [],
        transformedData: [],
        kategoriHeader: "Kategori",

        init() {
            console.log("Data Masuk:", this.data);
            if (this.data.length === 0) return;

            this.columns = Object.keys(this.data[0]).map(key => ({
                key,
                label: key.replace(/_/g, ' ').toUpperCase()
            }));

            console.log("Kolom yang Tersedia:", this.columns);
        },

        addHeader() {
            this.headers.push({ column: "", value: "", values: [] });
        },

        removeHeader(index) {
            this.headers.splice(index, 1);
            this.updateTableHeaders();
        },

        updateValues(index) {
            let columnKey = this.headers[index].column;
            if (!columnKey) return;

            let values = [...new Set(this.data.map(row => row[columnKey]))];
            this.headers[index].values = values;
        },

        updateTableHeaders() {
            if (!this.categoryColumn || !this.selectedDataColumn) {
                console.warn("Kategori atau Data yang Dipilih Kosong!");
                return;
            }

            console.log("Kategori Terpilih:", this.categoryColumn);
            console.log("Data yang Ditampilkan:", this.selectedDataColumn);

            // Menentukan header kategori yang akan digunakan
            this.kategoriHeader = this.columns.find(col => col.key === this.categoryColumn)?.label || "Kategori";

            // Set header tabel sesuai dengan data tahun yang dipilih
            this.tableHeaders = this.headers
                .map(h => (h.value ? h.value : h.column))
                .filter(v => v);

            let groupedData = {};

            this.data.forEach(row => {
                let kategori = (row[this.categoryColumn] || "Uncategorized").toLowerCase().trim(); // Normalisasi ke lowercase

                if (!groupedData[kategori]) {
                    groupedData[kategori] = {};
                    groupedData[kategori][this.kategoriHeader] = row[this.categoryColumn]; // Simpan dengan tampilan asli
                }

                this.headers.forEach(header => {
                    let tahun = header.value || header.column;
                    let nilai = row[this.selectedDataColumn];

                    if (row["tahun_data"] == tahun) { 
                        groupedData[kategori][tahun] = nilai ?? "-";
                    }
                });
            });


            this.transformedData = Object.values(groupedData);
            console.log("Hasil Transformasi Data:", this.transformedData);
        },

        // ✅ FUNGSI EXPORT KE EXCEL DIPINDAHKAN KE DALAM ALPINE
        exportToExcel() {
            if (!window.XLSX) {
                console.error("SheetJS (xlsx) belum dimuat!");
                return;
            }

            let data = [];

            // Ambil Header Tabel
            let headers = [this.kategoriHeader, ...this.tableHeaders];

            // Tambahkan ke array data
            data.push(headers);

            // Ambil Data Per Baris
            this.transformedData.forEach(row => {
                let rowData = headers.map(header => row[header] ?? "-"); // Jika tidak ada data, pakai "-"
                data.push(rowData);
            });

            // Buat Worksheet & Workbook
            let ws = XLSX.utils.aoa_to_sheet(data);
            let wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Data");

            // Simpan File Excel
            XLSX.writeFile(wb, "Data_Export.xlsx");
        }
    }));
});
</script>


@endsection