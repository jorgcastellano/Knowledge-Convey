<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Member Class
 *
 * @class   YITH_WCMBS_Membership
 * @package Yithemes
 * @since   1.0.0
 * @author  Yithemes
 *
 */
class YITH_WCMBS_Membership {

    /**
     * id of membership
     *
     * @var int
     * @since 1.0.0
     */
    public $id;

    /**
     * post of membership
     *
     * @var WP_Post|bool
     * @since 1.0.0
     */
    public $post;

    /**
     * Constructor
     *
     * @param int   $membership_id the membership id
     * @param array $args          array of meta for creating membership
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     */
    public function __construct( $membership_id = 0, $args = array() ) {

        //populate the membership if $membership_id is defined
        if ( $membership_id ) {
            $this->id = $membership_id;
            $this->populate();
        }

        //add a new membership if $args is passed
        if ( $membership_id == 0 && !empty( $args ) ) {
            $this->add_membership( $args );
        }

        // check if status is expired or in expiring
        $this->check_is_expiring();
        $this->check_is_expired();
    }

    /**
     * __get function.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get( $key ) {
        $value = get_post_meta( $this->id, '_' . $key, true );

        if ( !empty( $value ) ) {
            $this->$key = $value;
        }

        return $value;
    }

    /**
     * __set function.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return bool|int
     */
    public function set( $property, $value ) {
        $this->$property = $value;

        return update_post_meta( $this->id, '_' . $property, $value );
    }

    /**
     * Populate the membership
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com
     * @return void
     */
    public function populate() {

        $this->post = get_post( $this->id );

        foreach ( $this->get_membership_meta() as $key => $value ) {
            $this->$key = $value;
        }

        do_action( 'yith_wcmbs_membership_loaded', $this );
    }

    /**
     * Check if the Membership is valid, controlling if this post exist
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com
     * @return bool
     */
    public function is_valid() {
        return !!$this->post;
    }

    /**
     * Add new membership
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com
     * @return void
     */
    public function add_membership( $args ) {

        $plan_title = isset( $args[ 'title' ] ) ? $args[ 'title' ] : '';

        $membership_id = wp_insert_post( array(
            'post_status' => 'publish',
            'post_type'   => 'ywcmbs-membership',
            'post_title'  => $plan_title
        ) );

        if ( $membership_id ) {
            $this->id = $membership_id;
            $meta     = wp_parse_args( $args, $this->get_default_meta_data() );
            $this->update_membership_meta( $meta );
            $this->populate();

            $this->add_activity( 'new', $this->status, __( 'Membership successfully created.', 'yith-woocommerce-membership' ) );

            $mailer = WC()->mailer();
            // Send notification for New Membership
            $notification_args = array(
                'user_id'    => $this->user_id,
                'membership' => $this
            );
            do_action( 'yith_wcmbs_new_member_notification', $notification_args );
        }
    }

    /**
     * Update post meta in membership
     *
     * @param array $meta the meta
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com
     * @return void
     */
    function update_membership_meta( $meta ) {
        foreach ( $meta as $key => $value ) {
            update_post_meta( $this->id, '_' . $key, $value );
        }
    }

