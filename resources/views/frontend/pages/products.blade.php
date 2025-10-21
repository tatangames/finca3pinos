@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>
        /* Fondo full-bleed ya lo tienes; aquí limitamos SOLO el contenido */
        #products-full .products-container {
            max-width: 1200px; /* ajusta 1100–1280 si prefieres */
            margin: 0 auto;
            padding: 0 16px;
        }

        /* Espaciados suaves en la grilla */
        #products-full .products-sc .row {
            margin-left: -8px;
            margin-right: -8px;
        }

        #products-full .products-sc .swiper-slide {
            padding-left: 8px;
            padding-right: 8px;
        }

        /* Forzar # de columnas sin tocar JS de Swiper */
        @media (min-width: 1200px) {
            #products-full .swiper-wrapper {
                display: flex;
                flex-wrap: wrap;
            }

            #products-full .swiper-slide {
                width: 25% !important;
            }

            /* 4 por fila */
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            #products-full .swiper-wrapper {
                display: flex;
                flex-wrap: wrap;
            }

            #products-full .swiper-slide {
                width: 33.3333% !important;
            }

            /* 3 por fila */
        }

        @media (min-width: 576px) and (max-width: 991.98px) {
            #products-full .swiper-wrapper {
                display: flex;
                flex-wrap: wrap;
            }

            #products-full .swiper-slide {
                width: 50% !important;
            }

            /* 2 por fila */
        }

        @media (max-width: 575.98px) {
            #products-full .swiper-wrapper {
                display: flex;
                flex-wrap: wrap;
            }

            #products-full .swiper-slide {
                width: 100% !important;
            }

            /* 1 por fila */
        }

        /* Reducir tamaño visual de la card e imagen */
        #products-full article.product {
            border-radius: 14px;
            padding: 16px 12px !important;
        }

        #products-full article.product .photo {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 220px; /* mantiene altura pareja */
        }

        #products-full article.product .photo img {
            max-width: 220px; /* antes eran 300px */
            width: 100%;
            height: auto;
        }

        #products-full article.product .description {
            padding-top: 8px !important;
        }

        #products-full .price {
            font-size: 1.05rem;
        }

        /* baja un poco el precio */
        #products-full h5,
        #products-full .header h5 {
            font-size: 1.05rem;
        }

        /* baja el título */

    </style>


    <section id="products-full" data-vc-full-width="true" data-vc-full-width-init="true"
             class="vc_section bg-color-black">
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner">


                    <div class="wpb_wrapper">

                        <div class="products-container">
                            <div class="es-resp">
                                <div class="hidden-sm hidden-ms hidden-xs" style="height: 48px;"></div>
                                <div class="hidden-xl hidden-lg hidden-md hidden-xs" style="height: 0px;"></div>
                                <div class="visible-xs" style="height: 0px;"></div>
                            </div>
                            <div
                                class="heading  head-subheader align-center subcolor-main text-bg transform-default   vc_custom_1508507992778"
                                id="like_sc_header_1945088938"><h5 class="subheader" style="font-size: 35px">{{ __('meta.product_v1') }}</h5>
                            </div>




                            <div class="woocommerce">
                                <div class="products products-sc products-sc-default">
                                    <ul class="cats tabs-cats slider-filter">
                                        <li><span class="cat cat-active" data-filter="145">Coffee</span></li>
                                        <li><span class="cat" data-filter="155">Green coffee</span></li>
                                    </ul>
                                    <div class="items">
                                        <div class="row">
                                            <div
                                                class="swiper-container slider-filter-container products-slider swiper-container-horizontal"
                                                data-cols="4" data-autoplay="0">
                                                <div class="swiper-wrapper">
                                                    <div
                                                        class="col-lg-3 col-md-4 col-sm-6 swiper-slide filter-item item filter-type-145 swiper-slide-active"
                                                        style="width: 323.333px;">
                                                        <article id="post-2062"
                                                                 class="matchHeight post-2062 product type-product status-publish has-post-thumbnail product_cat-coffe product_tag-arabica first outofstock sale shipping-taxable purchasable product-type-simple"
                                                                 style="height: 557.743px;"><span
                                                                class="onsale">Sale</span>
                                                            <a href="https://coffeeking.like-themes.com/product/coffee-cup/"
                                                               class="photo"> <img decoding="async" width="300"
                                                                                   height="300"
                                                                                   src="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item1-300x300.jpg"
                                                                                   class="attachment-shop_catalog size-shop_catalog"
                                                                                   alt=""
                                                                                   srcset="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item1-300x300.jpg 300w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item1-150x150.jpg 150w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item1-100x100.jpg 100w"
                                                                                   sizes="(max-width: 300px) 100vw, 300px">
                                                            </a>
                                                            <div class="description"><a
                                                                    href="https://coffeeking.like-themes.com/product/coffee-cup/"
                                                                    class="header"><h5>MyCoffeeShop Cup</h5></a>
                                                                <div class="post_content entry-content">Duis et aliquam
                                                                    orci. Vivamus augue quam, ...
                                                                </div>
                                                                <h4 class="price color-main">
                                                                    <del aria-hidden="true"><span
                                                                            class="woocommerce-Price-amount amount"><bdi><span
                                                                                    class="woocommerce-Price-currencySymbol">$</span>23.00</bdi></span>
                                                                    </del>
                                                                    <span class="screen-reader-text">Original price was: $23.00.</span>
                                                                    <ins aria-hidden="true"><span
                                                                            class="woocommerce-Price-amount amount"><bdi><span
                                                                                    class="woocommerce-Price-currencySymbol">$</span>19.00</bdi></span>
                                                                    </ins>
                                                                    <span class="screen-reader-text">Current price is: $19.00.</span>
                                                                </h4>
                                                                <a rel="nofollow"
                                                                   href="https://coffeeking.like-themes.com/product/coffee-cup/"
                                                                   data-quantity="1" data-product_id="2062"
                                                                   data-product_sku=""
                                                                   class="ajax_add_to_cart button btn btn-default color-hover-black btn-xs transform-lowercase add_to_cart_button"><i
                                                                        class="fa fa-shopping-cart"
                                                                        aria-hidden="true"></i>Read
                                                                    more</a></div>
                                                        </article>
                                                    </div>


                                                    <div
                                                        class="col-lg-3 col-md-4 col-sm-6 swiper-slide filter-item item filter-type-145 filter-type-155"
                                                        style="width: 323.333px;">
                                                        <article id="post-2068"
                                                                 class="matchHeight post-2068 product type-product status-publish has-post-thumbnail product_cat-coffe product_cat-green-coffee product_tag-arabica  instock shipping-taxable purchasable product-type-simple"
                                                                 style="height: 557.743px;"><a
                                                                href="https://coffeeking.like-themes.com/product/green-africana/"
                                                                class="photo"> <img loading="lazy" decoding="async"
                                                                                    width="300" height="300"
                                                                                    src="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item2-300x300.jpg"
                                                                                    class="attachment-shop_catalog size-shop_catalog"
                                                                                    alt=""
                                                                                    srcset="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item2-300x300.jpg 300w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item2-150x150.jpg 150w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item2-600x600.jpg 600w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item2-100x100.jpg 100w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item2.jpg 625w"
                                                                                    sizes="auto, (max-width: 300px) 100vw, 300px">
                                                            </a>
                                                            <div class="description"><a
                                                                    href="https://coffeeking.like-themes.com/product/green-africana/"
                                                                    class="header"><h5>Green Africana</h5></a>
                                                                <div class="post_content entry-content">Duis et aliquam
                                                                    orci. Vivamus augue quam, ...
                                                                </div>
                                                                <h4 class="price color-main"><span
                                                                        class="woocommerce-Price-amount amount"><bdi><span
                                                                                class="woocommerce-Price-currencySymbol">$</span>9.00</bdi></span>
                                                                </h4>
                                                                <a rel="nofollow" href="?add-to-cart=2068"
                                                                   data-quantity="1"
                                                                   data-product_id="2068" data-product_sku=""
                                                                   class="ajax_add_to_cart button btn btn-default color-hover-black btn-xs transform-lowercase add_to_cart_button"><i
                                                                        class="fa fa-shopping-cart"
                                                                        aria-hidden="true"></i>Add
                                                                    to cart</a></div>
                                                        </article>
                                                    </div>
                                                    <div
                                                        class="col-lg-3 col-md-4 col-sm-6 swiper-slide filter-item item filter-type-145"
                                                        style="width: 323.333px;">
                                                        <article id="post-2066"
                                                                 class="matchHeight post-2066 product type-product status-publish has-post-thumbnail product_cat-coffe product_tag-arabica last instock shipping-taxable purchasable product-type-simple"
                                                                 style="height: 557.743px;"><a
                                                                href="https://coffeeking.like-themes.com/product/american-black-coffee/"
                                                                class="photo"> <img loading="lazy" decoding="async"
                                                                                    width="300" height="300"
                                                                                    src="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item4-300x300.jpg"
                                                                                    class="attachment-shop_catalog size-shop_catalog"
                                                                                    alt=""
                                                                                    srcset="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item4-300x300.jpg 300w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item4-150x150.jpg 150w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item4-100x100.jpg 100w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item4.jpg 400w"
                                                                                    sizes="auto, (max-width: 300px) 100vw, 300px">
                                                            </a>
                                                            <div class="description"><a
                                                                    href="https://coffeeking.like-themes.com/product/american-black-coffee/"
                                                                    class="header"><h5>American Black Coffee</h5></a>
                                                                <div class="post_content entry-content">Duis et aliquam
                                                                    orci. Vivamus augue quam, ...
                                                                </div>
                                                                <h4 class="price color-main"><span
                                                                        class="woocommerce-Price-amount amount"><bdi><span
                                                                                class="woocommerce-Price-currencySymbol">$</span>14.00</bdi></span>
                                                                </h4>
                                                                <a rel="nofollow" href="?add-to-cart=2066"
                                                                   data-quantity="1"
                                                                   data-product_id="2066" data-product_sku=""
                                                                   class="ajax_add_to_cart button btn btn-default color-hover-black btn-xs transform-lowercase add_to_cart_button"><i
                                                                        class="fa fa-shopping-cart"
                                                                        aria-hidden="true"></i>Add
                                                                    to cart</a></div>
                                                        </article>
                                                    </div>
                                                    <div
                                                        class="col-lg-3 col-md-4 col-sm-6 swiper-slide filter-item item filter-type-145"
                                                        style="width: 323.333px;">
                                                        <article id="post-2164"
                                                                 class="matchHeight post-2164 product type-product status-publish has-post-thumbnail product_cat-coffe product_tag-arabica product_tag-bean first instock shipping-taxable purchasable product-type-simple"
                                                                 style="height: 557.743px;"><a
                                                                href="https://coffeeking.like-themes.com/product/ground-coffee/"
                                                                class="photo"> <img loading="lazy" decoding="async"
                                                                                    width="300" height="300"
                                                                                    src="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item9-300x300.jpg"
                                                                                    class="attachment-shop_catalog size-shop_catalog"
                                                                                    alt=""
                                                                                    srcset="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item9-300x300.jpg 300w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item9-150x150.jpg 150w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item9-100x100.jpg 100w"
                                                                                    sizes="auto, (max-width: 300px) 100vw, 300px">
                                                            </a>
                                                            <div class="description"><a
                                                                    href="https://coffeeking.like-themes.com/product/ground-coffee/"
                                                                    class="header"><h5>Ground coffee</h5></a>
                                                                <div class="post_content entry-content">Duis et aliquam
                                                                    orci. Vivamus augue quam, ...
                                                                </div>
                                                                <h4 class="price color-main"><span
                                                                        class="woocommerce-Price-amount amount"><bdi><span
                                                                                class="woocommerce-Price-currencySymbol">$</span>13.44</bdi></span>
                                                                </h4>
                                                                <a rel="nofollow" href="?add-to-cart=2164"
                                                                   data-quantity="1"
                                                                   data-product_id="2164" data-product_sku=""
                                                                   class="ajax_add_to_cart button btn btn-default color-hover-black btn-xs transform-lowercase add_to_cart_button"><i
                                                                        class="fa fa-shopping-cart"
                                                                        aria-hidden="true"></i>Add
                                                                    to cart</a></div>
                                                        </article>
                                                    </div>


                                                </div>
                                                <div class="arrows"><a href="#"
                                                                       class="arrow-left fa fa-chevron-left swiper-button-disabled"></a>
                                                    <a href="#" class="arrow-right fa fa-chevron-right"></a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="es-resp">
                                <div class="hidden-sm hidden-ms hidden-xs" style="height: 80px;"></div>
                                <div class="hidden-xl hidden-lg hidden-md hidden-xs" style="height: 50px;"></div>
                                <div class="visible-xs" style="height: 50px;"></div>
                            </div>
                        </div>
                    </div>















                </div>
            </div>
        </div>
    </section>




    <div class="vc_row-full-width vc_clearfix"></div>






    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
