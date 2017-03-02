<?php

if ( !function_exists( 'yith_wcmbs_get_membership_statuses' ) ) {

    /**
     * Return the list of status available
     *
     * @return array
     * @since 1.0.0
     */

    function yith_wcmbs_get_membership_statuses() {
        $options = array(
            'active'     => __( 'active', 'yith-woocommerce-membership' ),
            'paused'     => __( 'paused', 'yith-woocommerce-membership' ),
            'not_active' => __( 'not active', 'yith-woocommerce-membership' ),
            'resumed'    => __( 'resumed', 'yith-woocommerce-membership' ),
            'expiring'   => __( 'expiring', 'yith-woocommerce-membership' ),
            'cancelled'  => __( 'cancelled', 'yith-woocommerce-membership' ),
            'expired'    => __( 'expired', 'yith-woocommerce-membership' ),
        );

        return apply_filters( 'yith_wcmbs_membership_statuses', $options );
    }
}


if ( !function_exists( 'yith_wcmbs_get_dates_customer_bought_product' ) ) {
    /**
     * Checks if a user (by email) has bought an item
     *
     * @param int   $user_id
     * @param int   $product_id
     * @param array $args
     *
     * @return array|bool array of dates when customer bought the product; return false if customer didn't buy the product
     */
    function yith_wcmbs_get_dates_customer_bought_product( $user_id, $product_id, $args = array() ) {
        global $wpdb;

        $customer_data = array( $user_id );

        if ( $user_id ) {
            $user = get_user_by( 'id', $user_id );

            if ( isset( $user->user_email ) ) {
                $customer_data[] = $user->user_email;
            }
        }

        $customer_data = array_map( 'esc_sql', array_filter( array_unique( $customer_data ) ) );

        if ( sizeof( $customer_data ) == 0 ) {
            return false;
        }

        $limit = isset( $args[ 'limit' ] ) ? ( "LIMIT " . $args[ 'limit' ] ) : '';

        $results = $wpdb->get_results( $wpdb->prepare( "
				SELECT p.post_date FROM {$wpdb->posts} AS p
				INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
				INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
				INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
				WHERE p.post_status IN ( 'wc-completed', 'wc-processing' )
				AND pm.meta_key IN ( '_billing_email', '_customer_user' )
				AND im.meta_key IN ( '_product_id', '_variation_id' )
				AND im.meta_value = %d
				", $product_id ) . " AND pm.meta_value IN ( '" . implode( "','", $customer_data ) . "' )" . " ORDER BY p.post_date DESC " . $limit );

        $membership_dates = array();
        if ( !empty( $results ) ) {
            foreach ( $results as $r ) {
                $membership_dates[] = $r->post_date;
            }
        }

        $membership_dates = array_unique( $membership_dates );

        if ( !empty( $membership_dates ) && isset( $args[ 'limit' ] ) && $args[ 'limit' ] == 1 ) {
            return $membership_dates[ 0 ];
        }

        return !empty( $membership_dates ) ? $membership_dates : false;
    }
}
?>