    /**
     * Updates status of membership
     *
     * @param string $new_status
     * @param string $activity
     * @param string $additional_note
     *
     */
    public function update_status( $new_status, $activity = 'change_status', $additional_note = '' ) {
        if ( !$this->id ) {
            return;
        }

        $old_status = $this->status;

        // cannot update status if it's expired or cancelled
        if ( in_array( $old_status, array( 'expired', 'cancelled' ) ) )
            return;

        if ( $new_status !== $old_status || !in_array( $new_status, array_keys( yith_wcmbs_get_membership_statuses() ) ) ) {

            // Status was changed
            do_action( 'yith_wcmbs_membership_status_' . $new_status, $this->id );
            do_action( 'yith_wcmbs_membership_status_' . $old_status . '_to_' . $new_status, $this->id );
            do_action( 'yith_wcmbs_membership_status_changed', $this->id, $old_status, $new_status );

            switch ( $new_status ) {
                case 'active' :
                    // Update the membership status
                    $this->set( 'status', $new_status );
                    $note = __( 'Membership has now been activated.', 'yith-woocommerce-membership' ) . ' ' . $additional_note;
                    $this->add_activity( $activity, $new_status, $note );
                    break;

                case 'paused' :
                    if ( !$this->can_be_paused() )
                        return;
                    // Update the membership status
                    $this->set( 'status', $new_status );
                    $note = __( 'Membership paused.', 'yith-woocommerce-membership' ) . ' ' . $additional_note;
                    $this->add_activity( $activity, $new_status, $note );
                    break;
                case 'resumed' :
                    if ( !$this->can_be_resumed() )
                        return;

                    $new_end_date  = '';
                    $last_activity = $this->get_last_activity();

                    // calculate and set paused days
                    $paused_days_in_sec = time() - $last_activity->timestamp;
                    $paused_days        = intval( ( $paused_days_in_sec ) / ( 24 * 60 * 60 ) );
                    $paused_days_tot    = $paused_days + $this->paused_days;
                    $this->set( 'paused_days', $paused_days_tot );

                    // update expiring date
                    if ( !$this->is_unlimited() ) {
                        $new_end_date = $this->end_date + $paused_days_in_sec;
                        $this->set( 'end_date', $new_end_date );
                    }

                    // Update the membership status
                    $this->set( 'status', 'resumed' );
                    $resumed_note = '';
                    if ( !empty( $new_end_date ) ) {
                        $resumed_note = __( 'Membership resumed.', 'yith-woocommerce-membership' ) . sprintf( __( 'Expiration date set to %s.', 'yith-woocommerce-membership' ), date_i18n( wc_date_format(), $new_end_date ) );
                    } else {
                        $resumed_note = __( 'Membership resumed.', 'yith-woocommerce-membership' );
                    }
                    $note = $resumed_note . ' ' . $additional_note;
                    $this->add_activity( $activity, $new_status, $note );

                    break;
                case 'cancelled' :
                    if ( !$this->can_be_cancelled() )
                        return;
                    $this->set( 'status', $new_status );
                    $cancelled_note = sprintf( __( 'Membership status updated to %s.', 'yith-woocommerce-membership' ), strtr( $new_status, yith_wcmbs_get_membership_statuses() ) );
                    $note           = $cancelled_note . ' ' . $additional_note;
                    $this->add_activity( $activity, $new_status, $note );
                    break;
                default:
                    $this->set( 'status', $new_status );
                    $update_status_note = sprintf( __( 'Membership status updated to %s.', 'yith-woocommerce-membership' ), strtr( $new_status, yith_wcmbs_get_membership_statuses() ) );
                    $note               = $update_status_note . ' ' . $additional_note;
                    $this->add_activity( $activity, $new_status, $note );
                    break;
            }

        }

        // check if status is expired or in expiring
        $this->check_is_expiring();
        $this->check_is_expired();
    }

    /**
     * Fill the default metadata with the post meta stored in db
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com
     * @return array
     */
    function get_membership_meta() {
        $membership_meta = array();
        foreach ( $this->get_default_meta_data() as $key => $value ) {
            $membership_meta[ $key ] = get_post_meta( $this->id, '_' . $key, true );
        }

        return $membership_meta;
    }


    /**
     * Return an array of all custom fields membership
     *
     *
     * @since  1.0.0
     * @author Leanza Francesco <leanzafrancesco@gmail.com
     * @return array
     */
    private function get_default_meta_data() {
        $membership_meta_data = array(
            'plan_id'       => 0,
            'title'         => '',
            'start_date'    => '',
            'end_date'      => '',
            'order_id'      => 0,
            'order_item_id' => 0,
            'user_id'       => 0,
            'status'        => 'active',
            'paused_days'   => 0,
            'activities'    => array(),
        );

        return $membership_meta_data;
    }

