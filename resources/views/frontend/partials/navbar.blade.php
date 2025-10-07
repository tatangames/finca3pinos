<div id="nav-wrapper" class="wrapper-">
    <nav data-spy="" data-offset-top="0" class="navbar">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar top-bar"></span>
                    <span class="icon-bar middle-bar"></span>
                    <span class="icon-bar bottom-bar"></span>
                </button>

                <a class="logo" href="#">
                    <img width="200" height="62"
                         src="{{ asset('images/logo_black-9.png') }}"
                         alt="CoffeeKing Logo">
                </a>
            </div>

            <div class="pull-right nav-right hidden-lg">
                <a href="#" class="shop_table cart" title="View your shopping cart">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <span class="cart-contents header-cart-count count">{{ $cartCount ?? 0 }}</span>
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

                <ul class="nav navbar-nav">
                    <li class="menu-item {{ request()->routeIs('home') ? 'current-menu-item' : '' }}">
                        <a href="#"><span>Home</span></a>
                    </li>

                    <li class="menu-item menu-item-has-children {{ request()->routeIs('about.*') ? 'current-menu-parent' : '' }}">
                        <a href="#"><span>About us</span></a>
                        <ul class="sub-menu">
                            <li><a href="#"><span>About us</span></a></li>
                            <li><a href="#"><span>Delivery</span></a></li>
                            <li><a href="#"><span>Events</span></a></li>
                        </ul>
                    </li>

                    <li class="menu-item menu-item-has-children {{ request()->routeIs('shop.*') ? 'current-menu-parent' : '' }}">
                        <a href="#"><span>Products</span></a>
                        <ul class="sub-menu">
                            <li><a href="#"><span>Shop</span></a></li>
                            <li><a href="#"><span>Cart</span></a></li>
                            <li><a href="#"><span>Checkout</span></a></li>
                        </ul>
                    </li>



                    <li class="ltx-fa-icon ltx-nav-cart hidden-md hidden-sm hidden-ms hidden-xs">
                        <div class="cart-navbar">
                            <a href="#" class="shop_table cart" title="View your shopping cart">
                                <span class="cart-contents header-cart-count count"></span>
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>

                    <li class="ltx-fa-icon ltx-nav-search hidden-md hidden-sm hidden-ms hidden-xs">
                        <div id="top-search" class="top-search">
                            <a href="#" id="top-search-ico" class="top-search-ico fa fa-search" aria-hidden="true"></a>
                            <input placeholder="Search" value="" type="text">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
