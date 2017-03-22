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
 * @class      YITH_Tickets_Frontend_Premium
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Francisco Mateo
 *
 */

if ( ! class_exists( 'YITH_Tickets_Frontend_Premium' ) ) {
    /**
     * Class YITH_Tickets_Frontend_Premium
     *
     * @author Francisco Mateo
     */
    class YITH_Tickets_Frontend_Premium extends YITH_Tickets_Frontend
    {
        /**
         * Construct
         *
         * @author Francisco Mateo
         * @since 1.0
         */

        protected static $instance = null;

        public function __construct()
        {
            parent::__construct();

            add_action ('yith_wcevti_register_scripts', array($this, 'register_scripts'));

            add_filter ('yith_wcevti_data_to_js_custom', array($this, 'set_data_to_js'), 10, 1);

            add_action ('yith_wcevti_end_fields_row', array($this, 'add_services_row'), 10, 2);

            add_action ('wp_ajax_nopriv_load_calendar_events_action', array ( $this, 'load_calendar_events_action'));

            add_filter ('woocommerce_product_tabs', array( $this, 'add_custom_tab'), 98);

            add_filter ('yith_wcevti_passed_custom', array($this, 'check_services'), 10, 3);

            add_action ('yith_wcevti_passed_check_service', array( $this, 'check_service_repeated'), 10, 3);

            add_filter ('yith_wcevti_before_add_to_cart', array($this, 'add_services_to_cart_item'), 10, 2);

            add_filter ('yith_wcevti_get_item_data', array($this, 'get_item_services'), 10, 2);

            add_filter ('yith_wcevti_set_cart_item', array($this, 'set_cart_item_services'), 10, 1);

            add_action ('yith_wcevti_add_order_item_meta', array($this, 'add_order_item_meta_services'), 10, 2);

            add_action ('woocommerce_after_checkout_validation', array( $this, 'after_checkout_validation'), 10, 1 );

            add_action ('yith_add_order_custom_item', array($this, 'add_order_service_item'), 10, 3);

            add_filter ('yith_wcevti_add_order_item_meta_display_output', array($this, 'add_order_item_meta_display_services'), 10, 3);

            add_action('yith_wcevti_default_html_end_fields', array($this, 'set_default_html_services_template'), 10, 1);
            add_action('yith_wcevti_default_html_end_fields', array($this, 'set_default_html_before_date'), 15, 1);

        }

        public function register_scripts(){

            $path = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '/unminified' : '';
            $prefix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';

            // Register shortcode scripts
            wp_register_script( 'yith-wcevti-script-frontend-shortcodes-tickets', YITH_WCEVTI_ASSETS_URL . '/js' . $path . '/script-tickets-shortcodes' . $prefix . '.js', array('jquery'), YITH_WCEVTI_VERSION);


            // Register shortcode style
            wp_register_style( 'yith-wcevti-style-frontend-shortcodes-tickets', YITH_WCEVTI_ASSETS_URL . '/css/script-tickets-shortcodes.css', null, YITH_WCEVTI_VERSION);


            wp_localize_script( 'yith-wcevti-script-frontend-shortcodes-tickets', 'event_tickets_shortcodes', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            ));

            // Register external monthly script...
            wp_register_script ('yith-wcevti-script-frontend-calendar-tickets', YITH_WCEVTI_ASSETS_URL. 'monthly-master/js/monthly.js', array('jquery'), YITH_WCEVTI_VERSION, true);

            // Register external monthly style
            wp_register_style( 'yith-wcevti-style-frontend-calendar-tickets', YITH_WCEVTI_ASSETS_URL . 'monthly-master/css/monthly.css', null, YITH_WCEVTI_VERSION);
        }

        public function set_data_to_js($data_to_js){

            $data_to_js['messages']['incomplete_field_service'] = __('Need to fill one or more of the above fields or services!', 'yith-event-tickets-for-woocommerce');

            return $data_to_js;
        }

        public function add_services_row($product_id, $row){
            $product = wc_get_product($product_id);
            $services = yit_get_prop( $product, '_services', true);
            $reduce_ticket = yit_get_prop( $product, '_reduce_ticket', true);
            $price = yit_get_prop($product, 'price');
            $args = array(
                'product_id' => $product_id,
                'services' => $services,
                'reduce_ticket' => $reduce_ticket,
                'price' => $price,
                'row' => $row
            );

            yith_wcevti_get_template('services_row', $args, 'frontend');
        }

        /**
         * Ajax call, send event data to json format
         *
         * @return void
         * @since 1.0.0
         */
        public function load_calendar_events_action(){

            $jsonData = array(
                'monthly' => array(
                    array(
                        'id'=> 1,
                        'name'=> 'This is a JSON event',
                        'startdate'=> '2016-7-15',
                        'enddate'=> '2016-7-18',
                        'starttime'=> '12=>00',
                        'endtime'=> '2=>00',
                        'color'=> '#FFB128',
                        'url'=> ''
                    ),
                    array(
                        'id'=> 2,
                        'name'=> 'This is a JSON event',
                        'startdate'=> '2016-7-20',
                        'enddate'=> '2016-7-25',
                        'starttime'=> '12=>00',
                        'endtime'=> '2=>00',
                        'color'=> '#EF44EF',
                        'url'=> ''
                    )
                )
            );

            wp_send_json( $jsonData );
        }

        public function add_custom_tab($tabs){
            global $product;
            $organization = yit_get_prop($product, '_organization', true);

            if(isset($organization['tab_assistants'])){
                if('on' == $organization['tab_assistants']){
                    $tabs['assistants'] = array(
                        'title' 	=> __( 'Assistants', 'yith-event-tickets-for-woocommerce' ),
                        'priority' 	=> 50,
                        'callback' 	=> array($this, 'load_assistants_tab')
                    );
                }
            }

	        $direction = yit_get_prop($product, '_direction_event', true);
	        if(!empty($direction)){
		        if('on' == yit_get_prop($product, '_map_tab_display', true)){
			        $tabs['location'] = array(
				        'title' 	=> __( 'Location of the event', 'yith-event-tickets-for-woocommerce' ),
				        'priority' 	=> 60,
				        'callback' 	=> array($this, 'load_location_tab')
			        );
		        }
	        }

            return $tabs;
        }

        public function load_assistants_tab(){
            global $product;
            $organization = yit_get_prop($product, '_organization', true);
            yith_wcevti_get_template('tab_assistants', array('organization' => $organization), 'frontend');
        }

        public function load_location_tab(){
            yith_wcevti_get_template('tab_location', array(), 'frontend');
        }

        public function check_services($passed_validation, $product_id, $quantity){
            if($passed_validation){
                $required_validation = $this->check_required_services($product_id);
                if($required_validation){
                $passed_validation = $this->check_stock_repeat_services($passed_validation, $product_id, $quantity);
                } else {
                    $passed_validation = false;
                }
            }
            return $passed_validation;
        }

        public function check_required_services($product_id){
            $passed_required_services = true;
            $product = wc_get_product($product_id);
            $services = yit_get_prop( $product, '_services', true);
            if(isset($_POST['_services_customer'])) {
                $services_customer = $_POST['_services_customer'];
                foreach ($services_customer as $index_panel => $service_panel){
                    foreach ($service_panel as $index_item => $service_item){
                        $passed_required_services = !$this->passed_item_service($service_item ,$services) ? false : $passed_required_services;
                    }
                }
                $passed_required_services = !yith_wcevti_before_cart_service_validation($product_id, $services_customer) ? false : $passed_required_services;
            }
            return $passed_required_services;
        }

        public function passed_item_service($service_user, $services_data){
            $exist_select = true;
            foreach ($services_data as $service_data) {
                if ($service_user['_key'] == $service_data['_key']) {
                    if (isset($service_data['_required']) & 'select' == $service_data['_type']) {
                        if(0 < strlen($service_user['_label'])){
                            $label = sanitize_title($service_user['_label']);
                            foreach ($service_data['_select'] as $select_data){
                                $label_data = sanitize_title($select_data['_label']);
                                if($label_data == $label){
                                    if(!empty($select_data['_range_from']) & !empty($select_data['_range_to'])){
                                        $value = $service_user['_value'][$label];
                                        if(0 < strlen($value)){
                                            if($value < $select_data['_range_from'] | $value > $select_data['_range_to']){
                                                $exist_select = false;
                                                wc_add_notice( sprintf( __('Select number: the selected value for %s service is out of range.', 'yith-event-tickets-for-woocommerce'), $service_user['_label'] ), 'error');
                                            }
                                        } else {
                                            $exist_select = false;
                                            wc_add_notice( sprintf( __('Select number: the selected value for %s service has not been selected. It\'s mandatory field.',
                                                'yith-event-tickets-for-woocommerce'), $service_user['_label'] ), 'error');
                                        }
                                    }
                                }
                            }
                        } else {
                            $exist_select = false;
                            wc_add_notice(__('Select service required: it\'s not selected.', 'yith-event-tickets-for-woocommerce'), 'error');
                        }
                        if (!$exist_select){
                            $exist_select = false;
                            wc_add_notice(__('Select service: the selected value does not exist.', 'yith-event-tickets-for-woocommerce'),
                                'error');
                        }
                    }
                }
            }
            return $exist_select;
        }

        public function check_stock_repeat_services($passed_validation, $product_id, $quantity){
            if($passed_validation){
                $services_customer = !empty( $_REQUEST['_services_customer'] ) ? $_REQUEST['_services_customer'] : array();
                $count_product_service = yith_wcevti_count_services_product($product_id, $services_customer, $quantity);
                $check_count_product_service = '';
                foreach ($count_product_service as $product_service){
                    if (!empty($product_service)) {
                        $check_count_product_service = yith_wcevti_check_current_stock_service($product_id, key($product_service), $product_service[key($product_service)], $check_count_product_service);
                        if ('out_range' == $check_count_product_service) {
                            $passed_validation = false;
                            wc_add_notice(__('You are trying to buy more services than the available. ', 'yith-event-tickets-for-woocommerce') . key
                                ($product_service) . __(' it\'s out of range.', 'yith-event-tickets-for-woocommerce'), 'error');
                        }
                    }
                }
                if('out_range' != $check_count_product_service) {
                    for ($i = 0; $i < $quantity; $i++) {
                        if (isset($services_customer[$i])) {
                            $service_customer = $services_customer[$i];
                            $service_validation = true;
                            foreach ($service_customer as $service) {
                                $passed_check_service = apply_filters('yith_wcevti_passed_check_service', $product_id, $service, $services_customer);
                                $label = sanitize_title($service['_label']);
                                if (isset($service['_value'][$label])) {
                                    switch ($passed_check_service) {
                                        case 'repeated':
                                            $service_validation = false;
                                            wc_add_notice($service['_label'] . ' x ' . $service['_value'][$label] . __(' has been already selected.',
                                                    'yith-event-tickets-for-woocommerce'), 'notice');
                                            break;
                                        case 'repeated_cart':
                                            $service_validation = false;
                                            wc_add_notice($service['_label'] . ' x ' . $service['_value'][$label] . __(' is already in your cart.',
                                                    'yith-event-tickets-for-woocommerce'), 'notice');
                                            break;
                                        case 'out_stock':
                                            $service_validation = false;
                                            wc_add_notice($service['_label'] . __(' is out of stock', 'yith-event-tickets-for-woocommerce'), 'notice');
                                            break;
                                        case 'sold':
                                            $service_validation = false;
                                            wc_add_notice($service['_label'] . ' x ' . $service['_value'][$label] . __(' has been bought by another user.', 'yith-event-tickets-for-woocommerce'), 'notice');
                                            break;
                                    }
                                }
                            }
                            $passed_validation = !$service_validation ? false : $passed_validation;
                        }
                    }
                }
            }

            return $passed_validation;
        }

        public function check_service_repeated($post_id ,$service, $services){
            $check='';
            //Check if service is repeated...
            $count = 0;
            foreach ($services as $i_service){
                foreach ($i_service as $item){
                    if('select' == $item['_type'] && $service['_label'] == $item['_label']){
                        $label = sanitize_title($service['_label']);
                        if(isset($item['_value'][$label]) && $service['_value'][$label] == $item['_value'][$label] ){
                            $count++;
                        }
                    }
                }
            }

            if(1 < $count){
                $check = 'repeated';
            } elseif (yith_wcevti_exist_service_cart($post_id, $service)) {
                $check = 'repeated_cart';
            }

            return $check;
        }

        public function add_services_to_cart_item ($product_data, $product_id){
            $services_customer = !empty( $_REQUEST['_services_customer'] ) ? $_REQUEST['_services_customer'] : array();
            $reduced_price = !empty($_REQUEST['_reduced_price']) ? $_REQUEST['_reduced_price'] : array();
            $index = $product_data['_field_service']['_index'];

            $services = isset( $services_customer[$index] ) ? $services_customer[$index] : array();

            $service_price = isset( $services_customer[$index] ) ? $this->get_services_price($product_id, $services_customer[$index]) : 0;

            $price_reduced = isset( $reduced_price[$index]['_value']) ? $price_reduced = $reduced_price[$index]['_value'] : '';

            $product_data['_field_service']['_services'] = $services;
            $product_data['_field_service']['_service_price'] = $service_price;
            $product_data['_field_service']['_price_reduced'] = $price_reduced;

            return $product_data;
        }

        public function get_services_price($product_id, $services_user){
            $price = 0;
            $product = wc_get_product($product_id);
            $services_data_ticket = yit_get_prop($product, '_services');

            if(isset($services_user)){
                foreach ($services_user as $service){
                    foreach ($services_data_ticket as $service_data){
                        if($service_data['_key'] == $service['_key'] ){
                            switch ($service_data['_type']){
                                case 'checkbox':
                                    if(isset($service['_value'])){
                                        $price += $service_data['_item_overcharge'];
                                    }
                                    break;
                                case 'select':
                                    foreach ($service_data['_select'] as $select){
                                        if($select['_label'] == $service['_label']){
                                            $price += $select['_overcharge'];
                                        }
                                    }
                                    break;
                            }
                        }

                    }
                }
            }
            return $price;
        }

        public function get_item_services($item_data, $cart_item){

            if (isset($cart_item['_field_service']['_services']) && is_array($cart_item['_field_service']['_services'])) {
                $product = wc_get_product($cart_item['product_id']);
                $services_data_ticket = yit_get_prop($product, '_services');
                foreach ($cart_item['_field_service']['_services'] as $service) {
                    foreach ($services_data_ticket as $service_data) {
                        if ($service_data['_key'] == $service['_key']) {
                            switch ($service_data['_type']) {
                                case 'checkbox':
                                    if (isset($service['_value'])) {
                                        $item_data[] = array(
                                            'key' => $service_data['_label'],
                                            'value' => __('Yes', 'yith-event-tickets-for-woocommerce')
                                        );
                                    }
                                    break;
                                case 'select':
                                    foreach ($service_data['_select'] as $select) {
                                        if ($select['_label'] == $service['_label']) {
                                            if (!empty($service['_value'][sanitize_title($service['_label'])])) {
                                                $item_data[] = array(
                                                    'key' => $select['_label'],
                                                    'value' => $service['_value'][sanitize_title($service['_label'])]
                                                );
                                            } else {
                                                $item_data[] = array(
                                                    'key' => $select['_label'],
                                                    'value' => __('Ok', 'yith-event-tickets-for-woocommerce')
                                                );
                                            }

                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
            }

            if (isset($cart_item['_field_service']['_price_reduced'])){
                if ('on' == $cart_item['_field_service']['_price_reduced']) {
                    $product = wc_get_product($cart_item['product_id']);
                    $reduced_price = yit_get_prop( $product, '_reduce_ticket');
                    $item_data[] = array(
                        'key' => __('Reduced price activated', 'yith-event-tickets-for-woocommerce'),

                        'value' => isset($reduced_price[0]['_description']) ? $reduced_price[0]['_description'] : empty($reduced_price[0]['_description']) ? __('Yes', 'yith-event-tickets-for-woocommerce'): __('No', 'yith-event-tickets-for-woocommerce')
                    );
                }
            }

            return $item_data;
        }

        public function set_cart_item_services($cart_item_data){

            $price = $cart_item_data['data']->get_price() + $cart_item_data['_field_service']['_service_price'];
            yit_set_prop($cart_item_data['data'], 'price', $price);

            if('on' == $cart_item_data['_field_service']['_price_reduced']){
                $reduced_price = yith_wecvti_get_reduced_price(yit_get_product_id($cart_item_data['data']));
                yit_set_prop($cart_item_data['data'], 'price', $cart_item_data['data']->get_price() - $reduced_price);
            }

            if(is_array($cart_item_data['_field_service']['_services'])) {
                foreach ($cart_item_data['_field_service']['_services'] as $service) {
                    if (!empty($service['_label'])) {
                        yit_set_prop($cart_item_data['data'], 'sold_individually', 'yes');
                    }
                }
            }
            return $cart_item_data;
        }

        public function add_order_item_meta_services($item_id, $values){
            if (is_array($values['_field_service']['_services'])) {
                foreach ($values['_field_service']['_services'] as $i => $service) {
                    if (!empty($service['_label'])) {
                        switch ($service['_type']){

                            case 'checkbox':
                                if (isset($service['_value'])) {
                                    wc_add_order_item_meta($item_id, '_service_' . $service['_label'], __('Yes', 'yith-event-tickets-for-woocommerce'));
                                }
                                break;
                            case 'select':
                                if (!empty($service['_value'][sanitize_title($service['_label'])])) {
                                    wc_add_order_item_meta($item_id, '_service_' . $service['_label'], $service['_value'][sanitize_title($service['_label'])]);
                                } else {
                                    wc_add_order_item_meta($item_id, '_service_' . $service['_label'], __('Ok', 'yith-event-tickets-for-woocommerce'));
                                }
                                break;
                        }
                    }
                }
            }
        }

        public function after_checkout_validation(){
            $cart = WC()->cart;
            if(yith_wcevti_checkout_service_validation()) {
                foreach ($cart->cart_contents as $cart_item) {
                    if (isset($cart_item['_field_service']) && isset($cart_item['_field_service']['_services']) & is_array($cart_item['_field_service']['_services'])) {
                        foreach ($cart_item['_field_service']['_services'] as $service) {
                            $quantity_service = isset($count_services[$cart_item['product_id']][$service['_label']]['_value']) ?$quantity_service = $count_services[$cart_item['product_id']][$service['_label']]['_value']: 1;
                            $service['_quantity'] = $quantity_service;
                            yith_wcevti_add_service_sold($cart_item['product_id'], $service);
                        }
                    }
                }
            }
        }

        public function add_order_service_item($post_id, $key, $event_item){
            if (preg_match('/service_/i', $key)) {
                update_post_meta($post_id, $key, $event_item);
            }
        }

        public function add_order_item_meta_display_services($meta_items, $key, $item){

            if (preg_match('/service_/i', $key)) {

                $meta_items[] = array(
                    'label' => str_replace( '_service_', '', $item->key ),
                    'value' => $item->value);
            }

            return $meta_items;
        }

        /**
         * Add services on pdf template...
         *
         * @return void
         * @since 1.0.0
         */
        public function set_default_html_services_template($post){

            $args = array(
                'services' => yith_wcevti_get_services($post)
            );

            yith_wcevti_get_template('default-html-services', $args, 'tickets');

        }

        public function set_default_html_before_date($post){
            $args = yith_wcevti_set_args_mail_template($post);
            $args = array(
                'location' => $args['location']
            );
            yith_wcevti_get_template('default-html-before-date', $args, 'tickets');
        }
    }


}