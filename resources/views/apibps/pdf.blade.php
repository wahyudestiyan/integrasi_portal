<!DOCTYPE html>
<html>
<head>
    <title>REKAP API OPD</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 10mm;
            font-size: 12px; /* Ukuran font keseluruhan */
        }
        h2 {
            text-align: center;
            font-size: 16px; /* Ukuran font untuk judul */
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid black; 
            padding: 8px; 
            text-align: left; 
            font-size: 11px; /* Ukuran font tabel */
        }
        th { 
            background-color: #f2f2f2; 
            font-size: 12px; /* Ukuran font header */
            text-align: center;
        }
        .status-terkirim {
            color: green;
            font-weight: bold;
            font-size: 11px; /* Ukuran font untuk status */
        }
        .status-belum-terkirim {
            color: red;
            font-weight: bold;
            font-size: 11px; /* Ukuran font untuk status */
        }
    </style>
</head>
<body>
    <h1>Rekap API BPS</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Instansi</th>
                <th>Nama Data</th>
                <th>URL API</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($apibps as $index => $api)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $api->nama_instansi }}</td>
                    <td>{{ $api->nama_data }}</td>
                    <td>{{ $api->url_api }}</td>
                    <td>{{ $api->method }}</td>
                    <td>
                        <span class="{{ $api->status == 'Terkirim' ? 'status-terkirim' : 'status-belum-terkirim' }}">
                            {{ $api->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
