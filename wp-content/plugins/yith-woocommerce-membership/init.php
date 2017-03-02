<?php
/**
 * Plugin Name: YITH WooCommerce Membership
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-membership/
 * Description: YITH WooCommerce Membership allows you to create custom membership for the contents of your shop.
 * Version: 1.0.7
 * Author: YITHEMES
 * Author URI: http://yithemes.com/
 * Text Domain: yith-woocommerce-membership
 * Domain Path: /languages/
 *
 * @author  yithemes
 * @package YITH WooCommerce Membership
 * @version 1.0.7
 */
/*  Copyright 2015  Your Inspiration Themes  (email : plugins@yithemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* == COMMENT == */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function yith_wcmbs_install_woocommerce_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'YITH WooCommerce Membership is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-membership' ); ?></p>
    </div>
    <?php
}


function yith_wcmbs_install_free_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Membership while you are using the premium one.', 'yith-woocommerce-membership' ); ?></p>
    </div>
    <?php
}

if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


if ( !defined( 'YITH_WCMBS_VERSION' ) ) {
    define( 'YITH_WCMBS_VERSION', '1.0.7' );
}

if ( !defined( 'YITH_WCMBS_FREE_INIT' ) ) {
    define( 'YITH_WCMBS_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YITH_WCMBS' ) ) {
    define( 'YITH_WCMBS', true );
}

if ( !defined( 'YITH_WCMBS_FILE' ) ) {
    define( 'YITH_WCMBS_FILE', __FILE__ );
}

if ( !defined( 'YITH_WCMBS_URL' ) ) {
    define( 'YITH_WCMBS_URL', plugin_dir_url( __FILE__ ) );
}

if ( !defined( 'YITH_WCMBS_DIR' ) ) {
    define( 'YITH_WCMBS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'YITH_WCMBS_TEMPLATE_PATH' ) ) {
    define( 'YITH_WCMBS_TEMPLATE_PATH', YITH_WCMBS_DIR . 'templates' );
}

if ( !defined( 'YITH_WCMBS_ASSETS_URL' ) ) {
    define( 'YITH_WCMBS_ASSETS_URL', YITH_WCMBS_URL . 'assets' );
}

if ( !defined( 'YITH_WCMBS_ASSETS_PATH' ) ) {
    define( 'YITH_WCMBS_ASSETS_PATH', YITH_WCMBS_DIR . 'assets' );
}


function yith_wcmbs_init() {

    load_plugin_textdomain( 'yith-woocommerce-membership', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    // Load required classes and functions
    require_once( 'includes/functions.yith-wcmbs.php' );
    require_once( 'includes/class.ajax-products-field.php' );
    require_once( 'includes/class.yith-wcmbs-membership.php' );
    require_once( 'includes/class.yith-wcmbs-activity.php' );
    require_once( 'includes/class.yith-wcmbs-membership-helper.php' );
    require_once( 'includes/class.yith-wcmbs-members.php' );
    require_once( 'includes/class.yith-wcmbs-member.php' );
    require_once( 'includes/class.yith-wcmbs-manager.php' );
    require_once( 'class.yith-wcmbs-frontend.php' );
    require_once( 'class.yith-wcmbs-admin.php' );
    require_once( 'class.yith-wcmbs.php' );

    // U P D A T E
    require_once( 'includes/functions.yith-wcmbs-update.php' );

    // Let's start the game!
    YITH_WCMBS();
}

add_action( 'yith_wcmbs_init', 'yith_wcmbs_init' );


function yith_wcmbs_install() {

    if ( !function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'yith_wcmbs_install_woocommerce_admin_notice' );
    } elseif ( defined( 'YITH_WCMBS_PREMIUM' ) ) {
        add_action( 'admin_notices', 'yith_wcmbs_install_free_admin_notice' );
        deactivate_plugins( plugin_basename( __FILE__ ) );
    } else {
        do_action( 'yith_wcmbs_init' );
    }
}

add_action( 'plugins_loaded', 'yith_wcmbs_install', 11 );

/* Plugin Framework Version Check */
if ( !function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( plugin_dir_path( __FILE__ ) . 'plugin-fw/init.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( plugin_dir_path( __FILE__ ) );