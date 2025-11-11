@extends('frontend.layouts.app')

@section('title', __('meta.payment_cancelled'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

    <div class="container py-5 text-center">
        <h1 class="text-danger mb-3">{{ __('meta.payment_cancelled') }} ❌</h1>

        @if ($ern)
            <p class="lead">
                {{ __('meta.transaction_referencia') }} <strong>{{ $ern }}</strong> {{ __('meta.was_cancelled_or_not') }}
            </p>
        @else
            <p class="lead">
                {{ __('meta.payment_process_cancelled') }}
            </p>
        @endif

        <p class="mt-3">
            {{ __('meta.if_problem_persists') }}
        </p>

        <a href="{{ route('checkout.show') }}" class="btn btn-outline-primary mt-4">
            {{ __('meta.back_to_checkout') }}
        </a>
    </div>


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>


    {{-- Superior (Newsletter) block --}}
@endsection
