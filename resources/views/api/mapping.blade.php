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
<h2 class="fs-4 mb-4">Nama Data : <strong>{{ $api->nama_data }}</strong></h2>
</div>
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
                <!-- Input untuk tahun_data -->
                <div class="form-group">
                    <label for="tahun_data">Tahun Data</label>
                    <input type="number" class="form-control" id="tahun_data" name="tahun_data" placeholder="Masukkan Tahun Data" value="{{ old('tahun_data', date('Y') - 1) }}">
                </div>

                <!-- Input tersembunyi untuk menyimpan urutan -->
                <input type="hidden" name="target_fields_order" id="target_fields_order" value="[]">

                <ul id="targetFields">
                    @foreach($sourceFields[0] as $sourceField => $value)
                        <li class="target-item" data-field="{{ $sourceField }}">
                            <label for="target_field_{{ $sourceField }}">{{ $sourceField }}</label>
                            <input type="text" class="form-control" 
                                id="target_field_{{ $sourceField }}" 
                                name="target_fields[{{ $sourceField }}]" 
                                placeholder="Enter Target Field" 
                                value="{{ old('target_fields.' . $sourceField, $sourceField) }}">

                            <button type="button" class="btn btn-danger btn-sm remove-field" data-field="{{ $sourceField }}">Remove</button>
                        </li>
                    @endforeach
                </ul>
                <button type="submit" class="btn btn-success mt-3">Save Mapping</button>
                <a href="{{ route('api.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                <a href="{{ route('export.mapping', ['apiId' => $api->id]) }}" class="btn btn-warning mt-3">Export Excel</a>
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
        const rawJson = document.getElementById('sourceJson').value;
        let sourceData;
        try {
            sourceData = JSON.parse(rawJson); 
        } catch (e) {
            throw new Error('Invalid JSON format in Source Fields.');
        }

        const tahunData = document.getElementById('tahun_data').value;

        const formattedData = {
            "data_id": "{{ $api->id_data }}", 
            "tahun_data": parseInt(tahunData), 
            "data": []
        };

        const columnsToRemove = [];
        document.querySelectorAll('.remove-column:checked').forEach(checkbox => {
            columnsToRemove.push(checkbox.getAttribute('data-field'));
        });

        const processRowData = (row) => {
            let rowData = {};
            columnsToRemove.forEach(column => {
                delete row[column];
            });

            document.querySelectorAll('.target-item input').forEach(input => {
                const field = input.getAttribute('id').replace('target_field_', '');
                const targetField = input.value.trim() || field; 
                if (row.hasOwnProperty(field)) {
                    rowData[targetField] = row[field];
                }
            });

            return rowData;
        };

        if (Array.isArray(sourceData)) {
            sourceData.forEach(row => {
                formattedData.data.push(processRowData(row));
            });
        } else if (typeof sourceData === 'object') {
            for (const key in sourceData) {
                if (Array.isArray(sourceData[key])) {
                    sourceData[key].forEach(row => {
                        formattedData.data.push(processRowData(row));
                    });
                } else if (typeof sourceData[key] === 'object') {
                    formattedData.data.push(processRowData(sourceData[key]));
                }
            }
        }

        document.getElementById('previewContent').textContent = JSON.stringify(formattedData, null, 4);
        document.getElementById('jsonPreview').style.display = 'block';
    } catch (error) {
        alert('Invalid JSON format in Source Fields.');
    }
});

document.querySelectorAll('.remove-field').forEach(button => {
    button.addEventListener('click', (event) => {
        event.target.closest('li').remove();
    });
});

// Sorting dengan SortableJS
document.addEventListener('DOMContentLoaded', function () {
    const targetFieldsList = document.getElementById('targetFields');
    new Sortable(targetFieldsList, {
        handle: '.target-item',
        animation: 150,
        onEnd: function (evt) {
            const order = Array.from(targetFieldsList.children).map((item) => item.getAttribute('data-field'));
            document.getElementById('target_fields_order').value = JSON.stringify(order);
        }
    });
});
</script>

@endsection
