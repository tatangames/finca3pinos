<div class="container subscribe-block">
    <section class="vc_section bg-color-theme_color displaced-top">
        <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-o-content-middle vc_row-flex">
            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-4 vc_col-md-6">
                <div class="vc_column-inner">
                    <div class="wpb_wrapper">
                        <div class="heading head-subheader align-left color-black subcolor-white transform-default">
                            <h4 class="subheader">Subscribe</h4>
                            <h4 class="header">Weekly newsletter</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-5 vc_col-md-6">
                <div class="vc_column-inner">
                    <div class="wpb_wrapper">
                        <div class="wpb_text_column wpb_content_element">
                            <div class="wpb_wrapper">
                                <form class="mc4wp-form">
                                    @csrf
                                    <div class="mc4wp-form-fields">
                                        <div class="input-group">
                                            <input type="email"
                                                   name="email"
                                                   placeholder="Your email address"
                                                   required>
                                            <span class="input-group-btn">
                                                <button class="btn" type="submit">Subscribe</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-3 vc_col-md-6 vc_hidden-md vc_hidden-sm vc_hidden-xs">
                <div class="vc_column-inner">
                    <div class="wpb_wrapper">
                        <div class="align-default icon-style-square">
                            <ul class="social-small icon-style-square icon-weight-bold">
                                <li><a href="#" class="fa fa-twitter"></a></li>
                                <li><a href="#" class="fa fa-facebook"></a></li>
                                <li><a href="#" class="fa fa-instagram"></a></li>
                                <li><a href="#" class="fa fa-google-plus"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section id="block-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-ms-12 matchHeight clearfix">
                <div class="footer-widget-area">
                    <div class="widget widget_media_image">
                        <img width="200" height="62"
                             src="{{ asset('images/logo_white-7.png') }}"
                             alt="CoffeeKing Logo">
                    </div>
                    <div class="widget widget_text">
                        <div class="textwidget">
                            <p>Pellentesque congue non augue vitae pellentesque. Morbi sollicitudin eleifend rhoncus.</p>
                            <p>&nbsp;</p>
                            <p>
                                <a class="btn btn-main-filled color-hover-white btn-xs"
                                   href="#"
                                   target="_blank">
                                    Purchase Theme
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-ms-12 hidden-xs hidden-ms hidden-sm matchHeight clearfix">
                <div class="footer-widget-area">
                    <div class="widget widget_nav_menu">
                        <h4 class="header-widget">Explore</h4>
                        <div class="menu-footer-menu-container">
                            <ul class="menu">
                                <li><a href="#">Home</a></li>
                                <li><a href="#">Contact Us</a></li>
                                <li><a href="#">Products</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">About us</a></li>
                                <li><a href="#">Gallery</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-ms-12 matchHeight clearfix">
                <div class="footer-widget-area">
                    <!-- Widget adicional aquí si lo necesitas -->
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer-block">
    <div class="container">
        <p>
            <a href="http://like-themes.com/">Like-themes</a> © All Rights Reserved - {{ date('Y') }}
        </p>
        <a href="#" class="go-top hidden-xs hidden-ms">
            <span class="fa fa-arrow-up"></span>TOP
        </a>
    </div>
</footer>
