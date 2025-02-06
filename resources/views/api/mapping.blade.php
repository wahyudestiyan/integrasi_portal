@extends('layouts.app')

@section('content')
<style>
    /* Styling untuk Source Fields dan Target Fields */
    #sourceJson {
        width: 100%;
        height: 200px;
        font-family: monospace;
        white-space: pre;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        resize: vertical;
    }

    #targetFields {
        list-style: none;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        min-height: 200px;
        margin-bottom: 20px;
    }

    .target-item {
        padding: 10px;
        margin: 5px 0;
        background-color: #ffffff;
        border: 1px dashed #ccc;
        border-radius: 5px;
    }

    .target-item .remove-field {
        margin-left: 10px;
    }

    #jsonPreview {
        margin-top: 20px;
        background-color: #f0f0f0;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        min-height: 100px;
    }

    #jsonPreview pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        background-color: #fff;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: monospace;
    }
</style>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="container">
    <div class="row">
        <!-- Source Fields sebagai Textarea -->
        <div class="col-md-6">
            <h4>Source Fields (Response Data)</h4>
            <textarea id="sourceJson" readonly>{{ json_encode($sourceFields, JSON_PRETTY_PRINT) }}</textarea>
        </div>
      
        <!-- Target Fields sebagai Mapping -->
        <div class="col-md-6">
            <h4>Target Fields (Mapping)</h4>
            <form action="{{ route('api.mapping.save', ['apiId' => $api->id]) }}" method="POST">
                @csrf
                <ul id="targetFields">
                    @foreach($sourceFields[0] as $sourceField => $value)
                        <li class="target-item" data-field="{{ $sourceField }}">
                            <label for="target_field_{{ $sourceField }}">{{ $sourceField }}</label>
                            <input type="text" class="form-control" 
                                id="target_field_{{ $sourceField }}" 
                                name="target_fields[{{ $sourceField }}]" 
                                placeholder="Enter Target Field" 
                                value="{{ old('target_fields.' . $sourceField, $sourceField) }}">

                            <!-- <input type="checkbox" class="remove-column" id="remove_{{ $sourceField }}" data-field="{{ $sourceField }}"> -->
                            <!-- <label for="remove_{{ $sourceField }}">Remove</label> -->
                            <button type="button" class="btn btn-danger btn-sm remove-field" data-field="{{ $sourceField }}">Remove</button>
                        </li>
                    @endforeach
                </ul>
                <button type="submit" class="btn btn-success mt-3">Save Mapping</button>
                <a href="{{ route('api.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </form>
        </div>
    </div>

    <!-- Tombol Generate Format -->
    <button id="generateFormatButton" class="btn btn-primary mt-3">Generate Format</button>

    <!-- Tempat untuk menampilkan preview JSON -->
    <div id="jsonPreview">
        <h4>Preview JSON Format</h4>
        <pre id="previewContent"></pre>
    </div>
</div>

<script>
document.getElementById('generateFormatButton').addEventListener('click', () => {
    try {
        // Ambil data JSON mentah dari textarea
        const rawJson = document.getElementById('sourceJson').value;

        // Cek apakah JSON valid
        let sourceData;
        try {
            sourceData = JSON.parse(rawJson); // Coba parse JSON
        } catch (e) {
            throw new Error('Invalid JSON format in Source Fields.');
        }

        const formattedData = {
            "data_id": "{{ $api->id_data }}", // Ambil data_id dari API
            "tahun_data": new Date().getFullYear() - 1, // Tahun saat ini dikurangi 1
            "data": []
        };

        // Ambil daftar kolom yang akan dihapus berdasarkan checkbox
        const columnsToRemove = [];
        document.querySelectorAll('.remove-column:checked').forEach(checkbox => {
            columnsToRemove.push(checkbox.getAttribute('data-field'));
        });

        // Fungsi untuk memproses setiap row data dari JSON dan membentuk format sesuai kebutuhan
        const processRowData = (row) => {
            let rowData = {};

            // Hapus kolom yang dipilih dari row
            columnsToRemove.forEach(column => {
                delete row[column];
            });

            // Ambil semua field yang ada di target
            document.querySelectorAll('.target-item input').forEach(input => {
                const field = input.getAttribute('id').replace('target_field_', '');
                const targetField = input.value.trim() || field; // Default ke field aslinya jika kosong
                if (row.hasOwnProperty(field)) {
                    rowData[targetField] = row[field];
                }
            });

            return rowData;
        };

        // Tangani jika data memiliki beberapa level atau nested array
        if (Array.isArray(sourceData)) {
            // Iterasi jika data adalah array
            sourceData.forEach(row => {
                formattedData.data.push(processRowData(row));
            });
        } else if (typeof sourceData === 'object') {
            // Jika data berupa objek, kita harus mengakses field yang ada di dalamnya
            for (const key in sourceData) {
                if (Array.isArray(sourceData[key])) {
                    // Jika field berupa array, iterasi array tersebut
                    sourceData[key].forEach(row => {
                        formattedData.data.push(processRowData(row));
                    });
                } else if (typeof sourceData[key] === 'object') {
                    // Jika field berupa objek, iterasi objek tersebut
                    formattedData.data.push(processRowData(sourceData[key]));
                }
            }
        }

        // Menampilkan hasil JSON pada preview
        document.getElementById('previewContent').textContent = JSON.stringify(formattedData, null, 4);
        document.getElementById('jsonPreview').style.display = 'block';
    } catch (error) {
        alert('Invalid JSON format in Source Fields.');
    }
});

// Event listener untuk tombol "Remove"
document.querySelectorAll('.remove-field').forEach(button => {
    button.addEventListener('click', (event) => {
        event.target.closest('li').remove();
    });
});
</script>
@endsection