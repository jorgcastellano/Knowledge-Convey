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
 * @class      YITH_Tickets_Event
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Francsico Mateo
 *
 */

if ( ! class_exists( 'WC_Product_Ticket_Event' ) ) {
    /**
     * Class WC_Product_Ticket_Event
     * Name class must be called 'WC_Product_Product_Type' where 'Product_Type' its the name our type product.
     * This is necesary for WC_Product_Factory can handle class and insert magically.
     *
     * @author Francsico Mateo
     */
    class WC_Product_Ticket_Event extends WC_Product_Simple {

        /**
         * Construct
         *
         * @author Francsico Mateo
         * @since 1.0
         */
        public function __construct($product){
            /* === Here create my Event product extending WC_Product_Simple === */

            parent::__construct($product);
            $this->product_type = 'ticket-event';
            $this->supports[] = 'add_to_cart';

            $item = array_diff( $this->supports, array( 'add_to_cart' ));
            unset($this->supports[ key($item)]);

            $this->price = (double) parent::get_price() + $this->get_stock_overcharge() + $this->get_time_overcharge();

        }

        /**
         * Returns the product's active price.
         *
         * @return double price
         * @overwrite
         */
        public function get_price() {
            return (double) parent::get_price(); // + $this->get_stock_overcharge() + $this->get_time_overcharge();
        }

        /**
         * @return increase_stock_value
         */
        public function get_stock_overcharge()
        {
            $increase_by_stock = get_post_meta( $this->id, '_increase_by_stock', true );
            $increase_value = 0;
            if ( 'yes' === get_post_meta( $this->id, '_manage_stock', true ) ) {
                $current_stock = $this->get_stock_quantity();
                $increase_value = $this->get_overcharge($increase_by_stock, $current_stock);
            }
            return $increase_value;
        }

        /**
         * @return increase_time_value
         */
        public function get_time_overcharge()
        {
            $increase_by_time = get_post_meta( $this->id, '_increase_by_time', true );
            $start_event_date = get_post_meta( $this->id, '_start_date_picker', true );
            $start_event_timestamp = strtotime( $start_event_date );
            $current_timestamp = time();

            $seconds_left = $start_event_timestamp - $current_timestamp;
            $days_left = ceil( $seconds_left / DAY_IN_SECONDS );


            $increase_value = 0;
            if(isset($increase_by_time) && !empty($increase_by_time)){
                $increase_value = $this->get_overcharge($increase_by_time, $days_left);
            }
            return $increase_value;
        }

        /***
         * @param $increase_by arrays rules
         * @param $current_value the time or stock number
         * @return float|int price to overcharge
         */
        public function get_overcharge($increase_by, $current_value){
            $increase_value = 0;
            //Compare current and if is it lower or equal keep rule.
            $current_rule = null;
            if(is_array($increase_by)){
                $increase_by_index = array();
                //Loop increase by to keep threshold on index
                foreach ($increase_by as $increase){
                    if(isset($increase['_threshold'])){
                        $increase_by_index [] = $increase['_threshold'];
                    }
                }
                //Short increase index more to less
                arsort($increase_by_index);

                // Loop index and we get the less value coincidence to $current_value.
                foreach ($increase_by_index as $key => $index){
                    if($index >= $current_value ){
                        $current_rule = $increase_by[$key];
                    }
                }

            }
            if( is_null( $current_rule ) ){
                return 0;
            }
            if ('fixed' == $current_rule['_increase_ticket_event_type']) {
                $increase_value = $current_rule['_increase_fixed_amount'];
            }
            if ('percentage' == $current_rule['_increase_ticket_event_type']) {
                if (100 >= $current_rule['_increase_percentage_amount']) {
                    $increase_value = (parent::get_price() * $current_rule['_increase_percentage_amount']) / 100;
                }
            }
            return $increase_value;
        }

        /**
         * @return string
         */
        public function add_to_cart_url()
        {
            return apply_filters( 'woocommerce_product_add_to_cart_url', get_permalink( $this->id ), $this );
        }

    }
}