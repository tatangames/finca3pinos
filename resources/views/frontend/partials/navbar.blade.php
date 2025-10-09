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
                <a class="logo" href="#">
                    <img width="200" height="62" src="{{ asset('images/logo_black-9.png') }}"
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
                    <li class="menu-item menu-item-has-children current-menu-parent">
                        <a href="#"><span>Home</span></a>
                        <ul class="sub-menu">
                            <li><a href="#"><span>Coffee Market</span></a></li>
                            <li class="current-menu-item"><a href="#"><span>Light Version</span></a></li>
                            <li><a href="#"><span>Coffee Shop Dark</span></a></li>
                            <li><a href="#"><span>Coffee Cup Waves</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-item menu-item-has-children">
                        <a href="#"><span>About us</span></a>
                        <ul class="sub-menu">
                            <li><a href="#"><span>About us</span></a></li>
                            <li><a href="#"><span>Delivery</span></a></li>
                            <li><a href="#"><span>Events</span></a></li>
                            <li><a href="#"><span>Special Offers</span></a></li>
                            <li><a href="#"><span>Testimonials</span></a></li>
                            <li><a href="#"><span>Team</span></a></li>
                            <li><a href="#"><span>FAQ</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-item menu-item-has-children">
                        <a href="#"><span>Products</span></a>
                        <ul class="sub-menu">
                            <li><a href="#"><span>Shop</span></a></li>
                            <li><a href="#"><span>Cart</span></a></li>
                            <li><a href="#"><span>Checkout</span></a></li>
                            <li><a href="#"><span>My account</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-item menu-item-has-children">
                        <a><span>Blog</span></a>
                        <ul class="sub-menu">
                            <li><a href="#"><span>Blog One Column</span></a></li>
                            <li><a href="#"><span>Blog Two Columns</span></a></li>
                            <li><a href="#"><span>Blog Three Columns</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-item menu-item-has-children">
                        <a><span>Gallery</span></a>
                        <ul class="sub-menu">
                            <li><a href="#"><span>Gallery 2-columns</span></a></li>
                            <li><a href="#"><span>Gallery 3-columns</span></a></li>
                            <li><a href="#"><span>Gallery 4-columns</span></a></li>
                        </ul>
                    </li>
                    <li><a href="#"><span>Contacts</span></a></li>
                    <li class="menu-item menu-item-has-children">
                        <a href="#"><span>Pages</span></a>
                        <ul class="sub-menu">
                            <li class="menu-item menu-item-has-children">
                                <a><span>Typography</span></a>
                                <ul class="sub-menu">
                                    <li><a href="#"><span>Headers</span></a></li>
                                    <li><a href="#"><span>Text</span></a></li>
                                    <li><a href="#"><span>Text Columns</span></a></li>
                                    <li><a href="#"><span>Table</span></a></li>
                                    <li><a href="#"><span>Separators</span></a></li>
                                </ul>
                            </li>
                            <li class="menu-item menu-item-has-children">
                                <a><span>Form Elements</span></a>
                                <ul class="sub-menu">
                                    <li><a href="#"><span>Buttons</span></a></li>
                                    <li><a href="#"><span>Forms</span></a></li>
                                </ul>
                            </li>
                            <li class="menu-item menu-item-has-children">
                                <a><span>Shortcodes</span></a>
                                <ul class="sub-menu">
                                    <li><a href="#"><span>Accordions</span></a></li>
                                    <li><a href="#"><span>Alerts</span></a></li>
                                    <li><a href="#"><span>Tabs</span></a></li>
                                    <li><a href="#"><span>Shortcodes</span></a></li>
                                </ul>
                            </li>
                            <li class="menu-item menu-item-has-children">
                                <a><span>Lists</span></a>
                                <ul class="sub-menu">
                                    <li><a href="#"><span>Icons</span></a></li>
                                    <li><a href="#"><span>Lists and Social Icons</span></a></li>
                                </ul>
                            </li>
                            <li><a href="#"><span>404 Page</span></a></li>
                            <li><a href="#"><span>Cooming Soon</span></a></li>
                            <li><a href="#"><span>Under Construction</span></a></li>
                        </ul>
                    </li>

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
