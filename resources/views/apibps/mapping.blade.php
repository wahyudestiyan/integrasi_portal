@extends('layouts.app')

@section('content')
<style>
    #vervar-options {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ced4da;
        padding: 10px;
        border-radius: 5px;
    }

    #turvar-options {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ced4da;
        padding: 10px;
        border-radius: 5px;
    }
    
    #turtahun-options {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ced4da;
        padding: 10px;
        border-radius: 5px;
    }


    #preview-content {
        max-height: 250px;
        overflow-y: auto;
        white-space: pre-wrap;
    }
    
        .drag-handle {
            cursor: grab;
            margin-right: 10px;
        }
        .sortable-ghost {
            opacity: 0.5;
            background: #f0f0f0;
        }

        #targetFields {
    border: 1px solid #ccc;
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
    border-radius: 5px;
    background: #f9f9f9;
}


</style>
<div class="container">
    <h2 class="fs-4 mb-4">Nama Data : <strong>{{ $apiBps->nama_data }}</strong></h2>

    <div class="row">
        <!-- Kolom kiri: Response API BPS -->
        <div class="col-md-6">
            <h4><strong>Response API BPS</strong></h4>
            <textarea class="form-control" rows="25" readonly>{{ json_encode($responseData, JSON_PRETTY_PRINT) }}</textarea>
        </div>

        <!-- Kolom kanan: Dropdown Mapping dan Preview -->
        <div class="col-md-6">
            <h4><strong>Mapping Fields</strong></h4>
            <form id="mapping-form">
                @csrf
                
                <div class="mb-3">
                    <label for="var">Nama Data</label>
                    <select name="var" id="var" class="form-select" required>
                        <option value=""> - Pilih -</option>
                        @foreach($responseData['var'] as $v)
                            <option value="{{ $v['val'] }}" data-label="{{ $v['label'] }}">{{ $v['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="vervar">Nama Vervar</label>
                    <div>
                        <input type="checkbox" id="select-all-vervar"> <label for="select-all-vervar"><strong>Pilih Semua</strong></label>
                    </div>
                    <div id="vervar-options">
                        @foreach($responseData['vervar'] as $ver)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input vervar-checkbox" name="vervar[]" value="{{ $ver['val'] }}" id="vervar_{{ $ver['val'] }}">
                                <label class="form-check-label" for="vervar_{{ $ver['val'] }}">{{ $ver['label'] }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-3">
                    <label for="turvar">Nama Tururnan Variabel</label>
                    <div>
                        <input type="checkbox" id="select-all-turvar"> <label for="select-all-turvar"><strong>Pilih Semua</strong></label>
                    </div>
                <div id="turvar-options">
                        @foreach($responseData['turvar'] as $tv)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input turvar-checkbox" name="turvar[]" value="{{ $tv['val'] }}" id="turvar_{{ $tv['val'] }}">
                                <label class="form-check-label" for="turvar_{{ $tv['val'] }}">{{ $tv['label'] }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- <div class="mb-3">
                    <label for="turvar">Turunan Variabel</label>
                    <select name="turvar" id="turvar" class="form-select">
                        <option value="0">- Pilih -</option>
                        @foreach($responseData['turvar'] as $tv)
                            <option value="{{ $tv['val'] }}">{{ $tv['label'] }}</option>
                        @endforeach
                    </select>
                </div> -->

                <div class="mb-3">
                    <label for="tahun">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select" required>
                        <option value="">- Pilih -</option>
                        @foreach($responseData['tahun'] as $th)
                            <option value="{{ $th['val'] }}" data-label="{{ $th['label'] }}">{{ $th['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="turtahun">Nama Tururnan Tahun</label>
                    <div>
                        <input type="checkbox" id="select-all-turtahun"> <label for="select-all-turtahun"><strong>Pilih Semua</strong></label>
                    </div>
                <div id="turtahun-options">
                        @foreach($responseData['turtahun'] as $tt)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input turtahun-checkbox" name="turtahun[]" value="{{ $tt['val'] }}" id="turtahun_{{ $tt['val'] }}">
                                <label class="form-check-label" for="turtahun_{{ $tt['val'] }}">{{ $tt['label'] }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- <div class="mb-3">
                    <label for="turtahun">Turtahun</label>
                    <select name="turtahun" id="turtahun" class="form-select">
                        <option value="0"> - Pilih -</option>
                        @foreach($responseData['turtahun'] as $tt)
                            <option value="{{ $tt['val'] }}">{{ $tt['label'] }}</option>
                        @endforeach
                    </select>
                </div> -->

                <button type="button" id="btn-preview" class="btn btn-primary">Lihat</button>
                <button type="button" id="btn-generate" class="btn btn-success">Generate</button>
            </form>

            <!-- Hasil Preview langsung di bawah tombol -->
            <div class="mt-3">
                <h4><strong>Hasil Preview</strong></h4>
                <pre id="preview-content" class="border p-3 bg-light"></pre>
            </div>
        </div>
    </div>

    <div class="container mt-4">
    <div class="row">
        <!-- Kolom Kiri: Hasil Generate JSON -->
        <div class="col-md-6">
            <h4><strong>Hasil Generate</strong></h4>
            <form action="{{ route('apibps.saveMapping', ['apibps_id' => $apiBps->id]) }}" method="POST">
                @csrf
                <textarea id="generate-content" name="jsonhasil" class="form-control" rows="10" readonly></textarea>
                
                <button type="submit" id="btn-save" class="btn btn-success mt-2">Simpan</button>
            </form>
            <button id="btn-edit-attributes" class="btn btn-warning mt-2">Edit Atribut</button>
        </div>

        <!-- Kolom Kanan: Mapping Atribut JSON -->
        <div class="col-md-6">
            <h4><strong>Mapping Atribut JSON</strong></h4>
            <ul id="targetFields" class="list-group"></ul>
            <button id="btn-update-json" class="btn btn-warning mt-3">Ubah</button>
        </div>
    </div>
</div>

<script>
   document.getElementById('select-all-vervar').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.vervar-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
document.getElementById('select-all-turvar').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.turvar-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
document.getElementById('select-all-turtahun').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.turtahun-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

document.getElementById('btn-preview').addEventListener('click', function() {
    let vervarValues = Array.from(document.querySelectorAll('.vervar-checkbox:checked')).map(cb => cb.value);
    let turvarValues = Array.from(document.querySelectorAll('.turvar-checkbox:checked')).map(cb => cb.value);
    let turtahunValues = Array.from(document.querySelectorAll('.turtahun-checkbox:checked')).map(cb => cb.value);
    let variabel = document.getElementById('var').value;
    let tahun = document.getElementById('tahun').value;

    if (!variabel || !tahun || vervarValues.length === 0 || turvarValues.length === 0 || turtahunValues.length === 0) {
        alert("Pastikan semua field terisi dan pilih minimal 1 vervar, turvar & turtahun!");
        return;
    }

    let previewText = "";
    let dataContent = @json($responseData['datacontent']);

    // Loop vervar + turvar + turtahun
    vervarValues.forEach(vervar => {
        turvarValues.forEach(turvar => {
            turtahunValues.forEach(turtahun => {
                let generatedId = `${vervar}${variabel}${turvar}${tahun}${turtahun}`;
                let nilai = dataContent[generatedId] ?? "-";
                previewText += `ID: ${generatedId}\nNilai: ${nilai}\n\n`;
            });
        });
    });

    document.getElementById('preview-content').textContent = previewText;
});

document.getElementById('btn-generate').addEventListener('click', function() {
    let idData = "{{ $apiBps->id_data }}";
    let tahunDropdown = document.getElementById('tahun');
    let tahunData = tahunDropdown.options[tahunDropdown.selectedIndex].getAttribute('data-label');
    
    let vervarValues = Array.from(document.querySelectorAll('.vervar-checkbox:checked')).map(cb => cb.value);
    let turvarValues = Array.from(document.querySelectorAll('.turvar-checkbox:checked')).map(cb => cb.value);
    let turtahunValues = Array.from(document.querySelectorAll('.turtahun-checkbox:checked')).map(cb => cb.value);
    let variabel = document.getElementById('var').value;
    let tahun = document.getElementById('tahun').value;

    if (!variabel || !tahun || vervarValues.length === 0 || turvarValues.length === 0 || turtahunValues.length === 0) {
        alert("Pastikan semua field terisi!");
        return;
    }

    let dataContent = @json($responseData['datacontent']);
    let vervarList = @json($responseData['vervar']);
    let turvarList = @json($responseData['turvar']);
    let turtahunList = @json($responseData['turtahun']);

    let hasilGenerate = {
        "data_id": idData,
        "tahun_data": tahunData,
        "data": []
    };

    // Loop vervar + turvar + turtahun
    vervarValues.forEach(vervar => {
        turvarValues.forEach(turvar => {
            turtahunValues.forEach(turtahun => {
                let generatedId = `${vervar}${variabel}${turvar}${tahun}${turtahun}`;
                let nilai = dataContent[generatedId] ?? "-";

                let vervarData = vervarList.find(item => item.val == parseInt(vervar));
                let turvarData = turvarList.find(item => item.val == parseInt(turvar));
                let turtahunData = turtahunList.find(item => item.val == parseInt(turtahun));

                let labelVervar = vervarData ? vervarData.label : `Kode ${vervar}`;
                let labelTurvar = turvarData ? turvarData.label : `Kode ${turvar}`;
                let labelTurtahun = turtahunData ? turtahunData.label : `Kode ${turtahun}`;

                let dataEntry = {
                    "vervar_val": parseInt(vervar),
                    "vervar_label": labelVervar,
                    "turvar_val": parseInt(turvar),
                    "turvar_label": labelTurvar,
                    "turtahun_val": parseInt(turtahun),
                    "turtahun_label": labelTurtahun
                };
                dataEntry[generatedId] = nilai;

                hasilGenerate.data.push(dataEntry);
            });
        });
    });

    let jsonString = JSON.stringify(hasilGenerate, null, 4);
    document.getElementById('generate-content').value = jsonString;
    document.getElementById('btn-send').disabled = false;
});



//update atribut
function updateJsonFields(jsonData) {
    let targetFieldsContainer = document.getElementById('targetFields');
    targetFieldsContainer.innerHTML = ""; // Kosongkan sebelum render ulang

    if (!jsonData || !jsonData.data || !Array.isArray(jsonData.data)) {
        console.warn("JSON tidak valid.");
        return;
    }

    let allKeys = new Set();
    let idFields = new Set();

    jsonData.data.forEach(item => {
        Object.keys(item).forEach(key => {
            allKeys.add(key);
            if (/^\d{16}$/.test(key)) { // Jika key adalah angka panjang (16 digit)
                idFields.add(key);
            }
        });
    });

    allKeys.forEach(field => {
        let listItem = document.createElement('li');
        listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
        listItem.dataset.field = field;

        listItem.innerHTML = `
            <input type="text" class="form-control w-75 field-input" id="target_field_${field}" 
                name="target_fields[${field}]" value="${field}" data-original="${field}">
            <button type="button" class="btn btn-danger btn-sm btn-delete-field" data-field="${field}">Hapus</button>
        `;

        targetFieldsContainer.appendChild(listItem);
    });
}

// Daftar key yang dihapus
let deletedFields = new Set();

// **Gunakan Event Delegation untuk Menangani Klik Tombol Hapus**
document.getElementById('targetFields').addEventListener('click', function (event) {
    if (event.target.classList.contains('btn-delete-field')) {
        let fieldToDelete = event.target.dataset.field;
        let listItem = document.querySelector(`[data-field="${fieldToDelete}"]`);

        if (listItem) {
            listItem.remove();
            deletedFields.add(fieldToDelete); // Tambahkan ke daftar key yang harus dihapus dari JSON
        }
    }
});

// Jika ada perubahan pada ID panjang, ubah semuanya
// Jika ada perubahan pada ID panjang, ubah semuanya
document.getElementById('targetFields').addEventListener('input', function (event) {
    if (event.target.classList.contains('field-input')) {
        let original = event.target.dataset.original;
        let newValue = event.target.value.trim();

        // Jika key yang asli adalah angka panjang (10 digit atau lebih)
        if (/^\d{5,}$/.test(original)) {
            document.querySelectorAll('.field-input').forEach(otherInput => {
                if (otherInput.dataset.original === original || /^\d{5,}$/.test(otherInput.dataset.original)) {
                    otherInput.value = newValue; // Ubah semua angka panjang yang lain
                }
            });
        }
    }
});


// Tambahkan fitur Drag and Drop menggunakan Sortable.js atau Native Drag & Drop
document.addEventListener("DOMContentLoaded", function () {
    let targetFieldsContainer = document.getElementById('targetFields');

    // Gunakan Sortable.js untuk Drag & Drop (Jika sudah ada library)
    if (typeof Sortable !== 'undefined') {
        new Sortable(targetFieldsContainer, {
            animation: 150,
            onEnd: function (evt) {
                console.log("Urutan diubah:", getUpdatedOrder());
            }
        });
    } else {
        console.warn("Sortable.js tidak ditemukan, pastikan library sudah dimuat.");
    }
});

// Fungsi untuk mendapatkan urutan terbaru dari elemen di DOM
function getUpdatedOrder() {
    let updatedOrder = [];
    document.querySelectorAll('#targetFields .field-input').forEach(input => {
        updatedOrder.push(input.value.trim());
    });
    return updatedOrder;
}
// Fungsi untuk memperbarui JSON berdasarkan input pengguna
document.getElementById('btn-update-json').addEventListener('click', function () {
    let newJsonData = JSON.parse(document.getElementById('generate-content').value);
    let updatedKeys = {};

    // Ambil urutan terbaru field dari DOM (hasil drag & drop)
    let updatedOrder = Array.from(document.querySelectorAll('#targetFields li'))
    .map(li => {
        let input = li.querySelector('.field-input');
        return input ? input.value.trim() : '';
    });

    // Mapping nama field lama ke yang baru
    document.querySelectorAll('#targetFields .field-input').forEach(input => {
        let originalField = input.dataset.original;
        let newField = input.value.trim();
        if (newField) {
            updatedKeys[originalField] = newField;
        }
    });

    // Buat ulang data JSON berdasarkan urutan field baru
    newJsonData.data = newJsonData.data.map(item => {
    let newItem = {};

    // Tambahkan field berdasarkan urutan baru (drag n drop)
    updatedOrder.forEach(key => {
        let originalKey = Object.keys(updatedKeys).find(k => updatedKeys[k] === key);
        let actualKey = originalKey || key;

        if (item.hasOwnProperty(actualKey) && !deletedFields.has(actualKey)) {
            newItem[key] = item[actualKey];
        }
    });

    // Tambahkan sisa field yang belum disentuh (tidak dihapus dan belum ditambahkan)
    Object.keys(item).forEach(key => {
        if (!deletedFields.has(key)) {
            let renamedKey = updatedKeys[key] || key;
            if (!newItem.hasOwnProperty(renamedKey)) {
                newItem[renamedKey] = item[key];
            }
        }
    });

    return newItem;
});


    document.getElementById('generate-content').value = JSON.stringify(newJsonData, null, 4);
    alert("JSON berhasil diperbarui!");
});

// Panggil update hanya saat tombol "Edit Atribut" ditekan
document.getElementById('btn-edit-attributes').addEventListener('click', function () {
    let jsonContent = document.getElementById('generate-content').value;
    if (jsonContent.trim()) {
        try {
            let jsonData = JSON.parse(jsonContent);
            deletedFields.clear(); // Reset daftar key yang dihapus
            updateJsonFields(jsonData);
        } catch (e) {
            console.warn("Format JSON tidak valid! Periksa kembali.");
        }
    } else {
        console.warn("Textarea kosong! Harap isi JSON terlebih dahulu.");
    }
});


</script>
@endsection
