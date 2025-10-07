<header class="page-header like-parallax">
    <div class="container">
        <h1>{{ $title ?? 'Traditions of the coffee ceremony' }}</h1>

        @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home">
                    <span property="itemListElement" typeof="ListItem">
                        <a property="item" typeof="WebPage"
                           title="Go to CoffeeKing"
                           href="{{ route('home') }}"
                           class="home">
                            <span property="name">CoffeeKing</span>
                        </a>
                        <meta property="position" content="1">
                    </span>
                </li>

                @foreach($breadcrumbs as $index => $breadcrumb)
                    <li class="{{ $breadcrumb['class'] ?? '' }}">
                        <span property="itemListElement" typeof="ListItem">
                            @if(isset($breadcrumb['url']))
                                <a property="item" typeof="WebPage"
                                   title="{{ $breadcrumb['title'] }}"
                                   href="{{ $breadcrumb['url'] }}"
                                   class="{{ $breadcrumb['class'] ?? '' }}">
                                    <span property="name">{{ $breadcrumb['name'] }}</span>
                                </a>
                            @else
                                <span property="name">{{ $breadcrumb['name'] }}</span>
                            @endif
                            <meta property="position" content="{{ $index + 2 }}">
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</header>
