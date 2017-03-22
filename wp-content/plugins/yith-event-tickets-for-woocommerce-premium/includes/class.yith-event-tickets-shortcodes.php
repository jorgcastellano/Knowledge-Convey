<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if (!defined('YITH_WCEVTI_PATH')) {
    exit('Direct access forbidden.');
}

/**
 *
 *
 * @class      YITH_Tickets_Shortcodes
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Francsico Mateo
 *
 */

if (!class_exists('YITH_Tickets_Shortcodes')) {
    /**
     * Class YITH_Tickets_Shortcodes
     *
     * @author Francsico Mateo
     */
    class YITH_Tickets_Shortcodes
    {

        public static function init()
        {
            $shortcodes = array(
                'event_map' => __CLASS__ . '::event_map', // print map event
                'event_calendar' => __CLASS__ . '::event_calendar', // print the calendar events
                'users_purchased' => __CLASS__ . '::print_users_purchased', // print the users tickets purchased
                'organizers' => __CLASS__ . '::print_organizers'
            );

            foreach ($shortcodes as $shortcode => $function) {
                add_shortcode($shortcode, $function);
            }

            shortcode_atts( array('id' => ''), array(), 'event_map');
            shortcode_atts( array('id' => ''), array(), 'users_purchased');
            shortcode_atts( array('id' => ''), array(), 'organizers');
        }

        /**
         * ShortCode for map event
         *
         * @return void
         * @since 1.0.0
         */
        public static function event_map($atts){
            global $product;

            $atts_id = isset($atts['id']) ? $atts['id'] : '';
            $atts_id = empty($atts_id) & is_product() ?  yit_get_product_id( $product ) : $atts_id;

            $product = wc_get_product($atts_id);

            $enable_location = get_option('yith_wcte_enable_location');
            $api_key = get_option('yith_wcte_api_key_gmaps');

            if ($enable_location == 'yes' && !empty($api_key)) {
                if(!empty($atts_id)) {
                    $id = $atts_id;
                    $latitude = get_post_meta($id, '_latitude_event', true);
                    $longitude = get_post_meta($id, '_longitude_event', true);
                    $address = get_post_meta($id, '_direction_event', true);


                    if(isset($latitude) & isset($longitude)){

                        $args = array(
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'address' => $address
                        );

                        wp_enqueue_script('yith-wc-script-gmaps');
                        wp_enqueue_script('yith-wcevti-script-frontend-shortcodes-tickets');
                        wp_enqueue_style('yith-wcevti-style-frontend-shortcodes-tickets');
                        wc_get_template('frontend/map-address-frontend.php', $args, '', YITH_WCEVTI_TEMPLATE_PATH);
                    }


                }
            }
        }

        /**
         * ShortCode for event calendar
         *
         * @return void
         * @since 1.0.0
         */
        public static function event_calendar()
        {

            wp_enqueue_script('yith-wcevti-script-frontend-shortcodes-tickets');
            wp_enqueue_style('yith-wcevti-style-frontend-shortcodes-tickets');

            wp_enqueue_script('yith-wcevti-script-frontend-calendar-tickets');
            wp_enqueue_style('yith-wcevti-style-frontend-calendar-tickets');

            wc_get_template('frontend/event-calendar-frontend.php', array(), '', YITH_WCEVTI_TEMPLATE_PATH);
        }


        /**
         * ShorCode for users purchased
         *
         * @return void
         * @since 1.0.0
         */
        public static function print_users_purchased($atts){
            global $product;

            $atts_id = isset($atts['id']) ? $atts['id'] : '';
            $atts_id = empty($atts_id) & is_product() ?  yit_get_product_id( $product ) : $atts_id;

            $product = wc_get_product($atts_id);

            if('ticket-event' == $product->get_type()) {
                $purchased_tickets = yith_wcevti_get_orders_from_product(yit_get_product_id( $product ));
                $title = __('Users who will take part in the event', 'yith-event-tickets-for-woocommerce');

                wp_enqueue_style('yith-wcevti-style-frontend-shortcodes-tickets');
                yith_wcevti_get_template('users_purchased', array('title' => $title, 'users_tickets' => $purchased_tickets), 'frontend');
            }
        }

        /**
         * ShorCode for display organizers
         *
         * @return void
         * @since 1.0.0
         */
        public static function print_organizers($atts){
            global $product;

            $atts_id = isset($atts['id']) ? $atts['id'] : '';
            $atts_id = empty($atts_id) & is_product() ?  yit_get_product_id( $product ) : $atts_id;

            $product = wc_get_product($atts_id);

            if('ticket-event' == $product->get_type()) {
                $organization = yit_get_prop($product, '_organization', true);
                $values_selected = isset($organization['values']) ? is_array($organization['values'])? $organization['values'] : explode( ',', $organization['values'] ) : array();

                $organizers = array();

                foreach($values_selected as $value){
                    $user = get_user_by('id', $value);

                    if(is_object($user)){
                    $organizers[$user->data->user_nicename] = array(
                        'display_name' => $user->data->display_name,
                        'avatar' => get_avatar($user->data->ID)
                    );
                    }
                }
                $title = __('Organizers', 'yith-event-tickets-for-woocommerce');
                wp_enqueue_style('yith-wcevti-style-frontend-shortcodes-tickets');
                yith_wcevti_get_template('users_purchased', array('title' => $title, 'users_tickets' => $organizers), 'frontend');
            }
        }

    }
}