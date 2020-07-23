<?php
/**
 * Woocommerce wrappers
 *
 * @package Life_In_Balance
 */


if ( !class_exists('WooCommerce') )
    return;

/**
 * Declare support
 */
function life_in_balance_wc_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'life_in_balance_wc_support' );

/**
 * Add and remove actions
 */
function life_in_balance_woo_actions() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
    add_action('woocommerce_before_main_content', 'life_in_balance_wc_wrapper_start', 10);
    add_action('woocommerce_after_main_content', 'life_in_balance_wc_wrapper_end', 10);
}
add_action('wp','life_in_balance_woo_actions');

/**
 * Theme wrappers
 */
function life_in_balance_wc_wrapper_start() {
    echo '<div id="primary" class="content-area col-md-9">';
        echo '<main id="main" class="site-main" role="main">';
}

function life_in_balance_wc_wrapper_end() {
        echo '</main>';
    echo '</div>';
}

/**
 * Archive titles
 */
function life_in_balance_woo_archive_title() {
    echo '<h3 class="archive-title">';
    echo woocommerce_page_title();
    echo '</h3>';
}
add_filter( 'woocommerce_show_page_title', 'life_in_balance_woo_archive_title' );

/**
 * Remove default WooCommerce CSS
 */
function life_in_balance_dequeue_styles( $enqueue_styles ) {
    unset( $enqueue_styles['woocommerce-general'] ); 
    return $enqueue_styles;
}
add_filter( 'woocommerce_enqueue_styles', 'life_in_balance_dequeue_styles' );

/**
 * Enqueue custom CSS for Woocommerce
 */
function life_in_balance_woocommerce_css() {
    wp_enqueue_style( 'life_in_balance-wc-css', get_template_directory_uri() . '/woocommerce/css/wc.css' );
}
add_action( 'wp_enqueue_scripts', 'life_in_balance_woocommerce_css', 1 );

/**
 * Number of related products
 */
function life_in_balance_related_products_args( $args ) {
    $args['posts_per_page'] = 3;
    $args['columns'] = 3;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'life_in_balance_related_products_args' );

/**
 * Variable products button
 */
function life_in_balance_single_variation_add_to_cart_button() {
    global $product;
    ?>
    <div class="woocommerce-variation-add-to-cart variations_button">
        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

        <?php
            do_action( 'woocommerce_before_add_to_cart_quantity' );

            woocommerce_quantity_input( array(
                'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1,
            ) );

            do_action( 'woocommerce_after_add_to_cart_quantity' );
        ?>
        <button type="submit" class="roll-button cart-button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
        <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
        <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
        <input type="hidden" name="variation_id" class="variation_id" value="0" />
    </div>
     <?php
}
add_action( 'woocommerce_single_variation', 'life_in_balance_single_variation_add_to_cart_button', 21 );
