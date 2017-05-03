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
 * @class      YITH_Tickets_Admin_Premium
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Francisco Mateo
 *
 */

if ( ! class_exists( 'YITH_Tickets_Admin_Premium' ) ) {
    /**
     * Class YITH_Tickets_Admin_Premium
     *
     * @author Francisco Mateo
     */
    class YITH_Tickets_Admin_Premium extends YITH_Tickets_Admin
    {

        /**
         * @var Panel page
         */
        protected $_panel_page = 'yith_wcevti_panel';

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

            /* === Register Panel Settings === */
            add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

            /* === Register plugin to licence/update system === */
            add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
            add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

            /* === My custom general fields === */
            add_action('woocommerce_product_options_pricing', array($this, 'add_reduce_ticket'));
            add_action('woocommerce_product_options_general_product_data', array( $this, 'add_increase_stock_section'), 10, 3);
            add_action('woocommerce_product_options_general_product_data', array( $this, 'add_increase_time_section'), 10, 3);

            /* === Redo product data tabs === */
            add_action('yith_wcevti_product_data_content', array($this, 'add_product_data_content_premium'));

            /* === Save my custom fields === */
            add_action('yith_wcevti_save_custom_fields', array($this, 'save_custom_fields_premium'));

            /* === Rewrite product options === */
            add_action( 'product_type_options', array($this, 'event_ticket_type_options'));

            /* === Register ajax actions for admin === */
            add_action( 'wp_ajax_load_calendar_events_action', array ($this, 'load_calendar_events_action'));
            add_action( 'wp_ajax_nopriv_load_calendar_events_action', array ($this, 'load_calendar_events_action'));

            add_action( 'wp_ajax_print_increase_stock_row_action', array ($this, 'print_increase_stock_row_action'));
            add_action( 'wp_ajax_print_increase_time_row_action', array ($this, 'print_increase_time_row_action'));

            add_action( 'wp_ajax_print_service_row_action', array ($this, 'print_service_row_action'));
            add_action( 'wp_ajax_print_select_service_row_action', array ($this, 'print_select_service_row_action'));

            add_action('yith_wcevti_order_metabox_end_fields', array ($this, 'set_order_metabox_services_template'), 10, 1);

            add_action('yith_wcevti_default_html_preview_end_fields', array($this, 'set_default_html_preview_services_template'), 10, 1);
            add_action('yith_wcevti_default_html_preview_end_fields', array($this, 'set_default_html_before_date_template'), 15, 1);


        }

        /**
         * Register plugins for activation tab
         *
         * @return void
         * @since 1.0.0
         */
        public function register_plugin_for_activation() {
            if( ! class_exists( 'YIT_Plugin_Licence' ) ){
                require_once 'plugin-fw/licence/lib/yit-licence.php';
                require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
            }

            YIT_Plugin_Licence()->register( YITH_WCEVTI_INIT, YITH_WCEVTI_SECRETKEY, YITH_WCEVTI_SLUG );
        }

        /**
         * Register plugins for update tab
         *
         * @return void
         * @since 1.0.0
         */
        public function register_plugin_for_updates() {
            if( ! class_exists( 'YIT_Plugin_Licence' ) ){
                require_once( YITH_WCEVTI_PATH . 'plugin-fw/lib/yit-upgrade.php' );
            }

            YIT_Upgrade()->register( YITH_WCEVTI_SLUG, YITH_WCEVTI_INIT );
        }

        /**
         * Add a panel under YITH Plugins tab
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         * @use     /Yit_Plugin_Panel class
         * @see      plugin-fw/lib/yit-plugin-panel.php
         */
        public function register_panel() {

            if ( ! empty( $this->_panel ) ) {
                return;
            }

            $menu_title = _x( 'Event Tickets', 'shortened plugin name', 'yith-event-tickets-for-woocommerce' );

            $admin_tabs = apply_filters( 'yith_wcevti_admin_tabs', array(
                    'settings'      => __( 'Settings', 'yith-event-tickets-for-woocommerce' ),
                )
            );

            $args = array(
                'create_menu_page' => true,
                'parent_slug'      => '',
                'page_title'       => $menu_title,
                'menu_title'       => $menu_title,
                'capability'       => 'manage_options',
                'parent'           => '',
                'parent_page'      => 'yit_plugin_panel',
                'page'             => $this->_panel_page,
                'admin-tabs'       => $admin_tabs,
                'options-path'     => YITH_WCEVTI_OPTIONS_PATH,
                'links'            => $this->get_sidebar_link()
            );


            /* === Fixed: not updated theme/old plugin framework  === */
            if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
                require_once( 'plugin-fw/lib/yit-plugin-panel-wc.php' );
            }

            $this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
        }

        /**
         * Custom reduce ticket fields
         *
         * Allow add reduce ticket price
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function add_reduce_ticket(){
            global $thepostid;
            $product = wc_get_product($thepostid);
            $reduce_ticket = yit_get_prop($product, '_reduce_ticket', true);

            $args = array(
                'reduce_ticket'  => $reduce_ticket
            );
            yith_wcevti_get_template('reduce_ticket', $args, 'admin');
        }

        /**
         * Custom increase stock section
         *
         * Add increase stock section on our Event Ticket product
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function add_increase_stock_section(){
            global $thepostid;
            $product = wc_get_product($thepostid);
            $increase_by_stock = yit_get_prop($product, '_increase_by_stock', true);
            $args = array(
                'increase_by_stock' => $increase_by_stock
            );
            yith_wcevti_get_template('increase_stock_section', $args, 'admin');
        }

        /**
         * Custom increase time section
         *
         *
         * Add increase time section on our Event Ticket product
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function add_increase_time_section(){
            global $thepostid;
            $product = wc_get_product($thepostid);
            $increase_by_time = yit_get_prop($product, '_increase_by_time', true );
            $args = array(
                'increase_by_time' => $increase_by_time
            );
            yith_wcevti_get_template( 'increase_time_section', $args, 'admin');
        }

        /**
         * Set data tabs for Event Tickets
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function build_product_data_tabs( $tabs ){

            $enable_location = get_option('yith_wcte_enable_location');

            array_push($tabs['inventory']['class'], 'show_if_ticket-event');

            $new_map_tab = array();

            if($enable_location == 'yes') {
                $new_map_tab = array(
                    'map' => array(
                        'label' => __('Map', 'yith-event-tickets-for-woocommerce'),
                        'target' => 'map_product_data',
                        'class' => array('hide_if_grouped', 'show_if_ticket-event')
                    )
                );
            }

            $new_fields_tab = array(
                'event_fields' => array(
                    'label' => __('Fields', 'yith-event-tickets-for-woocommerce'),
                    'target' => 'fields_product_data',
                    'class' => array('show_if_ticket-event')
                )
            );

            $services_tab = array(
                'services' =>  array(
                    'label' => __('Services', 'yith-event-tickets-for-woocommerce'),
                    'target' => 'services_product_data',
                    'class' => array('show_if_ticket-event')
                )
            );

            $organization_tab = array(
                'organization_template' => array(
                    'label' => __('Organizers and assistants' , 'yith-event-tickets-for-woocommerce' ),
                    'target' => 'organization_product_data',
                    'class' => array('show_if_ticket-event')
                )
            );

            $mail_template_tab = array(
                'mail_template' => array(
                    'label' => __('Email template' , 'yith-event-tickets-for-woocommerce' ),
                    'target' => 'mail_template_product_data',
                    'class' => array('show_if_ticket-event')
                )
            );

            return array_merge($tabs, $new_fields_tab, $services_tab, $new_map_tab, $organization_tab,$mail_template_tab);

        }

        /**
         * Set content for data tabs
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function add_product_data_content_premium(){
            global $thepostid;
            $args = array(
                'thepostid' => $thepostid
            );
            yith_wcevti_get_template('product_data_content_premium', $args, 'admin');
        }

        /**
         * Save custom fields Event Tickets
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function save_custom_fields_premium($post_id){

            $product = wc_get_product($post_id);

            //*** Save Reduce Type ***
            if (isset($_POST['_reduce_ticket']) && !empty($_POST['_reduce_ticket'])){
                $reduce_ticket = $_POST['_reduce_ticket'];
                //$changes['_reduce_ticket'] = $reduce_ticket;
                yit_save_prop( $product, '_reduce_ticket', $reduce_ticket);
            }

            //*** Save rules to increase price event by stock ***
            if(isset($_POST['_increase_by_stock']) && !empty($_POST['_increase_by_stock'])){
                $increase_by_stock_post = $_POST['_increase_by_stock'];
                $increase_by_stock = array();

                foreach($increase_by_stock_post as $increase){

                    if(!empty($increase['_threshold'])){
                        $increase_by_stock[] = $increase;
                    }
                }
                //$changes['_increase_by_stock'] = $increase_by_stock;
                yit_save_prop( $product, '_increase_by_stock', $increase_by_stock);
            } else {
                //$changes['_increase_by_stock'] = '';
                yit_save_prop( $product, '_increase_by_stock', '');
            }

            //*** Save rules to increase price event by time ***
            if(isset($_POST['_increase_by_time']) && !empty($_POST['_increase_by_time'])){
                $increase_by_time_post = $_POST['_increase_by_time'];
                $increase_by_time = array();

                foreach($increase_by_time_post as $increase){

                    if(!empty($increase['_threshold'])){
                        $increase_by_time[] = $increase;
                    }
                }
                //$changes['_increase_by_time'] = $increase_by_time;
                yit_save_prop( $product, '_increase_by_time', $increase_by_time);

            } else {
                //$changes['_increase_by_time'] = '';
                yit_save_prop( $product, '_increase_by_time', '');
            }

            //*** Save services ***
            if(isset($_POST['_services']) && !empty($_POST['_services'])){
                $services_post = $_POST['_services'];
                $services = array();
                foreach($services_post as $service_item){
                    $service_item['_item_overcharge'] = !empty($service_item['_item_overcharge']) ? $service_item['_item_overcharge'] : 0;

                    if(isset($service_item['_label'])){
                        switch ($service_item['_type']){
                            case 'select':
                                foreach ($service_item['_select'] as &$select){
                                    if(empty($select['_overcharge'])){
                                        $select['_overcharge'] = 0;
                                        $select['_label'] = sanitize_text_field($select['_label']);
                                    }
                                    //$changes['_service_'.$select['_label'].'_stock'] = $select['_stock'];

                                    yit_save_prop( $product,'_service_'.$select['_label'].'_stock', $select['_stock']);
                                }
                                array_push($services, $service_item);
                                break;
                            case 'checkbox':
                            default:
                                array_push($services, $service_item);
                                //$changes['_service_'. $service_item['_label'] .'_stock'] = $service_item['_stock'];

                                yit_save_prop( $product,'_service_'. $service_item['_label'] .'_stock', $service_item['_stock']);
                                break;
                        }
                    }
                }

                $current_service_stock = yith_wcevti_get_service_stocks($product->ID);
                yith_wcevti_clean_service_stock($product->ID, $services, $current_service_stock);

                //$changes['_services'] = $services;
                yit_save_prop( $product, '_services', $services);

            } else {
                //$changes['_services'] = '';
                yit_save_prop( $product, '_services', '');
            }

            //*** Save options for organizers fields ***//
            if(isset($_POST['_organization'])){

                $organization['tab_assistants'] = isset($_POST['_organization']['_tab_assistants']) ?  $_POST['_organization']['_tab_assistants'] : '';
                $organization['display'] = isset($_POST['_organization']['_display']) ?  $_POST['_organization']['_display'] : '';
                $organization['values'] = isset($_POST['_organization']['_values']) ?  $_POST['_organization']['_values'] : '';
                //$changes['_organization'] = $organization;
                yit_save_prop( $product, '_organization', $organization);
            }
	        //*** Save Latitude, Longitude and address Event location ***
            if(isset($_POST['_direction_event_field'])) {
                $direction_event = $_POST['_direction_event_field'];
                //$changes['_direction_event'] = esc_attr($direction_event);
                yit_save_prop( $product, '_direction_event', esc_attr($direction_event));

                $map_tab_display = isset($_POST['_map_tab_display']) ? $_POST['_map_tab_display'] : '';
                //$changes['_map_tab_display'] = esc_attr($map_tab_display);
                yit_save_prop( $product, '_map_tab_display', esc_attr($map_tab_display));
            }
	        $latitude_event = '';
	        $longitude_event = '';
	        if (isset($_POST['_direction_event_field'])){
		        if (!empty($_POST['_direction_event_field'])) {
			        $latitude_event = $_POST['_latitude_event_field'];
			        $longitude_event = $_POST['_longitude_event_field'];
		        }
	        }
	        //$changes['_latitude_event'] = esc_attr( $latitude_event );
	        yit_save_prop( $product, '_latitude_event', esc_attr( $latitude_event ));
            //$changes['_longitude_event'] = esc_attr( $longitude_event );
            yit_save_prop( $product, '_longitude_event', esc_attr( $longitude_event ));

            //return $changes;
        }

        /**
         * Set the main options for product type Ticket Event
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function event_ticket_type_options ($options){
            $options['virtual']['wrapper_class'] = $options['virtual']['wrapper_class'] . ' show_if_ticket-event';
            $options['downloadable']['wrapper_class'] = $options['downloadable']['wrapper_class'] . ' show_if_ticket-event';

            return $options;
        }

        /**
         * Ajax call, send event data to json format
         *
         * @return void
         * @since 1.0.0
         */
        public function load_calendar_events_action(){

            $jsonData = yith_wcevti_get_dates();

            wp_send_json( $jsonData );
            die();
        }

        /**
         * Ajax call, add Increase by stock row
         *
         * @return void
         * @since 1.0.0
         */
        public function print_increase_stock_row_action(){

            if(isset($_POST['index'])){
                $args = array(
                    'index' => $_POST['index']
                );
                yith_wcevti_get_template('increase_stock_row', $args, 'admin');
            }
            die();
        }

        /**
         * Ajax call, add Increase by time row
         *
         * @return void
         * @since 1.0.0
         */
        public function print_increase_time_row_action(){

            if(isset($_POST['index'])){
                $args = array(
                    'index' => $_POST['index']
                );
                yith_wcevti_get_template('increase_time_row', $args, 'admin');
            }
            die();
        }

        /**
         * Ajax call, add service row
         *
         * @return void
         * @since 1.0.0
         */
        public function print_service_row_action(){
            if(isset($_POST['index'])){
                $service = array(
                    '_type' => ''
                );
                $args = array(
                    'index' => $_POST['index'],
                    'service' => $service
                );
                yith_wcevti_get_template('service_row', $args, 'admin');
            }
            die();
        }

        /**
         * Ajax call, add select service row
         *
         * @return void
         * @since 1.0.0
         */
        public function print_select_service_row_action(){
            if (isset($_POST['index'])){
                $args = array(
                    'row_index' => $_POST['row_index'],
                    'index'     => $_POST['index'],
                    'service_label' => $_POST['service_label']
                );
                yith_wcevti_get_template('select_service_row.php', $args, 'admin');
            }
            die();
        }

        /**
         * Add services on order metabox.
         *
         * @return void
         * @since 1.0.0
         */
        public function set_order_metabox_services_template($post){

            $args = array(
                'services' => yith_wcevti_get_services($post)
            );
            yith_wcevti_get_template('ticket_order_meta_box_services', $args, 'admin');
        }

        /**
         * Add services on live preview template...
         *
         * @return void
         * @since 1.0.0
         */
        public function set_default_html_preview_services_template($post){

            $args = array(
                'services' => yith_wcevti_get_services($post)
            );
            yith_wcevti_get_template('default-html-preview-services', $args, 'tickets');
        }

        public function set_default_html_before_date_template($post){
            $args = yith_wcevti_set_args_mail_template($post);
            $args = array(
                'location' => $args['location']
            );
            yith_wcevti_get_template('default-html-preview-before-date', $args, 'tickets');
        }
    }
}