    /**
     * Add Activity to membership
     *
     * @param string $activity
     * @param string $status
     * @param string $note
     *
     * @access public
     * @since  1.0.0
     */
    public function add_activity( $activity, $status, $note = '' ) {
        $timestamp = time();

        $act = new YITH_WCMBS_Activity( $activity, $status, $timestamp, $note );

        $this->activities[] = $act;
        $this->set( 'activities', $this->activities );
    }

    /**
     * Set the end date in base of duration
     *
     * @param int $duration
     *
     * @access public
     * @since  1.0.0
     */
    public function set_end_date( $duration ) {
        if ( $duration < 1 ) {
            $this->set( 'end_date', 'unlimited' );
        } else {
            $this->set( 'end_date', $duration + $this->start_date );
        }
    }


    /**
     * Get the last timestamp date in activities
     *
     * @access public
     * @since  1.0.0
     *
     * @return string|bool
     */
    public function get_last_timestamp_date() {
        $last_activity = $this->get_last_activity();

        return ( $last_activity ) ? $last_activity->timestamp : false;
    }

    /**
     * Get the last activity
     *
     * @access public
     * @since  1.0.0
     *
     * @return YITH_WCMBS_Activity
     */
    public function get_last_activity() {
        return end( $this->activities );
    }

    /**
     * Get the expire date, considering paused_days
     *
     * @access public
     * @since  1.0.0
     */
    public function get_expire_date() {
        if ( !$this->is_unlimited() && $this->paused_days > 0 ) {
            return ( $this->end_date + ( $this->paused_days * 60 * 60 * 24 ) );
        }

        return $this->end_date;
    }


    /**
     * Return html containing the start and expiration dates
     *
     * @access public
     * @since  1.0.0
     *
     * @return string
     */
    public function get_dates_html() {
        $data = __( 'Starting Date', 'yith-woocommerce-membership' ) . ':<br />' . $this->get_formatted_date( 'start_date' ) . '<br />';
        $data .= __( 'Expiration Date', 'yith-woocommerce-membership' ) . ':<br />' . $this->get_formatted_date( 'end_date' ) . '<br />';

        return $data;
    }

    /**
     * Return html containing all info about plan
     *
     * @access public
     * @since  1.0.0
     *
     * @return string
     */
    public function get_plan_info_html() {
        $html = $this->get_dates_html();
        $html .= __( 'Status', 'yith-woocommerce-membership' ) . ':<br />' . $this->get_status_text() . '<br />';

        return $html;
    }

    /**
     * Return string for status
     *
     * @access public
     * @since  1.0.0
     *
     * @return string
     */
    public function get_status_text() {
        $text = strtr( $this->status, yith_wcmbs_get_membership_statuses() );

        return $text;
    }

    /**
     * Return string for dates
     *
     * @param string $date_type the type of date
     * @param bool   $with_time if it's true include time in date format
     *
     * @access public
     * @since  1.0.0
     *
     * @return string
     */
    public function get_formatted_date( $date_type, $with_time = false ) {
        $format = wc_date_format();
        $format .= $with_time ? ( ' ' . wc_time_format() ) : '';

        switch ( $date_type ) {
            case 'end_date':
                return $this->is_unlimited() ? __( 'Unlimited', 'yith-woocommerce-membership' ) : date( $format, $this->get_expire_date() );
                break;
            case 'last_update':
                return date( $format, $this->get_last_timestamp_date() );
                break;
            default:
                return date( $format, $this->$date_type );
                break;
        }
    }

    /**
     * get the linked plans ids
     * return false if the plan don't have linked plans
     *
     * @access public
     * @since  1.0.0
     *
     * @return array
     */
    public function get_linked_plans() {
        $linked_plans = get_post_meta( $this->plan_id, '_linked-plans', true );

        return !empty( $linked_plans ) ? $linked_plans : array();
    }

    /**
     * Return true if status is active, resumed or expiring
     *
     * @return bool
     *
     * @access public
     * @since  1.0.0
     */
    public function is_active() {
        return in_array( $this->status, array( 'active', 'resumed', 'expiring' ) );
    }

    /**
     * Return true if membership is unlimited
     *
     * @return bool
     *
     * @access public
     * @since  1.0.0
     */
    public function is_unlimited() {
        return $this->end_date == 'unlimited';
    }

