<?php
/**
 * Main class
 *
 * @author  Yithemes
 * @package YITH WooCommerce Membership
 * @version 1.0.0
 */


if ( !defined( 'YITH_WCMBS' ) ) {
    exit;
} // Exit if accessed directly

if ( !class_exists( 'YITH_WCMBS' ) ) {
    /**
     * YITH WooCommerce Membership
     *
     * @since 1.0.0
     */
    class YITH_WCMBS {

        /**
         * Single instance of the class
         *
         * @var \YITH_WCMBS
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version = YITH_WCMBS_VERSION;

        /**
         * Plugin object
         *
         * @var string
         * @since 1.0.0
         */
        public $obj = null;

        /**
         * Returns single instance of the class
         *
         * @return \YITH_WCMBS
         * @since 1.0.0
         */
        public static function get_instance() {
            $self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

            if ( is_null( $self::$instance ) ) {
                $self::$instance = new $self;
            }

            return $self::$instance;
        }

        /**
         * Constructor
         *
         * @return mixed| YITH_WCMBS_Admin | YITH_WCMBS_Frontend
         * @since 1.0.0
         */
        public function __construct() {

            // Load Plugin Framework
            add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );


            if ( defined( 'YITH_WCMBS_PREMIUM' ) && YITH_WCMBS_PREMIUM ) {
                YITH_WCMBS_Products_Manager();
                YITH_WCMBS_Compatibility();
                YITH_WCMBS_Protected_Media();
                YITH_WCMBS_Cron();
            }

            // Class admin
            if ( is_admin() ) {
                YITH_WCMBS_Admin();
            } else {
                YITH_WCMBS_Frontend();
            }

            // Add widget for Messages if Premium
            if ( defined( 'YITH_WCMBS_PREMIUM' ) && YITH_WCMBS_PREMIUM ) {
                add_action( 'widgets_init', array( $this, 'register_widgets' ) );
                YITH_WCMBS_Messages_Manager_Frontend();
                YITH_WCMBS_Notifier();
            }


        }


        /**
         * Load Plugin Framework
         *
         * @since  1.0
         * @access public
         * @return void
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function plugin_fw_loader() {
            if ( !defined( 'YIT_CORE_PLUGIN' ) ) {
                global $plugin_fw_data;
                if ( !empty( $plugin_fw_data ) ) {
                    $plugin_fw_file = array_shift( $plugin_fw_data );
                    require_once( $plugin_fw_file );
                }
            }
        }


        /**
         * register Widget for Messages
         *
         * @access public
         * @since  1.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function register_widgets() {
            register_widget( 'YITH_WCBSL_Messages_Widget' );
        }
    }
}

/**
 * Unique access to instance of YITH_WCMBS class
 *
 * @return \YITH_WCMBS
 * @since 1.0.0
 */
function YITH_WCMBS() {
    return YITH_WCMBS::get_instance();
}

?>