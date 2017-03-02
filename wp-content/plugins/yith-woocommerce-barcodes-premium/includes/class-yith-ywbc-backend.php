<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'YITH_YWBC_Backend' ) ) {

	/**
	 *
	 * @class   YITH_YWBC_Backend
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_YWBC_Backend {

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
			$this->init_hooks();
		}


		/**
		 * Initialize all hooks used by the plugin affecting the back-end behaviour
		 */
		public function init_hooks() {
			/**
			 * Enqueue scripts and styles for admin pages
			 */
			add_action( 'admin_enqueue_scripts', array(
				$this,
				'enqueue_scripts'
			) );

			/**
			 * Enqueue scripts and styles for admin pages
			 */
			add_action( 'admin_enqueue_scripts', array(
				$this,
				'enqueue_style'
			) );

			/**
			 * Create the barcode for orders when they are created
			 */
			add_action( 'woocommerce_checkout_order_processed', array(
				$this,
				'on_new_order'
			) );

			/**
			 * Create the barcode for products when they are created
			 */
			add_action( 'transition_post_status', array(
				$this,
				'on_new_product'
			), 10, 3 );

			/**
			 * Add the search by barcode value on back-end product list
			 */
			add_filter( 'posts_join', array(
				$this,
				'query_barcode_join'
			), 10, 2 );

			/**
			 * Add the search by barcode value on back-end product list
			 */
			add_filter( 'posts_where', array(
				$this,
				'query_barcode_where'
			), 10, 2 );

			/**
			 * Add the barcode fields for the order search
			 */
			add_filter( 'woocommerce_shop_order_search_fields', array(
				$this,
				'search_by_order_fields'
			), 10, 2 );

			/**
			 * Manage a request from product bulk actions
			 */
			add_action( 'load-edit.php', array(
				$this,
				'generate_bulk_action'
			) );

			/**
			 * Show the order barcode on emails
			 */
			if ( YITH_YWBC()->show_on_email_all || YITH_YWBC()->show_on_email_completed ) {
				add_action( 'woocommerce_email_footer', array(
					$this,
					'show_on_emails'
				) );
			}

			/**
			 * Add a metabox showing the barcode for the order
			 */
			add_action( 'add_meta_boxes', array(
				$this,
				'add_barcode_metabox'
			) );

			/**
			 * Manage a request for generating the barcode for an object
			 */
			add_action( "admin_action_generate_barcode", array(
				$this,
				'admin_action_generate_barcode'
			) );


			/**
			 * If a manual value is entered on the product barcode fields, use it as the current barcode value
			 */
			add_action( 'save_post_product', array(
				$this,
				'save_manual_barcode'
			), 10, 3 );

			add_action( 'wp_ajax_apply_barcode_to_products', array(
				$this,
				'apply_barcode_to_products'
			) );

			if ( YITH_YWBC()->show_product_barcode_on_email ) {

				add_action( 'woocommerce_email_header', array(
					$this,
					'enable_product_barcode_in_email'
				), 5 );

				add_action( 'woocommerce_email_footer', array(
					$this,
					'disable_product_barcode_in_email'
				) );
			}

			/**
			 * Add barcode to variations
			 */
			add_action( 'woocommerce_product_after_variable_attributes', array(
				$this,
				'woocommerce_product_after_variable_attributes'
			), 10, 3 );

			add_action( 'wp_ajax_create_barcode', array(
				$this,
				'create_barcode_callback'
			) );

		}

		public function create_barcode_callback() {

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}


			$result = '';

			if ( isset( $_POST["id"] ) &&
			     isset( $_POST["type"] )
			) {

				$obj_id = $_POST["id"];
				$type   = $_POST["type"];
				$this->create_barcode( $obj_id, $type );

				ob_start();
				$post = get_post( $obj_id );
				$this->show_barcode_generation_section( $post );

				$result = ob_get_clean();
			}
			wp_send_json( $result );
		}

		/**
		 * Let's create the barcode for a specific id and object type
		 *
		 * @param int    $id   the object id
		 * @param string $type the object type
		 */
		public function create_barcode( $id, $type = 'shop_order' ) {
			if ( 'shop_order' == $type ) {
				$this->create_order_barcode( $id );
			} elseif ( ( 'product' == $type ) || ( 'product_variation' == $type ) ) {
				$this->create_product_barcode( $id );
			}
		}

		/**
		 * Generate the order barcode for a specific order id
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function admin_action_generate_barcode() {
			$obj_id = $_GET["id"];
			$type   = $_GET["type"];

			$this->create_barcode( $obj_id, $type );

			wp_redirect( esc_url_raw( admin_url( 'post.php?post=' . $obj_id ) . '&action=edit' ) );
		}

		/**
		 * @param $loop
		 * @param $variation_data
		 * @param $variation
		 */
		public function woocommerce_product_after_variable_attributes( $loop, $variation_data, $variation ) {
			/* @var WP_Post $variation */
			$this->show_barcode_generation_section( $variation );
		}

		public function enable_product_barcode_in_email() {
			add_action( 'woocommerce_order_item_meta_start', array(
				$this,
				'show_product_barcode_in_order_email'
			), 10, 3 );
		}

		public function show_product_barcode_in_order_email( $item_id, $item, $order ) {

			$product_id = $item["product_id"];
			echo '<br>' . do_shortcode( '[yith_render_barcode id="' . $product_id . '"]' );
		}

		public function disable_product_barcode_in_email() {

			remove_action( 'woocommerce_order_item_meta_start', array(
				$this,
				'show_product_barcode_in_order_email'
			), 10 );
		}


		/**
		 * If a manual value is entered on the product barcode fields, use it as the current barcode value
		 */
		public function save_manual_barcode( $post_ID, $post, $update ) {
			if ( isset( $_POST['ywbc-manual-value'] ) && ! empty( $_POST['ywbc-manual-value'] ) ) {
				//  save the custom value
				$barcode  = YITH_Barcode::get( $post_ID );
				$protocol = YITH_YWBC()->products_protocol;

				$value = $_POST['ywbc-manual-value'];

				$image_path = $this->get_server_file_path( $post_ID, $protocol, $value );

				$barcode->generate( $protocol, $value, $image_path );
				$barcode->save();
			}
		}

		public function enqueue_style( $hook ) {
			/**
			 * Add styles
			 */
			$screen_id = get_current_screen()->id;
			if ( ( 'product' == $screen_id ) ||
			     ( 'shop_order' == $screen_id )
			) {
				wp_enqueue_style( 'ywbc-style',
					YITH_YWBC_ASSETS_URL . '/css/ywbc-style.css',
					array(),
					YITH_YWBC_VERSION );
			}
		}

		/**
		 * Enqueue scripts and styles for the back-end
		 *
		 * @param string $hook
		 *
		 */
		public function enqueue_scripts( $hook ) {

			$screen_id = get_current_screen()->id;

			if ( 'edit-product' == $screen_id ) {
				wp_register_script( "ywbc-bulk-actions",
					YITH_YWBC_SCRIPTS_URL . yit_load_js_file( 'ywbc-bulk-actions.js' ),
					array(
						'jquery',
					),
					YITH_YWBC_VERSION,
					true );

				wp_localize_script( 'ywbc-bulk-actions',
					'ywbc_bk_data',
					array(
						'action_options' => '<option value="ywbc-generate">' . __( 'Generate barcode', 'yith-woocommerce-barcodes' ) . '</option>',
					) );

				wp_enqueue_script( "ywbc-bulk-actions" );
			}

			if ( ( isset( $_GET['page'] ) && 'yith_woocommerce_barcodes_panel' == $_GET['page'] ) && ( isset( $_GET['tab'] ) && 'tool' == $_GET['tab'] ) ) {

				wp_register_script( 'ywbc-ajax-apply-barcode', YITH_YWBC_SCRIPTS_URL . yit_load_js_file( 'ywbc-ajax-apply-barcode.js' ), array(
					'jquery',
					'jquery-ui-progressbar'
				), YITH_YWBC_VERSION, true );

				$ywbc_params = array(
					'ajax_url' => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
					'messages' => array(
						'complete_task' => __( 'Barcode applied successfully', 'yith-woocommerce-barcode' ),
						'error_task'    => __( 'It is not possible to complete the task', 'yith-woocommerce-barcode' )
					)
				);

				wp_localize_script( 'ywbc-ajax-apply-barcode', 'ywbc_params', $ywbc_params );
				wp_enqueue_script( 'ywbc-ajax-apply-barcode' );
			}

			wp_register_script( "ywbc-backend",
				YITH_YWBC_SCRIPTS_URL . yit_load_js_file( 'ywbc-backend.js' ),
				array(
					'jquery',
				),
				YITH_YWBC_VERSION,
				true );

			wp_localize_script( 'ywbc-backend',
				'ywbc_data',
				array(
					'loader'   => apply_filters( 'yith_ywbc_loader', YITH_YWBC_ASSETS_URL . '/images/loading.gif' ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				) );

			wp_enqueue_script( "ywbc-backend" );
		}


		/**
		 * Create barcode for new orders if needed
		 *
		 * @param int $order_id
		 */
		public function on_new_order( $order_id ) {

			//  Check if barcode are enabled for orders
			if ( ! YITH_YWBC()->enable_on_orders ) {
				return;
			}

			//  Check if barcode should be create automatically
			if ( ! YITH_YWBC()->create_on_orders ) {
				return;
			}

			$this->create_order_barcode( $order_id );
		}

		/**
		 * Create the barcode values for the order
		 *
		 * @param int    $order_id
		 * @param string $protocol
		 * @param string $value
		 */
		public function create_order_barcode( $order_id, $protocol = '', $value = '' ) {
			$protocol  = $protocol ? $protocol : YITH_YWBC()->orders_protocol;
			$the_value = $value ? $value : $order_id;
			$the_value = apply_filters( 'yith_barcode_new_order_value', $the_value, $order_id, $protocol, $value );

			$this->generate_barcode_image( $order_id, $protocol, $the_value );
		}

		/**
		 * Retrieve the filename to be used for the barcode file
		 *
		 * @param YITH_Barcode $barcode  the barcode instance whose file should be generated
		 * @param string       $protocol the Barcode protocol to use
		 * @param string       $value    the value to use as barcode text
		 *
		 * @return string
		 */
		public function get_server_file_path( $obj_id, $protocol, $value ) {

			$image_filename = apply_filters( 'yith_ywbc_image_filename', sprintf( "Image%s_%s_%s.gif", $obj_id, $protocol, $value ), $obj_id, $protocol, $value );

			return YITH_YWBC_UPLOAD_DIR . '/' . $image_filename;
		}

		/**
		 * Retrieve the filename to be used for the barcode file
		 *
		 * @param YITH_Barcode $barcode  the barcode instance whose file should be generated
		 * @param string       $protocol the Barcode protocol to use
		 * @param string       $value    the value to use as barcode text
		 *
		 * @return string
		 */
		public function get_public_file_path( $barcode ) {

			return str_replace( YITH_YWBC_UPLOAD_DIR, YITH_YWBC_UPLOAD_URL, $barcode->image_filename );
		}

		/**
		 * Generate a new barcode instance
		 *
		 * @param int    $object_id the id of the object(WC_Product or WC_Order) associated to this barcode
		 * @param string $protocol  the protocol to use
		 * @param string $value     the value to use as the barcode value
		 *
		 * @return YITH_Barcode
		 */
		public function generate_barcode_image( $object_id, $protocol, $value ) {
			$barcode = new YITH_Barcode( $object_id );

			$image_path = $this->get_server_file_path( $object_id, $protocol, $value );
			$res        = $barcode->generate( $protocol, $value, $image_path );
			//todo save with creating a file
			$barcode->save();


			return $barcode;

			/*$image_filename = sprintf( "%s_%s.gif", $barcode->get_protocol(), $barcode->get_display_value() );
			$image_path     = YITH_YWBC_UPLOAD_DIR . "/" . $image_filename;

			if ( imagegif( $res['img'], $image_path ) ) {
				imagedestroy( $res['img'] );
			}

			$barcode->save( $image_filename );*/
		}

		/**
		 * Create barcode for new products if needed
		 *
		 * @param string  $new_status
		 * @param string  $old_status
		 * @param WP_Post $post
		 */
		public function on_new_product( $new_status, $old_status, $post ) {

			//  Check if barcode are enabled for products
			if ( ! YITH_YWBC()->enable_on_products ) {
				return;
			}

			//  Check if barcode should be create automatically
			if ( ! YITH_YWBC()->create_on_products ) {
				return;
			}

			//  Work only on published posts
			if ( 'new' !== $old_status ) {
				return;
			}

			$this->create_product_barcode( $post->ID );
		}

		/**
		 * Create the barcode values for the order
		 *
		 * @param int    $product_id
		 * @param string $protocol
		 * @param string $value
		 *
		 * @return YITH_Barcode
		 */
		public function create_product_barcode( $product_id, $protocol = '', $value = '' ) {

			$protocol  = $protocol ? $protocol : YITH_YWBC()->products_protocol;
			$the_value = $value ? $value : $product_id;
			$the_value = apply_filters( 'yith_barcode_new_product_value', $the_value, $product_id, $protocol, $value );

			return $this->generate_barcode_image( $product_id, $protocol, $the_value );
		}

		/**
		 * Manage a request from product bulk actions
		 */
		public function generate_bulk_action() {

			global $typenow;
			$post_type = $typenow;
			$sendback  = admin_url( "edit.php?post_type=$post_type" );

			// 1. get the action
			$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
			$action        = $wp_list_table->current_action();

			if ( $action == 'ywbc-generate' ) {
				$post_ids = $_GET['post'];
				check_admin_referer( 'bulk-posts' );

				foreach ( $post_ids as $post_id ) {
					$this->create_product_barcode( $post_id );
				}

				// build the redirect url
				//$sendback = add_query_arg( array( 'done' => $done, 'ids' => join( ',', $post_ids ) ), $sendback );

				wp_redirect( $sendback );

				exit();
			}

			// 4. Redirect client

		}

		/**
		 * Set the join part of the query used for filtering products
		 *
		 * @param string   $join
		 * @param WP_Query $par2
		 *
		 * @return string
		 */
		public function query_barcode_join( $join, $par2 ) {

			if ( empty( $_GET["s"] ) ) {
				return $join;
			}

			//  check for necessary arguments
			if ( ! isset( $par2 ) || ! isset( $par2->query["post_type"] ) ) {
				return $join;
			}

			//  Do something only for products and orders
			if ( ( "product" != $par2->query["post_type"] ) &&
			     ( "shop_order" != $par2->query["post_type"] )
			) {
				return $join;
			}

			global $wpdb;

			$join .= sprintf( " LEFT JOIN {$wpdb->postmeta} ps_meta ON {$wpdb->posts}.ID = ps_meta.post_id and ps_meta.meta_key = '_ywbc_barcode_display_value'" );

			return $join;
		}

		public function search_by_order_fields( $fields ) {
			$fields[] = YITH_YWBC_META_KEY_BARCODE_DISPLAY_VALUE;

			return $fields;
		}

		/**
		 * Set the where part of the query used for filtering products
		 *
		 * @param string   $where
		 * @param WP_Query $par2
		 *
		 * @return string
		 */
		public function query_barcode_where( $where, $par2 ) {

			if ( empty( $_GET["s"] ) ) {
				return $where;
			}

			//  check for necessary arguments
			if ( ! isset( $par2 ) || ! isset( $par2->query["post_type"] ) ) {
				return $where;
			}

			//  Do something only for products and orders
			if ( ( "product" != $par2->query["post_type"] ) &&
			     ( "shop_order" != $par2->query["post_type"] )
			) {
				return $where;
			}

			$where .= sprintf( " or (ps_meta.meta_value like '%%%s%%') ", $_GET["s"] );

			return $where;
		}

		/**
		 * show the order barcode on emails
		 *
		 * @param WC_Email $email
		 */
		public function show_on_emails( $email ) {
			//  Check if the barcode should be shown...
			if ( ! YITH_YWBC()->show_on_email_completed && ! YITH_YWBC()->show_on_email_all ) {
				return;
			}

			if ( ! isset( $email ) ) {
				return;
			}

			//  Only for email related to an order...
			if ( ! $email->object instanceof WC_Order ) {
				return;
			}

			//  Check if only on completed order should be shown the barcode and
			//  this is not the case
			if ( YITH_YWBC()->show_on_email_completed && ( 'customer_completed_order' != $email->id ) ) {
				return;
			}

			//  Display the barcode...

			$order = $email->object;
			ob_start();

			include( YITH_YWBC_ASSETS_DIR . '/css/ywbc-style.css' );
			$css = ob_get_clean();

			YITH_YWBC()->show_barcode( $order->id, true, $css );
		}


		/**
		 * Show the order metabox
		 */
		function add_barcode_metabox() {
			if ( YITH_YWBC()->enable_on_orders ) {
				//  Add metabox on order page
				add_meta_box( 'ywbc_barcode',
					__( 'YITH Barcodes', 'yith-woocommerce-barcodes' ), array(
						$this,
						'show_barcode_generation_section',
					), 'shop_order', 'side', 'high' );
			}

			if ( YITH_YWBC()->enable_on_products ) {
				//  Add metabox on order page
				add_meta_box( 'ywbc_barcode',
					__( 'YITH Barcodes', 'yith-woocommerce-barcodes' ), array(
						$this,
						'show_barcode_generation_section',
					), 'product', 'side', 'high' );
			}
		}

		/**
		 * Display the barcode metabox
		 *
		 * @param WP_Post $post
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function show_barcode_generation_section( $post ) {

			if ( ( "shop_order" == $post->post_type ) ||
			     ( "product" == $post->post_type ) ||
			     ( "product_variation" == $post->post_type )
			) {
				?>
				<div class="ywbc-barcode-generation">
					<?php
					YITH_YWBC()->show_barcode( $post->ID );

					$this->show_generate_barcode_button( $post->post_type, $post->ID );
					$this->show_manual_barcode_generation( $post );
					?>
				</div>
				<?php
			}
		}

		/**
		 * Show the fields for entering custom product barcode value
		 *
		 * @param $post
		 */
		public function show_manual_barcode_generation( $post ) {
			if ( "product" != $post->post_type ) {
				return;
			}

			if ( ! YITH_YWBC()->manual_value_on_products ) {
				return;
			}

			?>
			<div class="ywbc-manual-barcode">
				<label for="ywbc-manual-value"><?php _e( 'Enter a value', 'yith-woocommerce-barcodes' ); ?></label>
				<input type="text" name="ywbc-manual-value" id="ywbc-manual-value">
			</div>
			<?php
		}

		/**
		 * Show a button that let the admin to generate a new barcode for the order
		 *
		 * @param string $type the type of object for which the action generate is intended for
		 * @param int    $obj_id
		 */
		public function show_generate_barcode_button( $type = 'shop_order', $obj_id ) {
			?>
			<div class="ywbc-generate-barcode">
				<a class="page-title-action ywbc-generate"
				   href="<?php echo admin_url( "admin.php?action=generate_barcode&type=" . $type . "&id=" . $obj_id ); ?>"
				   data-id="<?php echo $obj_id; ?>" data-type="<?php echo $type; ?>"><?php _e( 'Create now', 'yith-woocommerce-barcodes' ); ?></a>
			</div>
			<?php

		}


		/**
		 * apply barcode to product
		 * @since 1.0.2
		 */
		public function apply_barcode_to_products() {

			$item_id = isset( $_POST['ywbc_item_id'] ) ? $_POST['ywbc_item_id'] : false;
			$result  = 'error_on_create_barcode';
			if ( $item_id ) {
				/**
				 * @var YITH_Barcode $barcode
				 */
				$barcode = $this->create_product_barcode( $item_id );

				if ( $barcode->object_id ) {

					$result = 'barcode_created';
				}
			}
			wp_send_json( array( 'result' => $result ) );
		}
	}
}

YITH_YWBC_Backend::get_instance();