<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta name="format-detection" content="telephone=no">

    <title>@yield('title', __('meta.title'))</title>
    <meta name="description" content="@yield('meta_description', __('meta.description'))">

    <style>img:is([sizes="auto" i], [sizes^="auto," i]) { contain-intrinsic-size: 3000px 1500px }</style>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">

    {{-- Core & Theme CSS --}}
    <link rel="stylesheet" id="dashicons-css" href="{{ asset('frontend/css/dashicons.min.css') }}" media="all">
    <link rel="stylesheet" id="post-views-counter-frontend-css" href="{{ asset('frontend/css/frontend.min.css') }}" media="all">
    <link rel="stylesheet" id="wp-block-library-css" href="{{ asset('frontend/css/style.min.css') }}" media="all">

    <style id="classic-theme-styles-inline-css" type="text/css">
        .wp-block-button__link {
            color: #fff; background-color: #32373c; border-radius: 9999px; box-shadow: none; text-decoration: none;
            padding: calc(.667em + 2px) calc(1.333em + 2px); font-size: 1.125em
        }
        .wp-block-file__button { background:#32373c; color:#fff; text-decoration:none }
    </style>

    <link rel="stylesheet" id="bootstrap-css" href="{{ asset('frontend/css/bootstrap-grid.css') }}" media="all">
    <link rel="stylesheet" id="coffeeking-plugins-css" href="{{ asset('frontend/css/plugins.css') }}" media="all">
    <link rel="stylesheet" id="coffeeking-theme-style-css" href="{{ asset('frontend/css/style.css') }}" media="all">
    <link rel="stylesheet" id="font-awesome-css" href="{{ asset('frontend/css/font-awesome.min.css') }}" media="all">
    <link rel="stylesheet" id="js_composer_front-css" href="{{ asset('frontend/css/js_composer.min.css') }}" media="all">
    <link rel="stylesheet" id="parent-style-css" href="{{ asset('frontend/css/style.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('frontend/css/base1.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('frontend/css/base2.css') }}" media="all">
    <link rel="stylesheet" id="child-style-css" href="{{ asset('frontend/css/style.css') }}" media="all">
    <link rel="stylesheet" id="fw-option-type-icon-v2-pack-linecons-css" href="{{ asset('frontend/css/linecons.css') }}" media="all">
    <link rel="stylesheet" id="fw-option-type-icon-v2-pack-font-awesome-css" href="{{ asset('frontend/css/font-awesome.min.css') }}" media="all">
    <link rel="stylesheet" id="fw-option-type-icon-v2-pack-entypo-css" href="{{ asset('frontend/css/entypo.css') }}" type="text/css" media="all">
    <link rel="stylesheet" id="fw-option-type-icon-v2-pack-linearicons-css" href="{{ asset('frontend/css/lnr.css') }}" type="text/css" media="all">
    <link rel="stylesheet" id="fw-option-type-icon-v2-pack-typicons-css" href="{{ asset('frontend/css/typcn.css') }}" type="text/css" media="all">
    <link rel="stylesheet" id="fw-option-type-icon-v2-pack-unycon-css" href="{{ asset('frontend/css/unycon.css') }}" type="text/css" media="all">
    <link rel="stylesheet" id="magnific-popup-css" href="{{ asset('frontend/css/magnific-popup.css') }}" type="text/css" media="all">
    <link rel="stylesheet" id="coffeeking-google-fonts-css" href="//fonts.googleapis.com/css?family=Kanit:300,900,700&amp;subset=latin-ext" type="text/css" media="all">
    <link rel="stylesheet" id="vc_font_awesome_5_shims-css" href="{{ asset('frontend/css/v4-shims.min.css') }}" type="text/css" media="all">
    <link rel="stylesheet" id="vc_font_awesome_5-css" href="{{ asset('frontend/css/all.min.css') }}" type="text/css" media="all">
    <link rel="stylesheet" id="vc_animate-css-css" href="{{ asset('frontend/css/animate.min.css') }}" type="text/css" media="all">

    {{-- Favicons --}}
    <link href="{{ asset('images/logopestana.jpg') }}" rel="icon">
    <link rel="apple-touch-icon" href="{{ asset('images/logopestana.jpg') }}">
    <meta name="msapplication-TileImage" content="{{ asset('images/logopestana.jpg') }}">

    @stack('head')
</head>
<body class="masthead-fixed full-width footer-widgets singular paceloader-dots vc_responsive">
<div id="preloader"></div>

{{-- Navbar --}}
@include('frontend.partials.navbar')

<main>
    @yield('content')
</main>

{{-- Footer --}}
@include('frontend.partials.footer')

{{-- Core JS --}}
<script type="text/javascript" src="{{ asset('frontend/js/jquery.min.js') }}" id="jquery-core-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery-migrate.min.js') }}" id="jquery-migrate-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/modernizr-2.6.2.min.js') }}" id="modernizr-js"></script>

<!--[if lt IE 9]>
<script type="text/javascript" src="{{ asset('frontend/js/html5shiv.js') }}" id="html5shiv-js"></script>
<![endif]-->


<script>(function(){function maybePrefixUrlField(){const v=this.value.trim();if(v!==''&&v.indexOf('http')!==0){this.value='http://'+v}}const urlFields=document.querySelectorAll('.mc4wp-form input[type="url"]');for(let j=0;j<urlFields.length;j++){urlFields[j].addEventListener('blur', maybePrefixUrlField)}})();</script>

<script type="text/javascript" src="{{ asset('frontend/js/hooks.min.js') }}" id="wp-hooks-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/i18n.min.js') }}" id="wp-i18n-js"></script>
<script type="text/javascript" id="wp-i18n-js-after">wp.i18n.setLocaleData({'text direction\u0004ltr':['ltr']});</script>

<script type="text/javascript" src="{{ asset('frontend/js/jquery.counterup.min.js') }}" id="counterup-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.localscroll-1.2.7-min.js') }}" id="localscroll-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.matchHeight.js') }}" id="matchheight-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.parallax-1.1.3.js') }}" id="parallax-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.scrollTo-1.4.2-min.js') }}" id="scrollTo-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.magnific-popup.js') }}" id="magnific-popup-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.zoomslider.js') }}" id="zoomslider-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/imagesloaded.min.js') }}" id="imagesloaded-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/masonry.min.js') }}" id="masonry-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/scrollreveal.js') }}" id="scrollreveal-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/swiper.jquery.js') }}" id="swiper-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.nicescroll.js') }}" id="nicescroll-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/waypoint.js') }}" id="waypoint-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.fullPage.js') }}" id="fullpage-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/affix.js') }}" id="affix-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/jquery.ripples.js') }}" id="ripples-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/map-style.js') }}" id="coffeeking-map-style-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/scripts.js') }}" id="coffeeking-scripts-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/pace.js') }}" id="pace-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/js_composer_front.min.js') }}" id="wpb_composer_front_js-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/vc-waypoints.min.js') }}" id="vc_waypoints-js"></script>
<script type="text/javascript" src="{{ asset('frontend/js/skrollr.min.js') }}" id="vc_jquery_skrollr_js-js"></script>

@stack('scripts')
</body>
</html>
