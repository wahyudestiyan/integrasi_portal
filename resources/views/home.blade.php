@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-4">Welcome to My Laravel App</h1>
    <p class="text-gray-700">
        Ini adalah aplikasi Laravel dengan layout modern menggunakan Tailwind CSS.
    </p>
    <a href="/about" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Learn More
    </a>
</div>
@endsection
