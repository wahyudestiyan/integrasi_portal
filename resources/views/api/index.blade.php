    @extends('layouts.app')

    @section('title', 'Daftar API')

    @section('content')

    <style>
        table {
            table-layout: fixed;
            width: 100%;
        }
        th, td {
            word-wrap: break-word;
            white-space: normal;
        }
        .bg-red-500 {
            background-color: #f56565 !important;
        }
        .bg-blue-500 {
            background-color: #0A97B0 !important;
        }
        .bg-green-500 {
            background-color: #5CB338 !important;
        }
        .bg-gray-300 {
            background-color: #3E5879 !important;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .animated-alert {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .pagination {
            justify-content: flex-end;
        }
        .pagination .page-item .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .api-details {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .api-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .label {
            font-weight: bold;
            min-width: 150px;
        }

        .value {
            flex-grow: 1;
        }

        .api-item a {
            color: #007bff;
            text-decoration: none;
        }

        .api-item a:hover {
            text-decoration: underline;
        }

        .btn-submit {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
        .response-container {
            margin-top: 15px;
        }

        .response-box {
            max-height: 380px;
            overflow-y: auto;
            background-color: #000000;
            color: #00ff00;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
            <div class="mb-4 flex items-center space-x-4">
                <!-- Tombol Unduh Template (Kiri) -->
                <a href="{{ route('apis.download-template') }}" class="bg-blue-600 text-white py-2 px-4 rounded-md" style="text-decoration: none;">Unduh Template</a>
                
                <!-- Form Upload File Excel (Tengah) -->
                <form action="{{ route('apis.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                    @csrf
                    <!-- Input File -->
                    <div class="flex items-center space-x-2">
                        <label for="file" class="block text-sm font-medium text-gray-700">Upload File Excel--></label>
                        <input type="file" name="file" id="file" class="block border p-2 rounded-md" required>
                    </div>
                    
                    <!-- Tombol Upload (Kanan) -->
                    <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md">Upload</button>
                </form>
            </div>
           

            <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Form Pencarian -->
            <form action="{{ route('api.index') }}" method="GET" class="d-flex align-items-center">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama Instansi atau Nama Data"
                        value="{{ request('search') }}" style="width: 400px;">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
                @if(request('search'))
                    <a href="{{ route('api.index') }}" class="btn btn-secondary ms-2">Tampilkan Semua</a>
                @endif
            </form>

            <!-- Dropdown Export -->
            <!-- Dropdown Export -->
        <div class="relative">
            <button id="exportButton" type="button" class="btn btn-outline-primary">
                <i class="fas fa-file-export"></i> <!-- Icon Export -->
            </button>

            <div id="exportDropdown" class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg hidden">
                <a href="{{ route('api.export-excel') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-file-excel text-green-500"></i> Download Excel
                </a>
                <a href="{{ route('api.export-pdf') }}" target="_blank" rel="noopener noreferrer" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-file-pdf text-red-500"></i> Download PDF
                </a>
            </div>
        </div>
        </div>


        @if (session('warning'))
        <div class="alert alert-warning animated-alert">
            {{ session('warning') }}
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success animated-alert">
            {{ session('success') }}
        </div>
        @endif

    <table class="table-auto w-full border-collapse border border-gray-300">
        <colgroup>
            <col style="width: 6%;">
            <col style="width: 13%;">
            <col style="width: 20%;">
            <col style="width: 25%;">
            <col style="width: 8%;">
            <col style="width: 8%;">
            <col style="width: 15%;">
        </colgroup>

        <thead class="bg-gray-300 text-white text-center">
            <tr>
                <th class="py-2 px-4 border border-gray-300">No</th>
                <th class="py-2 px-4 border border-gray-300">Nama Instansi</th>
                <th class="py-2 px-4 border border-gray-300">Judul Data</th>
                <th class="py-2 px-4 border border-gray-300">URL API</th>
                <th class="py-2 px-4 border border-gray-300">Method</th>
                <th class="py-2 px-4 border border-gray-300">Status</th>
                <th class="py-2 px-4 border border-gray-300">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($apis as $api)
            <tr>
                <td class="py-2 px-4 border border-gray-300 text-center">{{ $apis->firstItem() + $loop->index }}</td>
                <td class="py-2 px-4 border border-gray-300">{{ $api->nama_instansi }}</td>
                <td class="py-2 px-4 border border-gray-300">{{ $api->nama_data }}</td>
                <td class="py-2 px-4 border border-gray-300">{{ $api->url_api }}</td>
                <td class="py-2 px-4 border border-gray-300 text-center">{{ $api->method }}</td>
                <td class="py-2 px-4 border border-gray-300 text-center">
                    <span class="inline-block py-1 px-2 text-xs font-semibold rounded-full text-white 
                        {{ $api->status == 'Belum Terkirim' ? 'bg-red-500' : 'bg-green-500' }}">
                        {{ $api->status }}
                    </span>
                </td>
                <td class="py-2 px-4 border border-gray-300 text-center w-48">
                    <div class="d-flex justify-content-center">
                        <a class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#modalLihat{{ $api->id }}">
                            Lihat
                        </a>
                        <!-- <form action="{{ route('apis.destroy', $api->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger me-2">Hapus</button>
                        </form> -->
                        <a href="{{ route('api.mapping', $api->id) }}" class="btn btn-sm btn-warning me-2">mapping</a>
                        <a href="{{ route('api.konfirm', $api->id) }}" class="btn btn-sm btn-success">Kirim</a>
                    </div>
                </td>
            </tr>
        
            <!-- MODAL SETIAP BARIS -->
            <div class="modal fade" id="modalLihat{{ $api->id }}" tabindex="-1" aria-labelledby="modalLihatLabel{{ $api->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLihatLabel{{ $api->id }}">Detail API - {{ $api->nama_instansi }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-dismissible" id="api-notification{{ $api->id }}" style="display: none;"></div>
                            <div class="api-details">
                                <div class="api-item">
                                    <div class="label">Nama Instansi :</div>
                                    <div class="value">{{ $api->nama_instansi }}</div>
                                </div>
                                <div class="api-item">
                                    <div class="label">Nama Data :</div>
                                    <div class="value">{{ $api->nama_data }}</div>
                                </div>
                                <div class="api-item">
                                    <div class="label">URL API :</div>
                                    <div class="value">
                                        <a href="{{ $api->url_api }}" target="_blank">{{ $api->url_api }}</a>
                                    </div>
                                </div>
                                <div class="api-item">
                                    <div class="label">Credential Key :</div>
                                    <div class="value">{{ $api->credential_key }}</div>
                                </div>
                                <form id="apiRequestForm{{ $api->id }}" action="{{ route('api.send_request', $api->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="parameter"><strong>Masukkan Parameter jika ada</strong></label>
                                        <input type="text" class="form-control" id="parameter" name="parameter" placeholder="Contoh: id_data=1234&tahun=2024">
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3 w-100">Kirim Permintaan</button>
                                </form>
        
                                <div class="response-container">
                                    <p><strong>Respon API:</strong></p>
                                    <pre class="response-box"><code class="language-json" id="response-data{{ $api->id }}">{{ json_encode($api->responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                        
                    </div>
                </div>
            </div>
        @empty
            <tr>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="7">Tidak ada data.</td>
            </tr>
        @endforelse
        
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="text-center mt-4">
        {{ $apis->links() }}
    </div>

    <!-- Tambahkan script untuk menghandle AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Menangani pengiriman form menggunakan AJAX
        $('form[id^="apiRequestForm"]').submit(function(e) {
            e.preventDefault(); // Mencegah form untuk disubmit secara normal

            var form = $(this);
            var apiId = form.attr('id').replace('apiRequestForm', ''); // Mengambil ID API dari form
            var url = form.attr('action'); // URL dari form action
            var data = form.serialize(); // Data yang diambil dari form

            // Kirim permintaan AJAX
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        // Tampilkan respons API di dalam modal
                        $('#response-data' + apiId).text(JSON.stringify(response.response_data, null, 2));

                        // Setelah respons dimasukkan, highlight sintaks JSON dengan PrismJS
                        Prism.highlightAll();

                        // Tampilkan notifikasi sukses di dalam modal setelah respons dimasukkan
                        showNotificationInModal(apiId, 'Permintaan berhasil dikirim dan respons telah diterima.', 'success');
                    } else {
                        // Tampilkan notifikasi jika data API sudah ada
                        showNotificationInModal(apiId, response.message, 'warning');
                    }
                },
                error: function(xhr, status, error) {
                    // Tampilkan error jika permintaan gagal
                    $('#response-data' + apiId).text('Terjadi kesalahan: ' + error);
                    Prism.highlightAll();

                    // Tampilkan notifikasi error setelah gagal
                    showNotificationInModal(apiId, 'Terjadi kesalahan saat mengirim permintaan.', 'error');
                }
            });
        });

        // Fungsi untuk menampilkan notifikasi di dalam modal
        function showNotificationInModal(apiId, message, type) {
            var notification = $('#api-notification' + apiId);

            // Atur class notifikasi sesuai jenis (success, error, atau warning)
            if (type === 'success') {
                notification.removeClass('alert-danger alert-warning').addClass('alert-success').text(message);
            } else if (type === 'warning') {
                notification.removeClass('alert-success alert-danger').addClass('alert-warning').text(message);
            } else {
                notification.removeClass('alert-success alert-warning').addClass('alert-danger').text(message);
            }

            // Tampilkan notifikasi
            notification.fadeIn();

            // Setelah 5 detik, hilangkan notifikasi
            setTimeout(function() {
                notification.fadeOut();
            }, 5000);
        }
    });
    // download excel dan pdf
    document.addEventListener("DOMContentLoaded", function () {
        const button = document.querySelector("#exportButton");
        const dropdown = document.querySelector("#exportDropdown");

        button.addEventListener("click", function () {
            dropdown.classList.toggle("hidden");
        });

        // Tutup dropdown jika klik di luar
        document.addEventListener("click", function (event) {
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });
    });
    </script>

    @endsection
