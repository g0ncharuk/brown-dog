<?php
/**
 * 
 * @package own-shop
 */


/**
 * WooCommerce setup
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function own_shop_woocommerce_setup() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'own_shop_woocommerce_setup' );


/**
 * WooCommerce scripts & styles
 *
 * @return void
 */
function own_shop_woocommerce_scripts() {
    wp_enqueue_style( 'own-shop-woocommerce-style', get_template_directory_uri() . '/css/woocommerce-style.min.css', array(), wp_get_theme()->get('Version'));
}
add_action( 'wp_enqueue_scripts', 'own_shop_woocommerce_scripts' );


/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function own_shop_woocommerce_active_body_class( $classes ) {
    $classes[] = 'woocommerce-active';

    return $classes;
}
add_filter( 'body_class', 'own_shop_woocommerce_active_body_class' );


/**
* Cart Fragments
*/
if ( ! function_exists( 'own_shop_woocommerce_cart_link_fragment' ) ) :
function own_shop_woocommerce_cart_link_fragment( $fragments ) {
    ob_start();
    own_shop_woocommerce_cart_link();
    $fragments['a.cart-content'] = ob_get_clean();

    return $fragments;
}
endif;
add_filter( 'woocommerce_add_to_cart_fragments', 'own_shop_woocommerce_cart_link_fragment' );


/**
* Cart Link
*/
if ( ! function_exists( 'own_shop_woocommerce_cart_link' ) ) :
function own_shop_woocommerce_cart_link() {
    $own_shop_cart_icon_title = apply_filters( 'own_shop_cart_icon_title', esc_html__( 'View your shopping cart', 'own-shop' ) );
    ?>
        <a class="cart-content" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo esc_attr( $own_shop_cart_icon_title ); ?>">
            <i class="la la-shopping-bag"></i>
            <?php $item_count_text = WC()->cart->get_cart_contents_count(); ?>
            <span class="count badge">
                <?php echo esc_html( $item_count_text ); ?>
            </span>
            <span class="cart-details">
                <label class="your-cart"><?php esc_html_e('Your Cart','own-shop') ?></label>
                <label class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></label>
            </span>
        </a>
    <?php
}
endif;


/**
* Header Cart
*/
if ( ! function_exists( 'own_shop_woocommerce_header_cart' ) ) :
function own_shop_woocommerce_header_cart() {
    $own_shop_cart_link_option = get_theme_mod( 'own_shop_cart_link_option', true );
    if ( false == $own_shop_cart_link_option ) :
        return;
    endif;
    if ( is_cart() ) :
        $class = 'current-menu-item';
    else :
        $class = '';
    endif;
    ?>
        <ul id="site-header-cart" class="site-header-cart">
            <li class="menu-cart <?php echo esc_attr( $class ); ?>">
                <?php own_shop_woocommerce_cart_link(); ?>
            </li>
            <li>
                <?php
                    $instance = array(
                        'title' => '',
                    );
                    the_widget( 'WC_Widget_Cart', $instance );
                ?>
            </li>
        </ul>
    <?php
}
endif;


/**
* Header Signup Links
*/
if ( ! function_exists( 'own_shop_woocommerce_header_signup_links' ) ) :
function own_shop_woocommerce_header_signup_links() {
    ?>
        <i class="la la-user"></i>
        <span class="register">
            <?php
                if ( is_user_logged_in() ) :
                    ?>
                        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php esc_attr_e('My Account','own-shop'); ?>"><?php esc_html_e('My Account','own-shop'); ?></a>
                    <?php
                else :
                    ?>  
                        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php esc_attr_e('Sign in','own-shop'); ?>"><?php esc_html_e('Sign in','own-shop'); ?></a>
                    <?php
                endif;
            ?>
        </span>
    <?php
}
endif;


/**
 * Check if Quick View is activated.
 */
function own_shop_is_active_quick_view() {
    if ( class_exists( 'YITH_WCQV_Frontend' ) ) :
        return true;
    else :
        return false;
    endif;
}


/**
* Product Search form with categories
*/
if (!function_exists('own_shop_product_search_form')) :
function own_shop_product_search_form() {
    ?>
    <div class="search-form-wrapper">
        <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="form-group search">
                <?php
                    $search_placeholder = esc_html(get_theme_mod('own_shop_product_search_placeholder', esc_html__('Search for products','own-shop'))) ;
                    $cat_placeholder = esc_html(get_theme_mod('own_shop_product_search_category_placeholder',esc_html__('All Categories','own-shop'))) ;
                ?>
                <label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e('Search for:', 'own-shop'); ?></label>
                <input type="search" id="woocommerce-product-search-field" class="search-field"   placeholder="<?php echo esc_attr($search_placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s"/>
                <?php
                    $product_cats = get_terms(array(
                        'taxonomy' => 'product_cat',
                    ));
                    if (!empty($product_cats) && !is_wp_error($product_cats)) :
                        $selected_product_cat = get_query_var('product_cat');
                        ?>
                            <select name="product_cat" class="category-dropdown">
                                <option value=""><?php echo '&mdash; ' . esc_attr($cat_placeholder) . ' &mdash;'; ?></option>
                                <?php
                                    foreach ($product_cats as $product_cat) {
                                        ?>
                                            <option value="<?php echo esc_attr($product_cat->slug) ?>" <?php esc_html(selected($product_cat->slug, $selected_product_cat)) ?>> <?php echo esc_html($product_cat->name); ?>
                                            </option>
                                        <?php
                                    }
                                ?>
                            </select>
                        <?php
                    endif;
                ?>
                <button type="submit" value=""><i class="la la-search" aria-hidden="true"></i> <?php esc_html_e('Search','own-shop') ?></button>
                <input type="hidden" name="post_type" value="product"/>
            </div>
        </form>
    </div>
    <?php
}
endif;


