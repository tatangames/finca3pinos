@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        html, body {
            height: 100%;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #000 url({{ asset('images/404.png') }}) center/cover no-repeat;
        }

        .demo-container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            padding: 50px 40px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .card img {
            width: 110px;
            height: auto;
            margin-bottom: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 110px;
            height: auto;
        }

        h4 {
            font-weight: 700;
            color: #333;
        }

        p {
            color: #444;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        .btn-main {
            background: #D2AA6D;
            color: #fff !important;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-main:hover,
        .btn-main:focus,
        .btn-main:active {
            background: #c39a5f;
            color: #fff !important;
            text-decoration: none;
            outline: none;
            transform: scale(1.02);
        }

        .footer-text {
            font-size: 13px;
            color: #777;
            margin-top: 25px;
        }
    </style>

    <div class="demo-container" style="margin-top: 35px; margin-bottom: 35px">
        <div class="card shadow-lg">
            <h4>{{ __('meta.product_v4') }}</h4>
            <p>{{ __('meta.product_v5') }}</p>
            <a href="{{ route('user.password.request') }}" class="btn btn-main w-100">{{ __('meta.product_v6') }}</a>
            <p class="footer-text">© {{ date('Y') }} {{ __('meta.product_v7') }}</p>
        </div>
    </div>

    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
