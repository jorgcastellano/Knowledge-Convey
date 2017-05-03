<?php
/*
Plugin Name: YITH Event Tickets for WooCommerce Premium
Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-event-tickets
Description: YITH Event Tickets for WooCommerce
Author: YITHEMES
Text Domain: yith-event-tickets-for-woocommerce
Version: 1.1.2
Author URI: http://yithemes.com/
*/

/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
$wp_upload_dir = wp_upload_dir();
/* === DEFINE === */
! defined( 'YITH_WCEVTI_VERSION' )              && define( 'YITH_WCEVTI_VERSION', '1.1.2' );
! defined( 'YITH_WCEVTI_INIT' )                 && define( 'YITH_WCEVTI_INIT', plugin_basename( __FILE__ ) );
! defined( 'YITH_WCEVTI_SLUG' )                 && define( 'YITH_WCEVTI_SLUG', 'yith-event-tickets-for-woocommerce' );
! defined( 'YITH_WCEVTI_SECRETKEY' )            && define( 'YITH_WCEVTI_SECRETKEY', 'ydq1FqEKDQkIgcgfInrd' );
! defined( 'YITH_WCEVTI_FILE' )                 && define( 'YITH_WCEVTI_FILE', __FILE__ );
! defined( 'YITH_WCEVTI_PATH' )                 && define( 'YITH_WCEVTI_PATH', plugin_dir_path( __FILE__ ) );
! defined( 'YITH_WCEVTI_URL' )                  && define( 'YITH_WCEVTI_URL', plugins_url( '/', __FILE__ ) );
! defined( 'YITH_WCEVTI_ASSETS_URL' )           && define( 'YITH_WCEVTI_ASSETS_URL', YITH_WCEVTI_URL . 'assets/' );
! defined( 'YITH_WCEVTI_TEMPLATE_PATH' )        && define( 'YITH_WCEVTI_TEMPLATE_PATH', YITH_WCEVTI_PATH . 'templates/' );
! defined( 'YITH_WCEVTI_OPTIONS_PATH' )         && define( 'YITH_WCEVTI_OPTIONS_PATH', YITH_WCEVTI_PATH . 'panel' );
! defined( 'YITH_WCEVTI_VENDOR_PATH')           && define( 'YITH_WCEVTI_VENDOR_PATH', YITH_WCEVTI_PATH . 'vendor/');
! defined( 'YITH_WCEVTI_WIDGET_PATH')           && define( 'YITH_WCEVTI_WIDGET_PATH', YITH_WCEVTI_PATH . 'widgets/');
! defined( 'YITH_WCEVTI_DOCUMENT_SAVE_PDF_DIR') && define( 'YITH_WCEVTI_DOCUMENT_SAVE_PDF_DIR' , $wp_upload_dir['basedir'] . '/ywcevti-pdf-tickets/');
! defined( 'YITH_WCEVTI_PREMIUM' )              && define( 'YITH_WCEVTI_PREMIUM', '1' );

/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WCEVTI_PATH . 'plugin-fw/init.php' ) ) {
    require_once( YITH_WCEVTI_PATH . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_WCEVTI_PATH  );

/* Load YWCM text domain */
load_plugin_textdomain( 'yith-event-tickets-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

if ( ! function_exists( 'YITH_Tickets' ) ) {
	/**
	 * Unique access to instance of YITH_Vendors class
	 *
	 * @return YITH_Tickets | YITH_Tickets_Premium
	 * @since 1.0.0
	 */
	function YITH_Tickets() {
		// Load required classes and functions

        if(!class_exists('mPDF')){
            require_once (YITH_WCEVTI_VENDOR_PATH . 'autoload.php');
        }
        if(!file_exists( YITH_WCEVTI_DOCUMENT_SAVE_PDF_DIR )){
            wp_mkdir_p(  YITH_WCEVTI_DOCUMENT_SAVE_PDF_DIR );
        }

        require_once( YITH_WCEVTI_PATH . 'includes/class.yith-event-tickets.php' );

        if ( defined( 'YITH_WCEVTI_PREMIUM' ) && file_exists( YITH_WCEVTI_PATH . 'includes/class.yith-event-tickets-premium.php') ) {
            require_once( YITH_WCEVTI_PATH . 'includes/class.yith-event-tickets-premium.php' );
            return YITH_Tickets_Premium::instance();
        }

		return YITH_Tickets::instance();
	}
}

/**
 * Instance main plugin class
 */
YITH_Tickets();