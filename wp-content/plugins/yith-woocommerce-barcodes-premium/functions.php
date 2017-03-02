<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/** Define constants for post meta key */
defined( 'YITH_YWBC_META_KEY_BARCODE_PROTOCOL' ) || define( 'YITH_YWBC_META_KEY_BARCODE_PROTOCOL', '_ywbc_barcode_protocol' );
defined( 'YITH_YWBC_META_KEY_BARCODE_VALUE' ) || define( 'YITH_YWBC_META_KEY_BARCODE_VALUE', '_ywbc_barcode_value' );
defined( 'YITH_YWBC_META_KEY_BARCODE_DISPLAY_VALUE' ) || define( 'YITH_YWBC_META_KEY_BARCODE_DISPLAY_VALUE', '_ywbc_barcode_display_value' );
defined( 'YITH_YWBC_META_KEY_BARCODE_IMAGE' ) || define( 'YITH_YWBC_META_KEY_BARCODE_IMAGE', '_ywbc_barcode_image' );
defined( 'YITH_YWBC_META_KEY_BARCODE_FILENAME' ) || define( 'YITH_YWBC_META_KEY_BARCODE_FILENAME', '_ywbc_barcode_filename' );


if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! function_exists( 'yith_initialize_plugin_fw' ) ) {
	/**
	 * Initialize plugin-fw
	 */
	function yith_initialize_plugin_fw( $plugin_dir ) {
		if ( ! function_exists( 'yit_deactive_free_version' ) ) {
			require_once $plugin_dir . 'plugin-fw/yit-deactive-plugin.php';
		}

		if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
			require_once $plugin_dir . 'plugin-fw/yit-plugin-registration-hook.php';
		}

		/* Plugin Framework Version Check */
		if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( $plugin_dir . 'plugin-fw/init.php' ) ) {
			require_once( $plugin_dir . 'plugin-fw/init.php' );
		}
	}
}

if ( ! function_exists( 'yith_ywbc_install_woocommerce_admin_notice' ) ) {

	function yith_ywbc_install_woocommerce_admin_notice() {
		?>
		<div class="error">
			<p><?php _e( 'YITH WooCommerce Barcodes is enabled but not effective. It requires WooCommerce in order to work.', 'yit' ); ?></p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'yith_ywbc_install' ) ) {
	/**
	 * Install the plugin
	 */
	function yith_ywbc_install() {

		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'yith_ywbc_install_woocommerce_admin_notice' );
		} else {
			do_action( 'yith_ywbc_init' );
		}
	}
}

if ( ! function_exists( 'yith_ywbc_init' ) ) {
	/**
	 * Start the plugin
	 */
	function yith_ywbc_init() {
		/**
		 * Load text domain
		 */
		load_plugin_textdomain( 'yith-woocommerce-barcodes', false, dirname( YITH_YWBC_BASENAME ) . '/languages/' );

		/** include plugin's files */

		require_once( YITH_YWBC_INCLUDES_DIR . 'class-yith-woocommerce-barcodes.php' );
		require_once( YITH_YWBC_INCLUDES_DIR . 'class-yith-barcode.php' );
		require_once( YITH_YWBC_INCLUDES_DIR . 'class-ywbc-plugin-fw-loader.php' );

		YITH_YWBC();
	}
}

if ( ! function_exists( 'ywbc_main' ) ) {
	/**
	 * Instantiate the plugin main file
	 *
	 * @author      Lorenzo Giuffrida
	 * @since       1.0.0
	 * @deprecated  1.0.9
	 * @return YITH_WooCommerce_Barcodes
	 */
	function ywbc_main() {
		_deprecated_function( 'ywbc_main', '1.0.9', 'YITH_YWBC' );

		return YITH_YWBC();
	}
}

if ( ! function_exists( 'YITH_YWBC' ) ) {
	/**
	 * Instantiate the plugin main file
	 *
	 * @author      Lorenzo Giuffrida
	 * @since       1.0.0
	 * @return YITH_WooCommerce_Barcodes
	 */
	function YITH_YWBC() {
		return YITH_WooCommerce_Barcodes::get_instance();
	}
}

add_action( 'yith_ywbc_init', 'yith_ywbc_init' );




