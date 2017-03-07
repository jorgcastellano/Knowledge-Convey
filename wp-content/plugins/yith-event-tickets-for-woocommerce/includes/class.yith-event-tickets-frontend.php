<?php
/*
 * This file belongs to the YIT Framework.
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
 * @class      YITH_Tickets_Frontend
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Francisco Mateo
 *
 */

if ( ! class_exists( 'YITH_Tickets_Frontend' ) ) {
    /**
     * Class YITH_Tickets_Frontend
     *
     * @author Francisco Mateo
     */
    class YITH_Tickets_Frontend {

        /**
         * Construct
         *
         * @author Francisco Mateo
         * @since 1.0
         */

        protected static $instance = null;

        public function __construct(){
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'load_fields_event'));
            add_action( 'woocommerce_ticket-event_add_to_cart', array( $this, 'event_add_to_cart') );
            add_action( 'woocommerce_add_to_cart_handler_ticket-event', array( $this, 'add_event_to_cart' ), 10, 1 );

            add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data'), 10, 2 );
            add_filter( 'woocommerce_add_cart_item', array( $this, 'set_cart_item'), 10, 1);

            add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'update_cart_item_session'), 10, 1);

            add_filter ( 'woocommerce_cart_item_quantity' , array( $this, 'set_quantity_on_sold_individually'), 10, 3);

            add_action( 'wp_ajax_load_fields_event_action', array ( $this, 'load_fields_event_action'));

            add_action( 'wp_ajax_nopriv_load_fields_event_action', array ( $this, 'load_fields_event_action'));

            //Validation actions...
            add_action ('yith_wcevti_passed_require_fields', array( $this, 'check_validation_fields'), 10, 2);

            //Order actions...
            add_action ('woocommerce_add_order_item_meta', array( $this, 'add_order_item_meta'), 10, 3);

            add_action('woocommerce_checkout_order_processed', array( $this, 'add_order_ticket'), 10, 1 );

            add_action('woocommerce_order_items_meta_display', array( $this, 'add_order_item_meta_display'), 10, 2);

            add_action('woocommerce_order_item_meta_start', array( $this, 'add_dates_on_order_item'), 10, 3);
            add_action('woocommerce_order_item_meta_end', array( $this, 'add_view_pdf_and_gcalendar_button'), 10, 3);
        }

        /**
         * Enqueue Scripts
         *
         * Register and enqueue scripts for Frontend
         *
         * @author Francisco Mateo
         * @since 1.0
         * @return void
         */
        public function enqueue_scripts(){
            global $post;

            $api_key = get_option('yith_wcte_api_key_gmaps');

            //Register external maps script...
            wp_register_script('yith-wc-script-gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places', array(), YITH_WCEVTI_VERSION, true);

            // Register frontend style
            wp_register_style( 'yith-wc-style-frontend-fontawesome-tickets', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', null, YITH_WCEVTI_VERSION);

            // Register frontend style
            wp_register_style( 'yith-wc-style-frontend-event-tickets', YITH_WCEVTI_ASSETS_URL . 'css/style-frontend.css', null, YITH_WCEVTI_VERSION);

            do_action('yith_wcevti_register_scripts');

            if(is_product()){ //Only load script if we are on single product.
                $product =  wc_get_product(get_the_id());;
                if($product->is_type('ticket-event')) {

                    $path = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '/unminified' : '';
                    $prefix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';

                    // Register the script (our handler, our src, our dependencies, our version)
                    wp_register_script('yith-wcevti-script-cookie', YITH_WCEVTI_ASSETS_URL . 'js-cookie/src/js.cookie.js', array(), YITH_WCEVTI_VERSION);
                    wp_register_script('yith-wcevti-script-tickets-frontend', YITH_WCEVTI_ASSETS_URL . '/js' . $path . '/script-tickets-frontend' . $prefix . '.js', array('jquery', 'yith-wcevti-script-cookie'), YITH_WCEVTI_VERSION);



                    // Create array will be printed depends our language

                    $data_to_js = array(
                        'product' => array(
                            'id' => $post->ID
                        ),
                        'labels' => array(
                            'start_date' => __('Start date', 'yith-event-tickets-for-woocommerce'),
                            'end_date' => __('End date', 'yith-event-tickets-for-woocommerce')
                        ),
                        'messages' => array(
                            'complete_field_service' => __('Complete', 'yith-event-tickets-for-woocommerce'),
                            'incomplete_field_service' => __('Need to fill one or more of the above fields', 'yith-event-tickets-for-woocommerce'),
                            'complete_required_item' => __('Complete', 'yith-event-tickets-for-woocommerce'),
                            'incomplete_required_item' => __('Incomplete', 'yith-event-tickets-for-woocommerce'),
                            'wrong_mail_field'=> __('Incorrect email format', 'yith-event-tickets-for-woocommerce'),
                            'wrong_number_field'=> __('Incorrect number format', 'yith-event-tickets-for-woocommerce'),
                            'ask_for_delete_ticket' => __('Are you sure you want to delete', 'yith-event-tickets-for-woocommerce'),
                            'tickets' => __('tickets', 'yith-event-tickets-for-woocommerce'),
                            'ticket' => __('ticket', 'yith-event-tickets-for-woocommerce')
                        )
                    );
                    $data_to_js = apply_filters('yith_wcevti_data_to_js_custom', $data_to_js);

                    //add translation array on our handler (our handler, name variable will contain the data on our javascript, array translation)
                    wp_localize_script('yith-wcevti-script-tickets-frontend', 'yith_wcevti_tickets', $data_to_js);

                    /*
                    * Localize script to bind with our admin-ajax.php
                    *
                    * wp_localize_script('our tag script', 'our name object', 'array to bind files or variables')
                    * See file assets/js/script-tickets-frontend.js to look how to load call ajax.
                    * If we use shortcode [event_calendar] that will ve enqueue on class.yith-event-tickets-shortcodes.php::event_calendar()
                    */
                    wp_localize_script('yith-wcevti-script-tickets-frontend', 'event_tickets_frontend', array(
                        'ajaxurl' => admin_url('admin-ajax.php'),
                    ));


                    wp_enqueue_script('jquery-ui-accordion');
                    wp_enqueue_script('yith-wcevti-script-tickets-frontend');


                    //Enqueue style if is product
                    wp_enqueue_style('yith-wc-style-frontend-fontawesome-tickets');
                    wp_enqueue_style('yith-wc-style-frontend-event-tickets');
                }

            }
        }

        public function load_fields_event(){
            global $product;
            if($product->is_type('ticket-event')) {

                $date_message = yith_wecvti_get_date_message($product->id);

                yith_wcevti_get_template('date_panel', $date_message, 'frontend');

                $args = array(
                    'price' => $product->get_price(),
                    'event_title' => $product->post->post_title
                );

                yith_wcevti_get_template('fields_panel', $args, 'frontend');
            }
        }

        public function load_fields_event_action(){

            // If is ajax call ask load num rows field .
            if (isset($_POST['num_rows']) && $_POST['num_rows'] > 0) {

                $num_rows = $_POST['num_rows'];
                $current_index = $_POST['current_index'];
                $product = wc_get_product($_POST['product_id']);

                //TODO for future improves see minimum and maximun quantities plugin YITH, check its compatible.
                $current_stock = get_post_meta($_POST['product_id'], '_stock', true);
                $num_tickets = $num_rows + $current_index;

                $fields = get_post_meta($_POST['product_id'], '_fields', true);

                $reduce_ticket = get_post_meta($_POST['product_id'], '_reduce_ticket', true);
                $price  = $product->get_price();

                $event_title = $_POST['event_title'];

                $args = array(
                    'product_id' => $_POST['product_id'],
                    'num_rows' => $num_rows,
                    'row' => $current_index,
                    'fields' => $fields,
                    'event_title' => $event_title,
                    'reduce_ticket' => $reduce_ticket,
                    'price' => $price
                );

                yith_wcevti_get_template('fields_services_row', $args, 'frontend');

                die();

                // If we load the page only load one row.
            } else {

                global $product;
                $fields = get_post_meta($product->id, '_fields', true);
                $event_title = $product->post->post_title;
                $reduce_ticket = get_post_meta($product->id, '_reduce_ticket', true);
                $price  = $product->get_price();

                $args = array(
                    'product_id' => $product->id,
                    'num_rows' => 1,
                    'row' => 0,
                    'fields' => $fields,
                    'event_title' => $event_title,
                    'reduce_ticket' => $reduce_ticket,
                    'price' => $price
                );
                    yith_wcevti_get_template('fields_services_row', $args, 'frontend');

            }
        }

        public function event_add_to_cart(){
            //TODO For the moment...
            //Im not use yith_wcevti_get_template because simple.php template its WC template...

            wc_get_template( 'single-product/add-to-cart/simple.php');


        }

        public function add_event_to_cart($url = false){

            $product_id  = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
            $quantity = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( $_REQUEST['quantity'] );

            $fields_customer = !empty( $_REQUEST['_fields_customer'] ) ? $_REQUEST['_fields_customer'] : array();

            $passed_validation = apply_filters ('yith_wcevti_passed_require_fields', true, $product_id);

            $passed_validation = apply_filters ('yith_wcevti_passed_custom', $passed_validation, $product_id, $quantity);

            if($passed_validation){
                for ($i = 0; $i < $quantity; $i++) {
                    $product_data['_field_service'] = array(
                        '_index' => $i,
                        '_fields' => (isset($fields_customer[$i])) ? $fields_customer[$i] : array()
                    );
                    $product_data = apply_filters('yith_wcevti_before_add_to_cart', $product_data, $product_id);
                    if (WC()->cart->add_to_cart($product_id, 1, 0, array(), $product_data)) {
                        wc_add_to_cart_message(array($product_id => $i), false);
                    }
                }
            }
            if (wc_notice_count( 'error' ) === 0 ) {
                // If has custom URL redirect there
                if ( $url = apply_filters( 'woocommerce_add_to_cart_redirect', $url ) ) {
                    wp_safe_redirect( $url );
                    exit;
                } elseif ( get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes' ) {
                    wp_safe_redirect( wc_get_cart_url() );
                    exit;
                }
            }

        }

        public function get_item_data($item_data, $cart_item){

            if(isset($cart_item['_field_service'])) {
                if (is_array($cart_item['_field_service']['_fields'])) {
                    $fields_data = get_post_meta($cart_item['product_id'], '_fields', true);
                    foreach ($cart_item['_field_service']['_fields'] as $field) {
                        if (!empty($field['_value'])) {
                            foreach ($fields_data as $field_data) {
                                if (sanitize_title($field_data['_label']) == $field['_key']) {
                                    $item_data[] = array(
                                        'key' => $field_data['_label'],
                                        'value' => $field['_value']
                                    );
                                }
                            }
                        }
                    }
                }

                $item_data = apply_filters('yith_wcevti_get_item_data', $item_data, $cart_item);
            }
            return $item_data;
        }

        public function set_cart_item($cart_item_data){
            if(isset($cart_item_data['_field_service'])){
                $cart_item_data['data']->sold_individually = 'no';

                if(is_array($cart_item_data['_field_service']['_fields'])){
                    foreach ($cart_item_data['_field_service']['_fields'] as $field){
                        if(!empty($field['_value'])){
                            $cart_item_data['data']->sold_individually = 'yes';
                        }
                    }
                }
                $cart_item_data = apply_filters('yith_wcevti_set_cart_item', $cart_item_data);
            }

            return $cart_item_data;
        }

        function set_quantity_on_sold_individually($product_quantity, $cart_item_key, $cart_item){
            $_product = $cart_item['data'];
            if('yes'  == $_product->sold_individually & 'ticket-event' == $_product->product_type){
                $product_quantity = sprintf( $cart_item['quantity']. ' <input type="hidden" name="cart[%s][qty]" value="'. $cart_item['quantity'] .'" />', $cart_item_key );
            } else {
                $product_quantity = woocommerce_quantity_input( array(
                    'input_name'  => "cart[{$cart_item_key}][qty]",
                    'input_value' => $cart_item['quantity'],
                    'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                    'min_value'   => '0'
                ), $_product, false );
            }

            return $product_quantity;
        }

        public function update_cart_item_session ($session_data){

            $session_data = self::set_cart_item($session_data);
            return $session_data;
        }

        public function check_validation_fields($passed_required_fields,$product_id){

            $fields = get_post_meta( $product_id, '_fields', true);
            if(isset($_POST['_fields_customer'])){
                $fields_customer = $_POST['_fields_customer'];
                foreach ($fields_customer as $index_panel => $field_panel){
                    foreach ($field_panel as $index_item => $field_item){
                        $passed_required_fields = $this->passed_item_field($field_item['_key'], $field_item['_value'] ,$fields) ? true : $passed_required_fields;
                    }
                }
            }
            return $passed_required_fields;
        }

        public function add_order_item_meta($item_id, $values, $cart_item_key){

            if('ticket-event' == $values['data']->product_type) {
                wc_add_order_item_meta($item_id, '_product_type', $values['data']->product_type);

                if (is_array($values['_field_service']['_fields'])) {
                    foreach ($values['_field_service']['_fields'] as $i => $field) {
                        if(!empty($field['_value'])){
                            wc_add_order_item_meta($item_id, '_field_' . $field['_label'], $field['_value']);
                        }
                    }
                }
                do_action ('yith_wcevti_add_order_item_meta', $item_id, $values);
            }
        }

        public function if_field_exist($key, $fields){
            $exist = false;

            foreach ($fields as $field){
                $label = sanitize_title($field['_label']);
                if ($key == $label){
                    $exist = true;
                }
            }

            return $exist;
        }

        public function passed_item_field($key, $value, $fields){
            $passed = true;

            foreach ($fields as $field){
                $label = sanitize_title($field['_label']);

                if ($key == $label){
                    if(isset($field['_required'])){
                        switch ($field['_type']){
                            case 'text':
                                if( 0 == strlen($value)){
                                    $passed = false;
                                    wc_add_notice(sprintf( __('The required text field "%s" is empty.', 'yith-event-tickets-for-woocommerce'),
                                        $field['_label'] ), 'error');
                                }
                                break;
                            case 'textarea':
                                if( 0 == strlen($value)){
                                    $passed = false;
                                    wc_add_notice(sprintf( __('The required textarea field "%s" is empty.',
                                        'yith-event-tickets-for-woocommerce'), $field['_label'] ), 'error');
                                }
                                break;
                            case 'email':
                                if( 0 == strlen($value)){
                                    $passed = false;
                                    wc_add_notice(sprintf( __('The required email field "%s" is empty.',
                                        'yith-event-tickets-for-woocommerce'), $field['_label'] ), 'error');


                                } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)){
                                    $passed = false;
                                    wc_add_notice(sprintf( __('The format of the email address entered for "%s" is not correct.',
                                        'yith-event-tickets-for-woocommerce'), $field['_label'] ), 'error');

                                }
                                break;
                            case 'number':
                                if( 0 == strlen($value)){
                                    $passed = false;
                                    wc_add_notice(sprintf( __('The required field "%s" is empty.',
                                        'yith-event-tickets-for-woocommerce'), $field['_label'] ), 'error');
                                } elseif (!filter_var($value, FILTER_VALIDATE_INT)){
                                    $passed = false;
                                    wc_add_notice(sprintf( __('The required field "%s" must be a number.', 'yith-event-tickets-for-woocommerce'),
                                        $field['_label'] ), 'error');
                                }
                                break;
                        }
                    }

                }
            }
            return $passed;
        }

        /**
         * Once ordered is processing we create tickets post with all information.
         * @param $order_id
         */
        public function add_order_ticket ($order_id){
            $order = wc_get_order($order_id);
            $order_items = $order->get_items();

            foreach ($order_items as $order_item_id => $order_item){

                if(isset($order_item['product_type']) ) {

                    if ('ticket-event' == $order_item['product_type']) {
                        $event_order = $order_item;

                        $product = wc_get_product($event_order['product_id']);
                        $event_post = array(
                            'post_author' => $order->post->post_author,
                            'post_date' => $order->post->post_date,
                            'post_date_gmt' => $order->post->post_date_gmt,
                            'post_title' => $product->get_title(),
                            'post_status' => 'publish',
                            'post_type' => 'ticket',
                            'post_name' => $order->post->post_name,
                            'post_modified' => $order->post->post_modified,
                            'post_modified_gmt' => $order->post->post_modified_gmt,
                            'guid' => $order->post->guid,
                            'filter' => $order->post->filter
                        );

                        $post_id = wp_insert_post($event_post);

                        update_post_meta($post_id, 'wc_event_id', $product->get_id());
                        update_post_meta($post_id, 'wc_total', $order_item['line_total']);
                        update_post_meta($post_id, 'wc_order_id', $order_id);
                        update_post_meta($post_id, 'wc_order_item_id', $order_item_id);

                        foreach ($event_order as $key => $event_item) {
                            if (preg_match('/field_/i', $key)) {
                                update_post_meta($post_id, $key, $event_item);
                            }
                            do_action('yith_add_order_custom_item', $post_id, $key, $event_item);
                        }

                        wc_add_order_item_meta($order_item_id, '_event_id', $post_id);

                        yith_wcevti_create_pdf($post_id);
                    }
                }
            }
        }

        public function add_order_item_meta_display($output, $order_item_meta){

            foreach ($order_item_meta->meta as $key => $item) {

                if (preg_match('/field_/i', $key)) {

                    $label = str_replace( '_field_', '', $key );

                    $my_output = '<dl class="variation"><dt class="variation-product_type">'.$label.':</dt><dd class="variation-product_type">'.$item[0].'</dd></dl>';

                    $output = $output . $my_output;
                }

                $output = apply_filters('yith_wcevti_add_order_item_meta_display_output', $output, $key, $item);
            }

            return $output;
        }

        public function add_dates_on_order_item ($item_id, $item, $order){

            $date_message = yith_wecvti_get_date_message($item['product_id']);

            yith_wcevti_get_template('date_panel', $date_message, 'frontend');
        }

        public function add_view_pdf_and_gcalendar_button ($item_id, $item, $order){
            if('wc-completed' == $order->post_status | 'wc-processing' == $order->post_status){

                $wc_event_id = get_post_meta($item['event_id'], 'wc_event_id', true);
                $url_calendar = yith_wcevti_get_google_calendar_link($wc_event_id);

                $args = array(
                    'event_id' => $item['event_id'],
                    'url_google_calendar' => $url_calendar,
                    'wc_event_id' => $wc_event_id
                );

                wp_enqueue_style('yith-wc-style-frontend-fontawesome-tickets');
                wp_enqueue_style('yith-wc-style-frontend-address-tickets');
                yith_wcevti_get_template('view_pdf_and_gcalendar_button', $args, 'frontend');

            }
        }

        /*** Returns single instance of the class
         *
         * @return instance
         * @since 1.0.0
         */
        public static function get_instance(){
            if( is_null( self::$instance ) ){
                self::$instance = new self();
            }

            return self::$instance;
        }
    }
}

/**
 * Unique access to instance of YITH_Tickets_Frontend class
 *
 * @return \YITH_Tickets_Frontend
 * @since 1.0.0
 */
function YITH_Tickets_Frontend(){
    return YITH_Tickets_Frontend::get_instance();
}