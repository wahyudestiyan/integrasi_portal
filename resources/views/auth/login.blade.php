@extends('layouts.app')

@section('content')
<div class="container vh-100 d-flex flex-column align-items-center justify-content-center">
    <!-- Judul Aplikasi -->
    <h2 class="mb-4 text-white" style="font-weight: bold; background-color: #1F2937; padding: 10px 20px; border-radius: 8px;">
        PORTAL DATA BRIDGING
    </h2>

    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg" style="max-width: 500px; margin: auto;">
                <div class="card-header text-center text-white" style="background-color: #1F2937;">
                    <h4>{{ __('Login') }}</h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('Username') }}</label>
                            <input type="text" class="form-control form-control-lg" id="username" name="username" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn w-100 py-2 text-white" style="background-color: #10B981;">
                            {{ __('Login') }}
                        </button>
                    </form>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