    /**
     * Check if this is in expired
     *
     * @return void
     *
     * @access public
     * @since  1.0.0
     */
    public function check_is_expired() {
        if ( in_array( $this->status, array( 'active', 'resumed', 'expiring' ) ) && !$this->is_unlimited() ) {
            if ( $this->get_remaining_days() <= 0 ) {
                $this->update_status( 'expired' );
            }
        }
    }

    /**
     * Check if this is in expiring
     *
     * @return void
     *
     * @access public
     * @since  1.0.0
     */
    public function check_is_expiring() {
        if ( in_array( $this->status, array( 'active', 'resumed' ) ) && !$this->is_unlimited() ) {
            if ( $this->get_remaining_days() <= 10 ) {
                $this->update_status( 'expiring' );

                WC()->mailer();
                $args = array(
                    'user_id'    => $this->user_id,
                    'membership' => $this
                );

                do_action( 'yith_wcmbs_membership_expiring_notification', $args );
            }
        }
    }


    /**
     * Return the remaining days
     *
     * @return int
     *
     * @access public
     * @since  1.0.0
     */
    public function get_remaining_days() {
        if ( $this->is_unlimited() )
            return -1;

        $remaining_days = ( $this->get_expire_date() - time() ) / ( 60 * 60 * 24 );

        return ( $remaining_days > 0 ) ? absint( $remaining_days ) : 0;
    }

    /**
     * return true if the membership can be cancelled
     *
     * @return bool
     *
     * @access public
     * @since  1.0.0
     */
    public function can_be_cancelled() {
        return !in_array( $this->status, array( 'expired', 'cancelled' ) );
    }

    /**
     * return true if the membership can be paused
     *
     * @return bool
     *
     * @access public
     * @since  1.0.0
     */
    public function can_be_paused() {
        return $this->is_active();
    }

    /**
     * return true if the membership can be resumed
     *
     * @return bool
     *
     * @access public
     * @since  1.0.0
     */
    public function can_be_resumed() {
        return in_array( $this->status, array( 'not_active', 'paused' ) );
    }

    /**
     * get the current name of plan
     *
     * @return string
     *
     * @access public
     * @since  1.0.0
     */
    public function get_plan_title() {
        $title = get_the_title( $this->plan_id );
        if ( empty( $title ) ) {
            $title = $this->title;
        }

        return $title;
    }

    /**
     * control if thi membership has subscription plan linked
     *
     * @return bool
     *
     * @access public
     * @since  1.0.0
     */
    public function has_subscription() {
        $subscription_id = $this->subscription_id;

        return !empty( $subscription_id );
    }


    /**
     * check if item is in membership plan or in linked plans, in base of delay time
     *
     * @param $post_id
     */
    public function has_item( $post_id ) {

    }

