<?php
/*
 * This file belongs to the YITH Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCEVTI_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Tickets
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_Tickets' ) ) {
	/**
	 * Class YITH_Tickets
	 *
	 * @author Francisco Mateo
	 */
	class YITH_Tickets {
        /**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0
		 */
		public $version = YITH_WCEVTI_VERSION;

        /**
		 * Main Instance
		 *
		 * @var YITH_Tickets
		 * @since 1.0
		 * @access protected
		 */
		protected static $_instance = null;

		/**
		 * Main Admin Instance
		 *
		 * @var YITH_Tickets_Admin
		 * @since 1.0
		 */
		public $admin = null;

        /**
		 * Main Frontpage Instance
		 *
		 * @var YITH_Tickets_Frontend
		 * @since 1.0
		 */
		public $frontend = null;

        public $widget_calendar = null;


        /**
         * Construct
         *
         * @author Francisco Mateo
         * @since 1.0
         */
        public function __construct(){

			/* === Require Main Files === */
			$require = apply_filters( 'yith_wcevti_require_class',
				array(
					'common'    => array(
                        'includes/functions.yith-wcevti.php',
						'includes/class.yith-event-tickets-event.php'
                    ),
					'frontend'     => array(
						'includes/class.yith-event-tickets-frontend.php'
					),
					'admin'     => array(
                        'includes/class.yith-event-tickets-admin.php'
					)

				)
			);

			$this->_require( $require );

            /* === Load Plugin Framework === */
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

			/* === Product Event Ticket === */
			add_action('product_type_selector', array($this, 'define_product_type'));
            add_filter('woocommerce_email_attachments', array($this, 'attach_items_pdf_mail'), 10, 3);

			/* === Plugins Init === */
            add_action( 'init', array( $this, 'init' ) );
            add_action( 'init', array( $this, 'order_event_ticket_init' ));
            add_action( 'init', array($this, 'add_custom_image_sizes'));

        }

            /**
		 * Main plugin Instance
		 *
		 * @return YITH_Tickets Main instance
		 * @author Francisco Mateo
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

        /**
		 * Add the main classes file
		 *
		 * Include the admin and frontend classes
		 *
		 * @param $main_classes array The require classes file path
		 *
		 * @author Francisco Mateo
		 * @since  1.0
		 *
		 * @return void
		 * @access protected
		 */
		protected function _require( $main_classes ) {
			foreach ( $main_classes as $section => $classes ) {
				foreach ( $classes as $class ) {
					if ( 'common' == $section  || ( 'frontend' == $section && ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) || ( 'admin' == $section && is_admin() ) && file_exists( YITH_WCEVTI_PATH . $class ) ) {
						require_once( YITH_WCEVTI_PATH . $class );
					}
				}
			}
			do_action('yith_wcevti_require');
        }

		/**
		 * Load plugin framework
		 *
		 * @author Francisco Mateo
		 * @since  1.0
		 * @return void
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
		 * This function define type product to display on WooCommerce type products selector
		 *
		 * @author Francisco Mateo
		 * @since  1.0
		 * @return $types
		 * @access public
		 */
		public function define_product_type($types) {

			$types[ 'ticket-event' ] = _x( 'Event Ticket', 'product type in Edit product page', 'yith-event-tickets-for-woocommerce' );

			return $types;
		}

        /**
		 * Class Initialization
		 *
		 * Instance the admin class
		 *
		 * @author Francisco Mateo
		 * @since  1.0
		 * @return void
		 * @access protected
		 */
		public function init() {
			global $wp_query;
            if ( is_admin() ) {
				$this->admin = new YITH_Tickets_Admin();
			}

			if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX )) {
				
				$this->frontend = new YITH_Tickets_Frontend();
			}
        }

		public function order_event_ticket_init(){
            $labels = array(
                'name'                  => _x( 'Purchased tickets', 'Handle purchased tickets on your shop', 'yith-event-tickets-for-woocommerce' ),
                'singular_name'         => _x( 'Purchased tickets', 'Purchased tickets', 'yith-event-tickets-for-woocommerce' ),
                'menu_name'             => _x( 'Tickets', 'Purchased tickets', 'yith-event-tickets-for-woocommerce' ),
                'edit_item'             => __( 'Edit Ticket', 'yith-event-tickets-for-woocommerce' )
                );

            $args = array(
                'labels'             => $labels,
                'public'             => false,
                'show_ui'            => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'order' ),
                'hierarchical'       => true,
                'menu_position'      => null,
                'supports'           => array(),
                'menu_icon'           => 'dashicons-tickets-alt'
            );

            register_post_type( 'ticket', $args );
        }

        public function add_custom_image_sizes(){

            add_image_size('default_header_mail', 420, 203, true);
            add_image_size('default_content_mail', 601,340, true);
            add_image_size('default_footer_mail', 66, 43, true);
        }

        public function attach_items_pdf_mail($attachments, $email_id, $object){
            $allowed_emails = array('customer_processing_order', 'customer_completed_order');

            if(in_array($email_id, $allowed_emails) && is_a($object, 'WC_Order')){
                $order_items = $object->get_items();
                foreach ($order_items as $item){
                    if('ticket-event' == $item['product_type'] ){
                        $pdf_path = YITH_WCEVTI_DOCUMENT_SAVE_PDF_DIR . $item['event_id'] .  '.pdf';
                        $attachments[] = $pdf_path;
                    }
                }
            }
            return $attachments;
        }
    }
}