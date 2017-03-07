<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCEVTI_PATH' ) ) {
    exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Tickets_Admin
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Francsico Mateo
 *
 */

if ( ! class_exists( 'YITH_Tickets_Admin' ) ) {
    /**
     * Class YITH_Tickets_Admin
     *
     * @author Francsico Mateo
     */
    class YITH_Tickets_Admin{

        /**
         * @var Panel page
         */
        protected $_panel_page = 'yith_wcte_panel';

        /**
         * @var doc_url
         */
        protected $doc_url = 'http://docs.yithemes.com/yith-event-tickets-for-woocommerce';

        /**
         * @var string Official plugin documentation
         */
        protected $_official_documentation = 'http://docs.yithemes.com/yith-event-tickets-for-woocommerce' ;

        /**
         * @var $_premium string Premium tab template file name
         */
        protected $_premium = 'premium.php';

        /**
         * @var string Premium version landing link
         */
        protected $_premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-event-tickets';

        /**
         * Construct
         *
         * @author Francsico Mateo
         * @since 1.0
         */
        public function __construct(){

            /* === Action links and meta === */
            add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta' ), 10, 2 );

            /* === My custom general fields === */
            add_action('woocommerce_product_options_general_product_data', array( $this, 'add_date_fields'), 10, 3 );

            /* === Save my custom fields === */
            add_action('woocommerce_process_product_meta_ticket-event', array($this, 'save_custom_fields'), 10, 3);

            /* === Add my custom scripts === */
            add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts' ));

            /* === Register ajax actions for admin === */
            add_action( 'wp_ajax_print_field_row_action', array ($this, 'print_field_row_action'));

            add_action('wp_ajax_print_mail_template_action', array($this, 'print_mail_template_action'));

            /* === Redo product data tabs === */
            add_filter( 'woocommerce_product_data_tabs', array($this, 'build_product_data_tabs'));
            add_action( 'woocommerce_product_data_panels', array($this, 'build_product_data_content'));

            //Meta-boxes
            add_action( 'add_meta_boxes', array($this, 'add_meta_boxes' ), 10 );

            //Tickets table
            add_filter( 'manage_ticket_posts_columns', array($this, 'add_columns_ticket'), 10, 1);
            add_action( 'manage_ticket_posts_custom_column', array($this, 'render_ticket_custom_columns'), 10, 2 );
            add_action( 'pre_get_posts', array($this, 'search_ticket_for_place'));

            //Register panel
            add_action( 'admin_menu', array( $this, 'register_panel' ), 5) ;
            add_action( 'yith_wcevti_premium', array( $this, 'premium_tab' ) );

        }



        /**
         * Sidebar links
         *
         * @return   array The links
         * @since    1.2.1
         * @author Francsico Mateo
         */
        public function get_sidebar_link(){
            $links =  array(
                array(
                    'title' => __( 'Plugin documentation', 'yith-event-tickets-for-woocommerce' ),
                    'url'   => $this->_official_documentation,
                ),
                array(
                    'title' => __( 'Help Center', 'yith-event-tickets-for-woocommerce' ),
                    'url'   => 'http://support.yithemes.com/hc/en-us/categories/202568518-Plugins',
                ),
            );

            return $links;
        }


        /**
         * Add a panel under YITH Plugins tab
         *
         * @return   void
         * @since    1.0
         * @author Francsico Mateo
         * @use     /Yit_Plugin_Panel class
         * @see      plugin-fw/lib/yit-plugin-panel.php
         */
        public function register_panel() {

            if ( ! empty( $this->_panel ) ) {
                return;
            }

            $admin_tabs = array(
                'premium' => __( 'Premium Version', 'yith-event-tickets-for-woocommerce' )
            );


            $args = array(
                'create_menu_page' => true,
                'parent_slug'      => '',
                'page_title'       => __( 'Event Tickets', 'yith-event-tickets-for-woocommerce' ),
                'menu_title'       => __( 'Event Tickets', 'yith-event-tickets-for-woocommerce' ),
                'capability'       => 'manage_options',
                'parent'           => '',
                'parent_page'      => 'yit_plugin_panel',
                'page'             => $this->_panel_page,
                'admin-tabs'       => apply_filters( 'yith-wcevti-admin-tabs', $admin_tabs ),
                'options-path'     => YITH_WCEVTI_PATH . '/plugin-options'
            );

            /* === Fixed: not updated theme  === */
            if( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
                require_once( YITH_WACP_DIR . '/plugin-fw/lib/yit-plugin-panel-wc.php' );
            }

            $this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
        }

        /**
         * Premium Tab Template
         *
         * Load the premium tab template on admin page
         *
         * @return   void
         * @since    1.0
         * @author Francsico Mateo
         * @return void
         */
        public function premium_tab() {
            $premium_tab_template = YITH_WCEVTI_PATH . 'templates/admin/' . $this->_premium;
            if( file_exists( $premium_tab_template ) ) {
                include_once($premium_tab_template);
            }

        }

        /**
         * Get the premium landing uri
         *
         * @since   1.0.0
         * @author  @author Francsico Mateo
         * @return  string The premium landing link
         */
        public function get_premium_landing_uri() {
            return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing.'?refer_id=1030585';
        }

        /**
         * Custom date start and end fields
         *
         * Add custom dates field on our Event Ticket product
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function add_date_fields(){
            global $thepostid;
            $args = array(
                'thepostid' => $thepostid
            );
            wc_get_template('admin/date_fields.php', $args, '', YITH_WCEVTI_TEMPLATE_PATH);
        }

        /**
         * Save Custom fields
         *
         * Save custom field variations on our Event Ticket product
         *
         * @author Francsico Mateo
         * @since 1.0
         * @param $post_id
         */
        public function save_custom_fields( $post_id ){

            //*** Save Start and End Event Data ***
            $start_date_picker_field = $_POST['_start_date_picker_field'];

            if( ! empty( $start_date_picker_field ) ) {
                update_post_meta( $post_id, '_start_date_picker', esc_attr( $start_date_picker_field ) );
            }

            $start_time_picker_field = $_POST['_start_time_picker_field'];

            if(!empty($start_time_picker_field)){
                update_post_meta( $post_id, '_start_time_picker', esc_attr( $start_time_picker_field ) );
            }

            $end_date_picker_field = $_POST['_end_date_picker_field' ];

            if( ! empty( $end_date_picker_field ) ) {
                update_post_meta( $post_id, '_end_date_picker', esc_attr( $end_date_picker_field) );
            }

            $end_time_picker_field = $_POST['_end_time_picker_field'];

            if(!empty($end_time_picker_field)){
                update_post_meta( $post_id, '_end_time_picker', esc_attr( $end_time_picker_field ) );

            }

            //*** Save Latitude, Longitude and address Event location ***
            if(isset($_POST['_direction_event_field'])){
                $direction_event = $_POST['_direction_event_field'];
                update_post_meta($post_id, '_direction_event', esc_attr( $direction_event ));
            }

            if(isset($_POST['_map_tab_display'])) {
                $map_tab_display = $_POST['_map_tab_display'] ? $_POST['_map_tab_display'] : '';
                update_post_meta($post_id, '_map_tab_display', esc_attr($map_tab_display));
            }

            if(isset($_POST['_latitude_event_field']) && !empty($_POST['_latitude_event_field'])){
                if(isset($_POST['_direction_event_field']) & empty($_POST['_direction_event_field'])){
                    $direction_event = '';
                } else {
                    $direction_event = $_POST['_latitude_event_field'];
                }
                update_post_meta($post_id, '_latitude_event', esc_attr( $direction_event ));
            }

            if(isset($_POST['_longitude_event_field']) && !empty($_POST['_longitude_event_field'])){
                if(isset($_POST['_direction_event_field']) & empty($_POST['_direction_event_field'])) {
                    $direction_event = '';
                } else {
                    $direction_event = $_POST['_longitude_event_field'];
                }
                update_post_meta($post_id, '_longitude_event', esc_attr( $direction_event ));
            }

            //*** Save fields ***
            if(isset($_POST['_fields']) && !empty($_POST['_fields'])){
                $fields_post = $_POST['_fields'];
                $fields = array();

                foreach($fields_post as $field_item){
                    if(!empty($field_item['_label'])){
                        $fields[] = $field_item;
                    }
                }
                update_post_meta($post_id, '_fields', $fields);

            } else {
                update_post_meta($post_id, '_fields', '');
            }

            //*** Save options for mail templates... ***
            if(isset($_POST['_template_type'])){
                $template_type = $_POST['_template_type'];

                switch ($template_type){
                    case 'default':
                        $header_image = '';
                        $background_image = '';
                        $footer_image = '';
                        $aditional_text = '';

                        if(isset($_POST['_header_image'])){
                            $header_image = array(
                                'id' => $_POST['_header_image']['id'],
                                'uri' => $_POST['_header_image']['uri']
                            );
                        }
                        if(isset($_POST['_background_image'])){
                            $background_image = array(
                                'id' => $_POST['_background_image']['id'],
                                'uri' => $_POST['_background_image']['uri']
                            );
                        }
                        if(isset($_POST['_footer_image'])){
                            $footer_image = array(
                                'id' => $_POST['_footer_image']['id'],
                                'uri' => $_POST['_footer_image']['uri']
                            );
                        }

                        if(isset($_POST['_aditional_text'])){
                            $aditional_text = $_POST['_aditional_text'];
                        }

                        $barcode_display = isset($_POST['_barcode']['display']) ? $_POST['_barcode']['display'] : '';
                        $barcode_type = isset($_POST['_barcode']['type']) ? $_POST['_barcode']['type'] : 'ticket';

                        $barcode = array(
                            'display' => $barcode_display,
                            'type' => $barcode_type
                        );


                        $data = array(
                            'header_image' => $header_image,
                            'barcode' => $barcode,
                            'background_image' => $background_image,
                            'footer_image' => $footer_image,
                            'aditional_text' => $aditional_text
                        );

                        $mail_template = array(
                            'type' => $template_type,
                            'data' => $data
                        );

                        update_post_meta($post_id, '_mail_template', $mail_template);

                        break;
                }
            }

            do_action('yith_wcevti_save_custom_fields', $post_id);

        }

        /**
         * Enqueue Scripts
         *
         * Register and enqueue scripts for Admin
         *
         * @author Francsico Mateo
         * @since 1.0
         * @return void
         */
        public function enqueue_scripts(){
            global $pagenow;

            $path = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '/unminified' : '';
            $prefix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';

            //Common style for admin...
            wp_register_style( 'yith-wc-style-admin-common', YITH_WCEVTI_ASSETS_URL . 'css/style-admin-common.css', null, YITH_WCEVTI_VERSION);
            wp_enqueue_style('yith-wc-style-admin-common');

            if ( $pagenow == 'post.php' && get_post_type( $_GET['post'] ) == 'product' || $pagenow == 'post-new.php' && $_GET['post_type'] == 'product'){
                $enable_location = get_option('yith_wcte_enable_location');
                $api_key = get_option('yith_wcte_api_key_gmaps');

                //My Style
                wp_register_style( 'yith-wc-style-admin-tickets', YITH_WCEVTI_ASSETS_URL . 'css/style-admin.css', null, YITH_WCEVTI_VERSION);
                wp_enqueue_style('yith-wc-style-admin-tickets');


                // My Scripts
                wp_register_script( 'yith-wc-script-admin-tickets', YITH_WCEVTI_ASSETS_URL . '/js' . $path . '/script-tickets-admin' . $prefix . '.js', array('jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable'), YITH_WCEVTI_VERSION, true );

                $data_to_js = array(
                    'message' => array(
                        'remove_service' => __('Remove this Service?', 'yith-event-tickets-for-woocommerce')
                    )
                );

                wp_localize_script('yith-wc-script-admin-tickets', 'yith_wcevti_admin_tickets', $data_to_js);

                if($enable_location == 'yes' && !empty($api_key)){
                    wp_register_script('yith-wc-script-gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places', array(), YITH_WCEVTI_VERSION, true);
                    wp_enqueue_script('yith-wc-script-gmaps');
                }
                wp_enqueue_script('yith-wc-script-admin-tickets');
            }

            if ($pagenow == 'post.php' && get_post_type( $_GET['post'] ) == 'ticket'){

                //My Style

                wp_register_style( 'yith-wc-style-admin-tickets-table', YITH_WCEVTI_ASSETS_URL . 'css/style-admin-tickets-table.css', null, YITH_WCEVTI_VERSION);
                wp_enqueue_style('yith-wc-style-admin-tickets-table');

            }

            if ($pagenow == 'edit.php' && $_GET['post_type']  == 'ticket') {
                //My Style
                wp_register_style( 'yith-wc-style-admin-tickets-table', YITH_WCEVTI_ASSETS_URL . 'css/style-admin-tickets-table.css', null, YITH_WCEVTI_VERSION);
                wp_enqueue_style('yith-wc-style-admin-tickets-table');
            }


        }

        /**
         * Adds plugin row meta
         *
         * @param $plugin_meta array
         * @param $plugin_file string
         * @return array
         * @since 1.0.0
         */
        public function add_plugin_meta( $plugin_meta, $plugin_file ){
            // documentation link
            $plugin_meta['documentation'] = '<a target="_blank" href="' . $this->doc_url . '">' . __( 'Plugin Documentation', 'yith-event-tickets-for-woocommerce' ) . '</a>';

            return $plugin_meta;
        }

        public function print_field_row_action(){
            if(isset($_POST['index'])){
                $args = array(
                    'index' => $_POST['index']
                );
                wc_get_template('admin/fields_row.php', $args, '', YITH_WCEVTI_TEMPLATE_PATH);
            }
            die();
        }

        public function print_mail_template_action(){
            if(isset($_GET['id'])){
                $post = get_post($_GET['id']);
                yith_wecvti_print_mail_template_preview($post);
            }
            die();
        }

        public function build_product_data_tabs( $tabs ){

            array_push($tabs['inventory']['class'], 'show_if_ticket-event');


            $new_fields_tab = array(
                'event_fields' => array(
                    'label' => __('Fields', 'yith-event-tickets-for-woocommerce'),
                    'target' => 'fields_product_data',
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

            return array_merge($tabs, $new_fields_tab, $mail_template_tab);

        }

        public function build_product_data_content (){
            global $thepostid;
            $args = array(
                'thepostid' => $thepostid
            );
            wc_get_template('admin/product_data_content.php', $args, '', YITH_WCEVTI_TEMPLATE_PATH);
        }

        public function add_columns_ticket($columns){
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'ticket' => __('Ticket', 'yith-event-tickets-for-woocommerce'),
                'order' => __('Order', 'yith-event-tickets-for-woocommerce'),
                'place' => __('Place', 'yith-event-tickets-for-woocommerce'),
                'purchased' => __('Purchase date', 'yith-event-tickets-for-woocommerce'),
                'start_end' => __('Start/End event', 'yith-event-tickets-for-woocommerce')
            );

            return $columns;
        }

        public function render_ticket_custom_columns($column, $post_id){

            switch ($column){
                case 'ticket':
                    $post = get_post($post_id);
                    ?>
                        <a class="row-title" href="<?php echo get_edit_post_link($post_id);?>"><strong><?php echo '#' . $post_id . ', ' . $post->post_title;?></strong></a>
                    <?php
                    break;
                case 'order':
                    $order_id = get_post_meta($post_id, 'wc_order_id', true);
                    $order = wc_get_order($order_id);
                    if(is_a($order, 'WC_Order')){
                        $user = $order->get_user();
                        if(is_a($user, 'WP_User')) {
                            $user_id = $user->ID;
                            $display_name = $user->data->display_name;
                            $user_email = $user->data->user_email;
                        } else {
                            $display_name = $order->billing_first_name;
                            $user_email = $order->billing_email;
                        }

                        ?>
                        <a href="<?php echo get_edit_post_link($order_id);?>"><?php echo '#'.$order_id ;?></a> <?php echo __('by', 'yith-event-tickets-for-woocommerce'); ?> <a href="<?php if(isset($user_id)){ echo get_edit_user_link($user_id);}?>"><?php echo $display_name ;?></a>
                        <small class="meta email"><a href="mailto:<?php echo $user_email;?>"><?php echo $user_email;?></a>
                        <?php if(!isset($user_id)){echo __('Unregistered user', 'yith-event-tickets-for-woocommerce');} ?>
                        </small>
                        <?php
                    } else {
                        echo __('The order linked to this event has been deleted...', 'yith-event-tickets-for-woocommerce');
                    }
                    break;
                case 'place':
                    $product_id = get_post_meta($post_id, 'wc_event_id', true);
                    $direction_event = get_post_meta($product_id, '_direction_event', true);
                    if(empty($direction_event)){
                        echo __('No location has been defined for this event', 'yith-event-tickets-for-woocommerce');
                    } else {
                        echo $direction_event;
                    }
                    break;
                case 'purchased':
                    $order_id = get_post_meta($post_id, 'wc_order_id', true);
                    $order = wc_get_order($order_id);
                    if(is_a($order, 'WC_Order')){
                        $date_format = get_option('date_format');
                        $purchased_date = date_i18n($date_format, strtotime($order->order_date));
                        echo $purchased_date;
                    }
                    break;
                case 'start_end':
                    $product_id = get_post_meta($post_id, 'wc_event_id', true);
                    $start_date = get_post_meta($product_id, '_start_date_picker', true);
                    $end_date = yit_get_post_meta($product_id, '_end_date_picker', true);

                    if(!$start_date | !$end_date){
                        echo __('No start or end date has been defined for this event', 'yith-event-tickets-for-woocommerce');
                    } else{
                        echo date('Y/m/d', strtotime($start_date)) . ' ' . __('to', 'yith-event-tickets-for-woocommerce') . ' ' . date('Y/m/d', strtotime($end_date));
                    }

                    break;
            }

        }

        public function search_ticket_for_place(){

            if(isset($_GET['post_type']) & isset($_GET['s'])){
                if('ticket' == $_GET['post_type'] ){
                    add_filter( 'posts_join', array( $this, 'search_ticket_join_for_place'));
                    add_filter( 'posts_where', array( $this, 'search_ticket_where_for_place'));
                    add_filter( 'posts_search', array( $this, 'search_ticket_search_item_for_place'));
                }
            }
        }

        public function search_ticket_join_for_place($join){
            global $wpdb;

            $join .= ' left join ' . $wpdb->postmeta . ' as postmeta_x on ' . $wpdb->posts . '.ID = postmeta_x.post_id'.
                     ' left join ' . $wpdb->postmeta . ' as postmeta_y on postmeta_x.meta_value = postmeta_y.post_id'  ;

            return $join;
        }

        public function search_ticket_where_for_place($where){

            $where .= ' and postmeta_x.meta_key like "wc_event_id" and postmeta_y.meta_key like "_direction_event"';

            return $where;
        }

        public function search_ticket_search_item_for_place($search){
            $text = $_GET['s'];

            $query = ') or (postmeta_y.meta_value like \'%'. $text .'%\')';

            $search = str_replace('))', $query . ')', $search);

            return $search;
        }

        public function add_meta_boxes() {
            global $post;
            $title = sprintf( __('Event #%d %s details', 'yith-event-tickets-for-woocommerce'), $post->ID, $post->post_title );

            add_meta_box('ticket-order', $title, array($this, 'print_ticket_order_metabox'), 'ticket');

            add_meta_box('ticket-template', __('Ticket template', 'yith-event-tickets-for-woocommerce'), array($this, 'print_ticket_template_metabox'), 'ticket');
        }

        public function print_ticket_order_metabox($post){

            $post_meta = get_post_meta($post->ID, '', true);
            $fields = '';


            foreach ($post_meta as $key => $meta){
                if (preg_match('/field_/i', $key)) {
                    $label = str_replace( array( 'field_' ), '', $key );
                    $value = $meta[0];

                    $fields[] = array(
                        $label => $value
                    );

                }
            }
            $args = array(
                'wc_event_id' => $post_meta['wc_event_id'][0],
                'post' => $post,
                'fields' => $fields,
            );

            yith_wcevti_get_template('ticket_order_meta_box', $args, 'admin');
        }

        public function print_ticket_template_metabox ($post){

            ?>
            <iframe class="iframe_template" src="<?php echo esc_url( add_query_arg( array( 'action' => 'print_mail_template_action', 'id' => $post->ID ), admin_url( 'admin-ajax.php' ) ) ) ?>">

            </iframe>
            <?php
        }
    }
}