/**
* Display Product search form within sidebar
*/ 
if (!function_exists('own_shop_sidebar_product_search_form')) :
function own_shop_sidebar_product_search_form() {
    ?>
    <div class="search-form-wrapper">
        <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="form-group search">
                <?php
                    $search_placeholder = esc_html(get_theme_mod('own_shop_product_search_placeholder', esc_html__('Search for products','own-shop'))) ;
                ?>
                <label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e('Search for:', 'own-shop'); ?></label>
                <input type="search" id="woocommerce-product-search-field" class="search-field"   placeholder="<?php echo esc_attr($search_placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s"/>
                <button type="submit" value=""><i class="la la-search" aria-hidden="true"></i> <?php esc_html_e('Search','own-shop') ?></button>
                <input type="hidden" name="post_type" value="product"/>
            </div>
        </form>
    </div>
    <?php
}
endif;


/**
* Header Category Custom Menu
*/
if ( ! function_exists( 'own_shop_header_product_custom_menu' ) ) :
function own_shop_header_product_custom_menu() {
    ?>
        <div class="header-product-custom-menu">
            <div class="custom-menu-wrapper">
                <a href="#" class="title navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-2"><i class="la la-list"></i> <?php 
                    echo esc_html(get_theme_mod( 'own_shop_header_category_heading_text', esc_html__('All Departments','own-shop')));
                    ?>
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-2">
                    <span><i class="la la-chevron-down"></i><i class="la la-chevron-up"></i></span>
                </button>
            </div>
            <div class="custom-menu-product">
                <div class="collapse navbar-collapse" id="navbar-collapse-2">
                    <?php
                        wp_nav_menu( array(                             
                            'theme_location'    => 'categorymenu',
                            'depth'             => 3,
                            'container'         => 'ul',
                            'container_class'   => 'product-custom-menu-container',
                            'container_id'      => 'menu-categorymenu',
                            'menu_class'        => 'category-custom',
                            )
                        );
                    ?>
                </div>
            </div>
        </div>
    <?php
}
endif;


/**
* Display Sale Price
*/
if ( ! function_exists( 'own_shop_change_displayed_sale_price_html' ) ) :
function own_shop_change_displayed_sale_price_html( $price, $product ) {
    // Only on sale products on frontend and excluding min/max price on variable products
    if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')) :
        // Get product prices
        $regular_price = (float) $product->get_regular_price(); // Regular price
        $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)

        // "Saving Percentage" calculation and formatting
        $precision = 1; // Max number of decimals
        $saving_percentage = round( 100 - ( $sale_price / $regular_price * 100 ), 1 ) . '%';

        // Append to the formated html price
        $price .= sprintf( __('<p class="saved-sale">Save: %s</p>', 'own-shop' ), $saving_percentage );
    endif;
    return $price;
}
endif;
add_filter( 'woocommerce_get_price_html', 'own_shop_change_displayed_sale_price_html', 10, 2 );


/**
 * Adding checkout sidebar classes to body
 */
if ( ! function_exists( 'own_shop_add_checkout_sidebar_classes_to_body' ) ) :
function own_shop_add_checkout_sidebar_classes_to_body($classes = '') {
    if('right'===esc_html(get_theme_mod('own_shop_checkout_page_sidebar_layout','right'))) :
        $classes[] = 'right-sidebar-checkout';
    elseif('left'===esc_html(get_theme_mod('own_shop_checkout_page_sidebar_layout','right'))) :
        $classes[] = 'left-sidebar-checkout';   
    elseif('no'===esc_html(get_theme_mod('own_shop_checkout_page_sidebar_layout','right'))) :
        $classes[] = 'left-sidebar-checkout';
    else :
        $classes[] = 'left-sidebar-checkout';
    endif;
    return $classes;
}
endif;
add_filter('body_class', 'own_shop_add_checkout_sidebar_classes_to_body');


/**
 * Adding cart sidebar classes to body
 */
if ( ! function_exists( 'own_shop_add_cart_sidebar_classes_to_body' ) ) :
function own_shop_add_cart_sidebar_classes_to_body($classes = '') {
    if('right'===esc_html(get_theme_mod('own_shop_cart_page_sidebar_layout','right'))) :
        $classes[] = 'right-sidebar-cart';
    elseif('left'===esc_html(get_theme_mod('own_shop_cart_page_sidebar_layout','right'))) :
        $classes[] = 'left-sidebar-cart';   
    elseif('no'===esc_html(get_theme_mod('own_shop_cart_page_sidebar_layout','right'))) :
        $classes[] = 'left-sidebar-cart';
    else :
        $classes[] = 'left-sidebar-cart';
    endif;
    return $classes;
}
endif;
add_filter('body_class', 'own_shop_add_cart_sidebar_classes_to_body');


/**
 * Related Products
*/

if (!function_exists('own_shop_filter_woocommerce_output_related_products_args')) :
function own_shop_filter_woocommerce_output_related_products_args( $args ) {     
    $args=array(    
    'posts_per_page' => intval( get_theme_mod('own_shop_row_items','3') ),
    'columns' => intval( get_theme_mod('own_shop_row_items','3') ),
    );
    return $args; 
};
endif;
add_filter( 'woocommerce_output_related_products_args', 'own_shop_filter_woocommerce_output_related_products_args', 10, 1 ); 
