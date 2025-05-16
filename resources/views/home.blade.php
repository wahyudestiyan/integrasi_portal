@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        .dashboard-embed-wrapper {
            margin: 0;
            padding: 0;
            width: 70vw;       /* viewport width */
            height: 100vh;      /* viewport height */
            position: relative;
        }

        .dashboard-embed-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>

    <div class="dashboard-embed-wrapper">
        <iframe
            src="https://satudata.jatengprov.go.id/mtb/public/dashboard/bfd18ae0-4e4f-4d18-80c8-0546b60608c3"
            allowtransparency
        ></iframe>
    </div>
@endsection
