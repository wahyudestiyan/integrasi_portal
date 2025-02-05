@extends('layouts.app')

@section('title', 'Konfirmasi API')

@section('content')
<style>
    .api-details, .transformed-api-container {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .response-box {
        border: 1px solid #ddd;
        padding: 20px;
        font-family: monospace;
        background-color: #f5f5f5;
        color: #333;
        white-space: pre-wrap;
        word-wrap: break-word;
        height: auto;
        max-height: 400px;
        overflow-y: auto;
        border-radius: 5px;
    }
    .draggable-item {
        padding: 8px;
        margin: 5px;
        background-color: #007bff;
        color: white;
        border-radius: 4px;
        cursor: grab;
    }
    .drop-container {
        min-height: 100px;
        border: 2px dashed #007bff;
        padding: 10px;
        background-color: #f1f1f1;
    }
    #api-source, #api-target {
        min-height: 100px;
        border: 2px dashed #007bff;
        padding: 10px;
        background-color: #f1f1f1;
    }
    #api-source {
        display: none;
    }
</style>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Transformasi Data API</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="api-details">
                <h4>API Asal</h4>
                <form id="apiRequestForm" action="{{ route('cek.konfirm', $api->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label><strong>Masukkan Parameter jika ada</strong></label>
                        <input type="text" class="form-control" id="parameter" name="parameter" placeholder="Contoh: id_data=1234&tahun=2024">
                    </div>
                    <button type="submit" class="btn btn-success mt-3 w-100">Kirim Permintaan</button>
                </form>

                <div class="response-container mt-4">
                    <p><strong>Respon API:</strong></p>
                    <div class="response-box" id="response-data">
                        <pre>{{ isset($responseData) ? json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'Tidak ada data' }}</pre>
                    </div>
                </div>
            </div>

            <h4 class="mt-4">Seret Data ke Format API Tujuan</h4>
            <div id="api-source" class="drop-container">
                <!-- Data dari API akan ditampilkan di sini -->
            </div>
            <button class="btn btn-primary mt-3" id="showDataButton">Tampilkan Data untuk Diseret</button>
        </div>

        <div class="col-md-6">
            <div class="transformed-api-container">
                <h4>Format API Tujuan</h4>
                <div id="api-target" class="drop-container">
                    <!-- Elemen yang diseret akan ditempatkan di sini -->
                </div>
                <button class="btn btn-primary mt-3" id="sendDataButton">Kirim ke API Tujuan</button>
            </div>
        </div>
    </div>

    <a href="{{ route('api.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

{{-- Memastikan jQuery Dimuat dengan Benar --}}
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>

<script>
    var $ = jQuery.noConflict(); // Pastikan $ tidak digunakan oleh library lain

    $(document).ready(function() {
        let apiResponse = {}; // Variabel untuk menyimpan data API response

        // Ketika form dikirim
        $("#apiRequestForm").submit(function(e) {
            e.preventDefault(); // Mencegah form submit biasa
            var form = $(this);
            var url = form.attr("action");
            var data = form.serialize();

            $.ajax({
                url: url,
                method: "POST",
                data: data,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(response) {
                    apiResponse = response; // Menyimpan response API

                    // Menampilkan data yang diterima
                    $('#response-data').text(JSON.stringify(response, null, 2));
                    hljs.highlightElement(document.getElementById('response-data'));

                    // Kosongkan area sebelumnya
                    $("#api-source").empty();

                    // Menambahkan data ke dalam #api-source
                    if (Array.isArray(response)) {
                        response.forEach(function(item, index) {
                            let itemDiv = document.createElement("div");
                            itemDiv.classList.add("draggable-item");
                            itemDiv.setAttribute("draggable", "true");
                            itemDiv.setAttribute("data-index", index);

                            let content = "<strong>Data " + (index + 1) + ":</strong><br>";
                            for (let key in item) {
                                if (item.hasOwnProperty(key)) {
                                    let div = document.createElement("div");
                                    let textNode = document.createTextNode(key + ": " + item[key]);
                                    div.appendChild(textNode);
                                    content += div.innerHTML + "<br>";
                                }
                            }

                            itemDiv.innerHTML = content;
                            itemDiv.addEventListener("dragstart", dragStart);
                            $("#api-source").append(itemDiv);
                        });
                    } else if (typeof response === 'object') {
                        let itemDiv = document.createElement("div");
                        itemDiv.classList.add("draggable-item");
                        itemDiv.setAttribute("draggable", "true");

                        let content = "<strong>Data:</strong><br>";
                        for (let key in response) {
                            if (response.hasOwnProperty(key)) {
                                let div = document.createElement("div");
                                let textNode = document.createTextNode(key + ": " + response[key]);
                                div.appendChild(textNode);
                                content += div.innerHTML + "<br>";
                            }
                        }

                        itemDiv.innerHTML = content;
                        itemDiv.addEventListener("dragstart", dragStart);
                        $("#api-source").append(itemDiv);
                    }

                    // Tampilkan data untuk diseret setelah API merespon
                    $('#api-source').show();
                },
                error: function(xhr, status, error) {
                    $('#response-data').text('Terjadi kesalahan: ' + error);
                }
            });
        });

        // Fungsi untuk memulai drag
        function dragStart(event) {
            event.dataTransfer.setData("text", event.target.getAttribute("data-index"));
        }

        // Menangani area drop
        let targetContainer = document.getElementById("api-target");
        targetContainer.addEventListener("dragover", function(event) {
            event.preventDefault(); // Agar bisa melakukan drop
        });

        targetContainer.addEventListener("drop", function(event) {
            event.preventDefault();
            let index = event.dataTransfer.getData("text");
            let item = apiResponse[index]; // Ambil data berdasarkan index

            // Proses dan tampilkan data di area drop
            let itemDiv = document.createElement("div");
            itemDiv.classList.add("draggable-item");
            itemDiv.textContent = JSON.stringify(item, null, 2);
            targetContainer.appendChild(itemDiv);
        });

        // Tampilkan data
        $('#showDataButton').click(function() {
            $("#api-source").show();
        });

        // Kirim data ke API tujuan
        $('#sendDataButton').click(function() {
            // Kirim data ke API tujuan sesuai dengan format yang diinginkan
            alert("Kirim data ke API tujuan!");
        });
    });
</script>

@endsection