    /**
     * Get products in this membership
     * include linked plans
     *
     * @param array $args              {
     *                                 Optional Arguments to retrieve products
     *
     * @type string $return            the type of return values. Allowed 'ids', 'posts', 'products'
     * @type bool   $only_downloadable do you want retrieve only downloadable products?
     *         }
     *
     * @return int[]|WC_Product[]|WP_Post[] List of products ids or product objects or post objects
     *
     * @access public
     * @since  1.0.0
     */
    public function get_products( $args = array() ) {
        $default_args = array(
            'return'            => 'ids',
            'only_downloadable' => apply_filters( 'yith_wcmbs_membership_default_only_downloadable', false ),
        );

        $args              = wp_parse_args( $args, $default_args );
        $return            = 'ids';
        $only_downloadable = false;
        extract( $args );

        $plan_ids   = $this->get_linked_plans();
        $plan_ids[] = $this->plan_id;

        $products = array();
        // get products in plan
        foreach ( $plan_ids as $plan_id ) {
            $args = array(
                'post_type'                  => 'product',
                'posts_per_page'             => -1,
                'post_status'                => 'publish',
                'yith_wcmbs_suppress_filter' => true,
                'meta_query'                 => array(
                    array(
                        'key'     => '_yith_wcmbs_restrict_access_plan',
                        'value'   => $plan_id,
                        'compare' => 'LIKE',
                    )
                ),
            );

            $products = array_unique( array_merge( $products, get_posts( $args ) ), SORT_REGULAR );
        }

        foreach ( $plan_ids as $plan_id ) {
            $plan_cats      = get_post_meta( $plan_id, '_product-cats', true );
            $plan_prod_tags = get_post_meta( $plan_id, '_product-tags', true );

            $cat_tag_args = array(
                'post_type'                  => 'product',
                'posts_per_page'             => -1,
                'post_status'                => 'publish',
                'yith_wcmbs_suppress_filter' => true,
                'tax_query'                  => array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $plan_cats,
                        'operator' => 'IN'
                    ),
                    array(
                        'taxonomy' => 'product_tag',
                        'field'    => 'term_id',
                        'terms'    => $plan_prod_tags,
                        'operator' => 'IN'
                    )
                ),
            );
            $products     = array_unique( array_merge( $products, get_posts( $cat_tag_args ) ), SORT_REGULAR );
        }

        $r = array();
        if ( !empty( $products ) ) {
            foreach ( $products as $product_post ) {
                $product = wc_get_product( $product_post->ID );

                $delay = get_post_meta( $product_post->ID, '_yith_wcmbs_plan_delay', true );

                $plans_delay_intersect = array_intersect( $plan_ids, array_keys( $delay ) );

                if ( !empty( $delay ) && !empty( $plans_delay_intersect ) ) {

                    // get the minimum delay [between linked plans]
                    $delay_for_plans = 0;
                    if ( isset( $delay[ $this->plan_id ] ) ) {
                        $delay_for_plans = $delay[ $this->plan_id ];
                    } else {
                        $first = true;
                        foreach ( $plan_ids as $plan_id ) {
                            if ( $first ) {
                                if ( isset( $delay[ $plan_id ] ) ) {
                                    $delay_for_plans = $delay[ $plan_id ];
                                    $first           = false;
                                }
                            } else {
                                if ( isset( $delay[ $plan_id ] ) && $delay_for_plans > $delay[ $plan_id ] ) {
                                    $delay_for_plans = $delay[ $plan_id ];
                                }
                            }
                        }
                    }

                    if ( $delay_for_plans > 0 ) {
                        $delay_days = $delay_for_plans;
                        $date       = $this->start_date + ( $this->paused_days * 60 * 60 * 24 );

                        $passed_days = intval( ( time() - $date ) / ( 24 * 60 * 60 ) );
                        if ( $passed_days <= $delay_days )
                            continue;
                    }
                }

                if ( $product ) {
                    $downloadable = false;
                    if ( $product->product_type != 'variable' ) {
                        if ( $product->is_downloadable() ) {
                            $downloadable = true;
                        }
                    } else {
                        $variations = $product->get_children();
                        if ( !empty( $variations ) ) {
                            foreach ( $variations as $variation ) {
                                $p_tmp = wc_get_product( $variation );
                                if ( $p_tmp->is_downloadable() ) {
                                    $downloadable = true;
                                    break;
                                }
                            }
                        }
                    }

                    // add ONLY Downloadable Products
                    if ( !$only_downloadable || $downloadable ) {
                        switch ( $return ) {
                            case 'ids':
                                $r[] = $product_post->ID;
                                break;
                            case 'products':
                                $r[] = $product;
                                break;
                            case 'posts':
                                $r[] = $product_post;
                                break;
                        }
                    }
                }
            }
        }

        return $r;
    }


    /**
     * Get items in this membership
     * include linked plans
     *
     * @param array $args              {
     *                                 Optional Arguments to retrieve items
     *
     * @type string $return            the type of return values. Allowed 'ids', 'posts'
     * @type bool   $only_downloadable do you want retrieve only downloadable products?
     *         }
     *
     * @return int[]|WP_Post[] List of items ids or post objects
     *
     * @access public
     * @since  1.0.0
     */
    public function get_items( $args = array() ) {
        $r = array();

        return $r;
    }

}