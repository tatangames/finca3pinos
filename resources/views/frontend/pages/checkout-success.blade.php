@extends('frontend.layouts.app')

@section('title', __('meta.successful_payment'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <div class="container py-5 text-center">
        <h1 class="text-success mb-3">{{ __('meta.thanks_for_your_purchase') }}</h1>

        <p class="lead">
            {{ __('meta.your_order_number') }} <strong>{{ $order->id }}</strong> {{ __('meta.was_processed_successfully') }}
        </p>

        <div class="card mt-4 mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <p><strong>{{ __('meta.reference') }}</strong> {{ $order->ern }}</p>
                <p><strong>{{ __('meta.total') }}:</strong> ${{ number_format($order->total, 2) }}</p>
                <p><strong>{{ __('meta.status') }}:</strong> {{ __('meta.order_status_' . $order->status_name) }}</p>
            </div>
        </div>

        <a href="{{ route('user.orders') }}" class="btn btn-primary mt-4">
            {{ __('meta.my_orders') }}
        </a>
    </div>


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>


    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
