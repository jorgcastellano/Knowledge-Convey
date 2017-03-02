<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'YITH_YWBC_Shortcodes' ) ) {

	/**
	 *
	 * @class   YITH_YWBC_Shortcodes
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_YWBC_Shortcodes {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 */
		protected static $instance;

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		protected function __construct() {

			/**
			 * Add a shortcode for rendering a barcode
			 */
			add_shortcode( "yith_render_barcode", array(
				$this,
				"yith_render_barcode"
			) );

			/**
			 * Add a shortcode for searching for a order barcode value
			 */
			add_shortcode( "yith_order_barcode", array(
				$this,
				"yith_order_barcode"
			) );

			/**
			 * Add a shortcode for searching for a product barcode value
			 */
			add_shortcode( "yith_product_barcode", array(
				$this,
				"yith_product_barcode"
			) );

			/**
			 * Manage a search barcode value for products from the form generated by the shortcode
			 */
			add_filter( 'yith_barcode_action_product_search', array(
				$this,
				'yith_barcode_action_product_search'
			), 10, 2 );

			/**
			 * Manage a search barcode value for products from the form generated by the shortcode
			 */
			add_filter( 'yith_barcode_action_shop_order_complete_order', array(
				$this,
				'yith_barcode_action_shop_order_complete_order'
			), 10, 2 );

			/**
			 * Manage a search barcode value for products from the form generated by the shortcode
			 */
			add_filter( 'yith_barcode_action_shop_order_search', array(
				$this,
				'yith_barcode_action_shop_order_search'
			), 10, 2 );

			/**
			 * Manage request from the form generated by the shortcode
			 */
			add_action( 'wp_ajax_barcode_actions', array(
				$this,
				'manage_barcode_actions_callback'
			) );
		}

		public function manage_barcode_actions_callback() {

			if ( ! isset( $_POST["type"] ) || ! isset( $_POST["text"] ) || ! isset( $_POST["value"] ) ) {
				return;
			}
			$text   = sanitize_text_field( $_POST["text"] );
			$action = sanitize_text_field( $_POST["value"] );
			$type   = sanitize_text_field( $_POST["type"] );

			$result = array(
				'code'  => - 1,
				'value' => __( 'The selected action could not be performed', 'yith-woocommerce-barcodes' ),
			);


			$result = apply_filters( 'yith_barcode_action_' . $type . '_' . $action, $result, $text );
			wp_send_json( $result );
		}

		/**
		 * Manage the shortcode 'yith_render_barcode' for rendering a barcode by object id or with specific value
		 *
		 * @param array $atts the shortcode attributes
		 *
		 * @return string
		 */
		public function yith_render_barcode( $atts ) {
			$fields = shortcode_atts( array(
				'id'            => 0,
				'hide_if_empty' => 1,
				'value'         => '',
				'protocol'      => 'EAN8',
				'inline_css'    => '',
				'layout'        => ''
			), $atts );

			ob_start();

			//  if id>0 show the barcode for a specific object
			if ( $fields['id'] ) {
				YITH_YWBC()->show_barcode( $fields['id'], $fields['hide_if_empty'], $fields['inline_css'], $fields['layout'] );
			} elseif ( $fields['value'] ) {
				//  Show barcode with specific value and protocol
				$barcode  = new YITH_Barcode();
				$protocol = $fields['protocol'];
				$value    = $fields['value'];

				$barcode->generate( $protocol, $value );
				YITH_YWBC()->show_barcode( $barcode, $fields['hide_if_empty'], $fields['inline_css'], $fields['layout'] );
			}

			return ob_get_clean();
		}

		/**
		 * Manage the shortcode 'yith_order_barcode'
		 *
		 * @param array $atts the shortcode attributes
		 *
		 * @return string
		 */
		public function yith_order_barcode( $atts ) {

			$fields = shortcode_atts(
				array(
					'search_type' => 'shop_order',
					'capability'  => 'manage_woocommerce',
					'style'       => 'buttons',
					'actions'     => 'search, complete_order',
				),
				$atts );

			ob_start();
			wc_get_template( 'shortcode/ywbc-barcode-actions.php',
				array(
					'fields' => $fields,
				),
				YITH_YWBC_TEMPLATES_DIR,
				YITH_YWBC_TEMPLATES_DIR
			);
			$content = ob_get_clean();

			return $content;
		}

		/**
		 * Manage the shortcode 'yith_product_barcode'
		 *
		 * @param array $atts the shortcode attributes
		 *
		 * @return string
		 */
		public function yith_product_barcode( $atts ) {

			$fields = shortcode_atts(
				array(
					'search_type' => 'product',
					'capability'  => 'manage_woocommerce',
					'style'       => 'buttons',
					'actions'     => 'search',
				),
				$atts );

			ob_start();
			wc_get_template( 'shortcode/ywbc-barcode-actions.php',
				array(
					'fields' => $fields,
				),
				YITH_YWBC_TEMPLATES_DIR,
				YITH_YWBC_TEMPLATES_DIR
			);

			$content = ob_get_clean();

			return $content;
		}

		/**
		 * Manage a search for barcode value on products
		 *
		 * @param array  $result the result for the current action
		 * @param string $text   the text entered by the user
		 *
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function yith_barcode_action_product_search( $result, $text ) {
			$items = $this->barcode_action_search(
				$text,
				'product'
			);

			ob_start();
			wc_get_template( 'shortcode/ywbc-search-products.php',
				array(
					'posts' => $items,
				),
				YITH_YWBC_TEMPLATES_DIR,
				YITH_YWBC_TEMPLATES_DIR
			);

			$content = ob_get_clean();

			$result['code']  = 1;
			$result['value'] = $content;

			return $result;
		}

		/**
		 * Manage a search for barcode value on orders
		 *
		 * @param array  $result the result for the current action
		 * @param string $text   the text entered by the user
		 *
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function yith_barcode_action_shop_order_complete_order( $result, $text ) {
			$items = $this->barcode_action_search(
				$text,
				'shop_order'
			);

			$current_user = wp_get_current_user();
			if ( $current_user ) {
				$message = sprintf( __( 'Order status set by %s through YITH WooCommerce Barcodes', 'yith-woocommerce-barcodes' ), $current_user->display_name );
			} else {
				$message = __( 'Order status set through YITH WooCommerce Barcodes', 'yith-woocommerce-barcodes' );

			}
			foreach ( $items as $post ) {
				$order = wc_get_order( $post );
				if ( $order ) {
					$order->update_status( 'wc-completed', $message );
				}
			}

			ob_start();
			wc_get_template( 'shortcode/ywbc-complete-orders.php',
				array(
					'posts' => $items,
				),
				YITH_YWBC_TEMPLATES_DIR,
				YITH_YWBC_TEMPLATES_DIR
			);

			$content = ob_get_clean();


			$result['code']  = 1;
			$result['value'] = $content;

			return $result;
		}

		/**
		 * Perform a search for a barcode value on specific object type
		 *
		 * @param string $text
		 * @param string $type
		 *
		 * @return array
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function barcode_action_search( $text, $type = 'shop_order' ) {

			$args = array(
				'posts_per_page' => - 1,
				'post_type'      => $type,
				'post_status'    => 'any',
				'meta_query'     => array(
					array(
						'key'     => YITH_YWBC_META_KEY_BARCODE_DISPLAY_VALUE,
						'value'   => $text,
						'compare' => 'LIKE',
					)
				)
			);

			return get_posts( $args );

		}

		/**
		 * Manage a search for barcode value on orders
		 *
		 * @param array  $result the result for the current action
		 * @param string $text   the text entered by the user
		 *
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function yith_barcode_action_shop_order_search( $result, $text ) {
			$items = $this->barcode_action_search(
				$text,
				'shop_order'
			);

			ob_start();
			wc_get_template( 'shortcode/ywbc-search-orders.php',
				array(
					'posts' => $items,
				),
				YITH_YWBC_TEMPLATES_DIR,
				YITH_YWBC_TEMPLATES_DIR
			);

			$content = ob_get_clean();


			$result['code']  = 1;
			$result['value'] = $content;

			return $result;
		}
	}
}

YITH_YWBC_Shortcodes::get_instance();