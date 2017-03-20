<?php
/**
 * Plugin Name: YITH WooCommerce Minimum Maximum Quantity Premium
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-minimum-maximum-quantity/
 * Description: YITH WooCommerce Minimum Maximum Quantity allows you to set a minimum and/or maximum quantity for purchases in your shop
 * Author: YIThemes
 * Text Domain: yith-woocommerce-minimum-maximum-quantity
 * Version: 1.0.3
 * Author URI: http://yithemes.com/
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function ywmmq_install_woocommerce_premium_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'YITH WooCommerce Minimum Maximum Quantity is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-minimum-maximum-quantity' ); ?></p>
    </div>
<?php
}

if ( !function_exists( 'yit_deactive_free_version' ) ) {
    require_once 'plugin-fw/yit-deactive-plugin.php';
}

yit_deactive_free_version( 'YWMMQ_FREE_INIT', plugin_basename( __FILE__ ) );

if ( !defined( 'YWMMQ_VERSION' ) ) {
    define( 'YWMMQ_VERSION', '1.0.3' );
}

if ( !defined( 'YWMMQ_INIT' ) ) {
    define( 'YWMMQ_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YWMMQ_SLUG' ) ) {
    define( 'YWMMQ_SLUG', 'yith-woocommerce-minimum-maximum-quantity' );
}

if ( !defined( 'YWMMQ_SECRET_KEY' ) ) {
    define( 'YWMMQ_SECRET_KEY', 'XA2SDO3adDGWqlRGL0YK' );
}

if ( !defined( 'YWMMQ_PREMIUM' ) ) {
    define( 'YWMMQ_PREMIUM', '1' );
}

if ( !defined( 'YWMMQ_FILE' ) ) {
    define( 'YWMMQ_FILE', __FILE__ );
}

if ( !defined( 'YWMMQ_DIR' ) ) {
    define( 'YWMMQ_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'YWMMQ_URL' ) ) {
    define( 'YWMMQ_URL', plugins_url( '/', __FILE__ ) );
}

if ( !defined( 'YWMMQ_ASSETS_URL' ) ) {
    define( 'YWMMQ_ASSETS_URL', YWMMQ_URL . 'assets' );
}

if ( !defined( 'YWMMQ_TEMPLATE_PATH' ) ) {
    define( 'YWMMQ_TEMPLATE_PATH', YWMMQ_DIR . 'templates' );
}

/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWMMQ_DIR . 'plugin-fw/init.php' ) ) {
    require_once( YWMMQ_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YWMMQ_DIR  );

function ywmmq_init() {

    /* Load text domain */
    load_plugin_textdomain( 'yith-woocommerce-minimum-maximum-quantity', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    /* === Global YITH WooCommerce Minimum Maximum Quantity  === */
    YITH_WMMQ();

}

add_action( 'ywmmq_init', 'ywmmq_init' );

function ywmmq_install() {

    if ( !function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'ywmmq_install_woocommerce_premium_admin_notice' );
    }
    else {
        do_action( 'ywmmq_init' );
    }

}

add_action( 'plugins_loaded', 'ywmmq_install', 11 );

/**
 * Init default plugin settings
 */
if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}

register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( !function_exists( 'YITH_WMMQ' ) ) {

    /**
     * Unique access to instance of YITH_WC_Min_Max_Qty
     *
     * @since   1.0.0
     * @return  YITH_WC_Min_Max_Qty|YITH_WC_Min_Max_Qty_Premium
     * @author  Alberto Ruggiero
     */
    function YITH_WMMQ() {

        // Load required classes and functions
        require_once( YWMMQ_DIR . 'class.yith-wc-min-max-qty.php' );

        if ( defined( 'YWMMQ_PREMIUM' ) && file_exists( YWMMQ_DIR . 'class.yith-wc-min-max-qty-premium.php' ) ) {

            require_once( YWMMQ_DIR . 'class.yith-wc-min-max-qty-premium.php' );
            return YITH_WC_Min_Max_Qty_Premium::get_instance();
        }

        return YITH_WC_Min_Max_Qty::get_instance();

    }

}