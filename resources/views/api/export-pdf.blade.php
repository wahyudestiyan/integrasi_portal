<!DOCTYPE html>
<html>
<head>
    <title>Rekap API</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Rekap API</h2>
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
            @foreach ($apis as $index => $api)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $api->nama_instansi }}</td>
                    <td>{{ $api->nama_data }}</td>
                    <td>{{ $api->url_api }}</td>
                    <td>{{ $api->method }}</td>
                    <td>{{ $api->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
