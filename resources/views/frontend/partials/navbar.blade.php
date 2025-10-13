<div id="nav-wrapper" class="wrapper-">
    <nav data-spy="" data-offset-top="0" class="navbar ">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar top-bar"></span>
                    <span class="icon-bar middle-bar"></span>
                    <span class="icon-bar bottom-bar"></span>
                </button>
                <a class="logo" href="{{ url('/' . session('region', config('region.default'))) }}">
                    <img style="width: 150px;" src="{{ asset('images/logoindex.png') }}"
                         class="attachment-full size-full" alt="" decoding="async">
                </a>
            </div>

            <div id="navbar" class="navbar-collapse collapse">
                <div class="toggle-wrap">
                    <button type="button" class="navbar-toggle collapsed">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar top-bar"></span>
                        <span class="icon-bar middle-bar"></span>
                        <span class="icon-bar bottom-bar"></span>
                    </button>
                    <div class="clearfix"></div>
                </div>
                {{-- Main Menu --}}
                <ul id="menu-main-menu" class="nav navbar-nav">
                    {{-- Keep placeholders (#) for Laravel routes you will wire later --}}
                    <li><a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.index', [], false)) }}"><span>{{ __('meta.home') }}</span></a></li>
                    <li><a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.ourcoffee', [], false)) }}"><span>{{ __('meta.our_coffee') }}</span></a></li>
                    <li><a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.products', [], false)) }}"><span>{{ __('meta.products') }}</span></a></li>
                    <li><a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.gallery', [], false)) }}"><span>{{ __('meta.gallery') }}</span></a></li>
                    <li><a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}"><span>{{ __('meta.contact') }}</span></a></li>













                    <li class="ltx-fa-icon ltx-nav-search hidden-md hidden-sm hidden-ms hidden-xs">
                        <div id="top-search" class="top-search">
                            <a href="#" id="top-search-ico" class="top-search-ico fa fa-search" aria-hidden="true"></a>
                            <input placeholder="Search" value="" type="text">
                        </div>
                    </li>
                    <li class="ltx-fa-icon ltx-nav-social hidden-md hidden-sm hidden-ms hidden-xs">
                        <a href="#" class="fa fa fa-location-arrow" target="_blank"></a>
                    </li>
                </ul>
                <div class="nav-mob"><ul class="nav navbar-nav"></ul></div>
            </div>
        </div>
    </nav>
</div>
