@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>

        body {
            background-image: url('{{ asset('images/404.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .btn-round {
            border-radius: 50px !important; /* bordes redondeados */
            padding: 10px 24px !important;  /* más espacio interno */
            transition: all .3s ease;
        }

        .btn-round:hover {
            background-color: #d2aa6d; /* ejemplo: color dorado al pasar el mouse */
            color: #fff !important;
        }
    </style>

    <div class="container">
        <section class="vc_section vc_custom_1502109771712 vc_section-has-fill">
            <div data-vc-full-width="true" data-vc-full-width-init="false" data-vc-stretch-content="true"
                 class="vc_row wpb_row vc_row-fluid vc_custom_1502108370252 vc_row-has-fill vc_row-no-padding">
                <div class="wpb_column vc_column_container vc_col-sm-12">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <div class="vc_empty_space" style="height: 240px"><span class="vc_empty_space_inner"></span>
                            </div>
                            <div class="heading  heading-xl align-center color-white subcolor-main transform-header-up"
                                 id="like_sc_header_1417972635"><h1 class="header">404</h1></div>
                            <div
                                class="heading  heading-large align-center color-main subcolor-second transform-header-up"
                                id="like_sc_header_1082839314"><h4 class="header">{{ __('meta.page_404') }}</h4></div>
                            <div class="vc_empty_space" style="height: 32px"><span class="vc_empty_space_inner"></span>
                            </div>
                            <div class="wpb_text_column wpb_content_element ">
                                <div class="wpb_wrapper">
                                    <p style="text-align: center; color: white"><strong>{{ __('meta.page_404_v1') }}</strong><br>
                                        <strong>{{ __('meta.page_404_v2') }}</strong></p>

                                </div>
                            </div>
                            <div class="vc_empty_space" style="height: 16px"><span class="vc_empty_space_inner"></span>
                            </div>
                            <div class="btn-wrap align-center">
                                <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.index', [], false)) }}"
                                   class="btn btn-xs btn-default transform-uppercase color-text-default color-hover-white align-center btn-round"
                                   id="like_sc_button_774096535">
                                    {{ __('meta.home') }}
                                </a>
                            </div>
                            <div class="vc_empty_space" style="height: 240px"><span class="vc_empty_space_inner"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vc_row-full-width vc_clearfix"></div>
        </section>
    </div>








@endsection

