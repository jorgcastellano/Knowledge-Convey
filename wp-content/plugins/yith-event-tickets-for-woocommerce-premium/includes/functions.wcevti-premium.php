<?php
if ( ! function_exists( 'yith_wcevti_get_service_stocks' ) ) {
    /**
     * return array stocks services from product.
     * @param $post_id
     */
    function yith_wcevti_get_service_stocks($post_id)
    {
        global $wpdb;

        $query = 'select meta_key from ' . $wpdb->postmeta .
            ' where meta_key like "service_%_stock"
            and post_id=' . $post_id;

        $stocks_services = $wpdb->get_results($query, ARRAY_A);

        return $stocks_services;

    }
}
if ( ! function_exists( 'yith_wcevti_clean_service_stock' ) ) {
    /**
     * Loop current_stock services from event and compare with news service to add. If no coincidence, remove from database.
     * @param $post_id
     * @param $services
     * @param $current_stock
     */
    function yith_wcevti_clean_service_stock($post_id, $services, $current_stock)
    {

        foreach ($current_stock as $service_stock) {
            $delete = true;
            foreach ($services as $service) {
                if ('select' == $service['_type']) {
                    foreach ($service['_select'] as $select) {
                        if ('_service_' . $select['_label'] . '_stock' == $service_stock['meta_key']) {
                            $delete = false;
                        }
                    }
                }
                if ('checkbox' == $service['_type']) {
                    if ('_service_' . $service['_label'] . '_stock' == $service_stock['meta_key']) {
                        $delete = false;
                    }
                }
            }
            if ($delete) {
                delete_post_meta($post_id, $service_stock['meta_key']);
            }
        }
    }
}
if ( ! function_exists( 'yith_wcevti_get_dates' ) ) {
    function yith_wcevti_get_dates()
    {
        $dates = array(
            'monthly' => array()
        );

        $args = array(
            'numberposts' => -1,
            'meta_key' => '_start_date_picker',
            'post_type' => 'product'
        );

        $posts = get_posts($args);

        foreach ($posts as $post) {
            $event = array(
                'id' => $post->ID,
                'name' => $post->post_title,
                'startdate' => get_post_meta($post->ID, '_start_date_picker', true),
                'enddate' => get_post_meta($post->ID, '_end_date_picker', true),
                'starttime' => get_post_meta($post->ID, '_start_time_picker', true),
                'endtime' => get_post_meta($post->ID, '_end_time_picker', true),
                'color' => '#7BA7CE',
                'url' => get_permalink($post->ID)
            );

            $dates['monthly'][] = $event;
        }

        return $dates;
    }
}
if ( ! function_exists( 'yith_wcevti_check_service_sold' ) ) {
    /**
     * Check stock service for Event Ticket
     * @param $post_id the id of Event.
     * @param $service the service to check stock
     * @return $value Different string accord to result 'sold'...
     */
    function yith_wcevti_check_service_sold($post_id, $service, $check_service)
    {
        global $wpdb;

        if (!empty($service['_label'])) {
            //If service is select...
            if ('select' == $service['_type']) {
                //Check if service was buyed...
                if (isset($service['_value'][sanitize_title($service['_label'])])) {
                    $queryexist = 'select count(meta_value) from ' . $wpdb->postmeta .
                        ' where meta_key like "_service_' . $service['_label'] . '_%range"
                                      and meta_value = ' . $service['_value'][sanitize_title($service['_label'])] .
                        ' and post_id =' . $post_id;
                    $count_service = $wpdb->get_var($queryexist);
                    if (0 < $count_service) {
                        $check_service = 'sold';
                    }
                }
            }
        }

        return $check_service;
    }
}
if ( ! function_exists( 'yith_wcevti_add_service_sold' ) ) {
    /**
     * Add service sold on product postmeta.
     * @param $post_id the id product
     * @param $service the service to add
     */
    function yith_wcevti_add_service_sold($post_id, $service)
    {
        global $wpdb;

        //Check if select service...
        if (!empty($service['_label'])) {

            switch ($service['_type']) {

                case 'select':
                    if (!empty($service['_value'][sanitize_title($service['_label'])])) {
                        //Get the count of the current services added for index...
                        $querycount = 'select count(meta_value) from ' . $wpdb->postmeta .
                            ' where meta_key like "_service_' . $service['_label'] . '_%range"
                                                    and post_id =' . $post_id;
                        $count = $wpdb->get_var($querycount);

                        update_post_meta($post_id, '_service_' . $service['_label'] . '_' . $count++ . '_range', $service['_value'][sanitize_title($service['_label'])]);
                    } else {
                        //Behavior simple select just the same than checkbox service.
                        add_check_service_sold($post_id, $service);
                    }
                    break;

                case 'checkbox':
                    if (isset($service['_value'])) {
                        add_check_service_sold($post_id, $service);
                    }
                    break;
            }
        }
    }
}
if ( ! function_exists( 'add_check_service_sold' ) ) {
    function add_check_service_sold($post_id, $service)
    {
        global $wpdb;

        //Get the value check service, this will contain the current check service buyed...
        $querycheck = 'select meta_value from ' . $wpdb->postmeta .
            ' where meta_key like "_service_' . $service['_label'] . '_%range"
                                                    and post_id =' . $post_id;
        $check_stock = $wpdb->get_var($querycheck);

        //If service not buyed, we initilize variable to starting add.
        if (empty($check_stock)) {
            $check_stock = 0;
        }
        $check_stock = $check_stock + $service['_quantity'];
        update_post_meta($post_id, '_service_' . $service['_label'] . '_range', $check_stock);
    }
}
if ( ! function_exists( 'yith_wcevti_count_services_cart' ) ) {
    /**
     * Loop cart finding same product service and return array with number services for each product...
     * For example...:
     * [302] = array( [seats] = 2, [vegetarian] = 5)
     * [420] = array( [vip] = 8, [platea] = 85)
     * @return array
     */
    function yith_wcevti_count_services_cart()
    {
        $cart = WC()->cart;
        $count_services = array();

        foreach ($cart->cart_contents as $key => $cart_item) {
            $product_id = $cart_item['product_id'];

            if ('ticket-event' == $cart_item['data']->get_type() & isset($cart_item['_field_service'])) {

                if (isset($cart_item['_field_service']['_services'])) {
                    if (is_array($cart_item['_field_service']['_services'])) {
                        foreach ($cart_item['_field_service']['_services'] as $service) {
                            $count_services = yith_wcevti_count_service($product_id, $service, $count_services, $cart_item['quantity']);
                        }
                    }
                }
            }
        }
        return $count_services;
    }
}
if ( ! function_exists( 'yith_wcevti_count_services_product' ) ) {
    function yith_wcevti_count_services_product($product_id, $array_services)
    {
        $count_product_service[$product_id] = array();
        foreach ($array_services as $item_services) {
            foreach ($item_services as $service) {
                $count_product_service = yith_wcevti_count_service($product_id, $service, $count_product_service);
            }
        }

        $services_cart = yith_wcevti_count_services_cart();

        foreach ($count_product_service as $id_product_a => &$product_services_a) {
            foreach ($services_cart as $id_product_b => $product_services_b) {
                if ($id_product_a == $id_product_b) {
                    foreach ($product_services_a as $label_a => &$service_a) {
                        foreach ($product_services_b as $label_b => $service_b) {
                            if ($label_b == $label_a) {
                                $service_a['_value'] += $service_b['_value'];
                            }
                        }
                    }
                }
            }
        }
        return $count_product_service;
    }
}
if ( ! function_exists( 'yith_wcevti_count_service' ) ) {
    /** Ask for the service pass by param and if have some condition add the sum value to especific part to array returned.
     * @param $product_id
     * @param $service
     * @param $count_product_service
     * @return mixed
     */
    function yith_wcevti_count_service($product_id, $service, $count_product_service, $quantity = 1)
    {
        $label = $service['_label'];
        $add_count = false;

        if (!empty($label)) {
            switch ($service['_type']) {
                case 'select':
                    $add_count = true;
                    break;
                case 'checkbox':
                    if (isset($service['_value'])) {
                        if ('on' == $service['_value']) {
                            $add_count = true;
                        }
                    }
                    break;
            }

            if ($add_count) {

                if (isset($count_product_service[$product_id][$label])) {
                    $count_product_service[$product_id][$label]['_value'] = $count_product_service[$product_id][$label]['_value'] + $quantity;
                } else {
                    $count_product_service[$product_id][$label] = array(
                        '_type' => $service['_type'],
                        '_value' => $quantity
                    );
                    if ('select' == $service['_type'] & !isset($service['_value'])) {
                        $count_product_service[$product_id][$label]['_simple'] = 'on';
                    }
                }
            }
        }
        return $count_product_service;
    }
}
if ( ! function_exists( 'yith_wcevti_exist_service_cart' ) ) {
    /**
     * Check if service with expecific value exist on the cart...
     * @param $product_id the product id to check service
     * @param $service to check
     * @return true or false
     */
    function yith_wcevti_exist_service_cart($product_id, $service)
    {
        $exist = false;

        $cart_contents = WC()->cart->cart_contents;
        if (!empty($cart_contents)) {
            foreach ($cart_contents as $cart_item) {
                if ($product_id == $cart_item['product_id']) {
                    if (isset($cart_item['_field_service']['_services'])) {
                        if (is_array($cart_item['_field_service']['_services'])) {
                            foreach ($cart_item['_field_service']['_services'] as $i_service) {
                                if ('select' == $i_service['_type'] && $service['_label'] == $i_service['_label']) {
                                    $label = sanitize_title($service['_label']);
                                    if (isset($service['_value'][$label]) && $service['_value'][$label] == $i_service['_value'][$label]) {
                                        $exist = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $exist;
    }
}
if ( ! function_exists( 'yith_wcevti_check_current_stock_service' ) ) {
    /**
     * Check the stock limit and compare if is greater than services what we want add.
     * @param $post_id
     * @param $label
     * @param $value
     * @param $check_stock
     * @return string
     */
    function yith_wcevti_check_current_stock_service($post_id, $label, $service, $check_stock)
    {
        global $wpdb;
        $value = $service['_value'];
        switch ($service['_type']) {
            case 'select':
                if (!isset($service['_simple'])) {
                    //Get the current number services buyed...
                    $querycount = 'select count(meta_value) from ' . $wpdb->postmeta .
                        ' where meta_key like "_service_' . $label . '_%range"
                    and post_id=' . $post_id;
                    $count = $wpdb->get_var($querycount);
                    //Add to number service what we want add.
                    $value += $count;
                } else {
                    //Get the number of service buyed...
                    $querycount = 'select meta_value from ' . $wpdb->postmeta .
                        ' where meta_key like "_service_' . $label . '_%range"
                                     and post_id =' . $post_id;
                    $count = $wpdb->get_var($querycount);

                    $value += $count;
                }
                break;
            case 'checkbox':
                //Get the number of service buyed...
                $querycount = 'select meta_value from ' . $wpdb->postmeta .
                    ' where meta_key like "_service_' . $label . '_%range"
                                     and post_id =' . $post_id;
                $count = $wpdb->get_var($querycount);

                $value += $count;
                break;
        }
        //Get the limit stock define by admin...
        $stock = get_post_meta($post_id, '_service_' . $label . '_stock', true);
        //Check if greater than services number what we want to add.
        if ($stock < $value && !empty($stock)) {
            $check_stock = 'out_range';
        }

        return $check_stock;
    }
}
if ( ! function_exists( 'yith_wcevti_get_orders_from_product' ) ) {
    /** Get all orders filtered by product. After count the numbers of tickets from each order.
     *  Some users can have diferents orders from the same tickets. This is a reason to loop our return table,
     *  to sum the current tickets purchased with the current order on loop.
     * @param $id_product
     * @return array
     */
    function yith_wcevti_get_orders_from_product($id_product)
    {
        global $wpdb;

        $query = 'select oi.* from ' . $wpdb->prefix . 'woocommerce_order_itemmeta oim' .
            ' left join ' . $wpdb->prefix . 'woocommerce_order_items oi' .
            ' on  oim.order_item_id = oi.order_item_id' .
            ' where meta_key = "_product_id" and meta_value = %d';

        $order_items = $wpdb->get_results($wpdb->prepare($query, $id_product));

        $purchased_data = array();
        foreach ($order_items as $item) {
            $customer = get_post_meta($item->order_id, '_customer_user', true);
            $customer_user = $customer ? get_userdata($customer) : false;
            if (!$customer_user) {
                $order = wc_get_order($item->order_id);
                $customer_user = new WP_User();
                $customer_user->data->ID = 0;
                $customer_user->data->user_nicename = yit_get_prop($order, 'billing_first_name');
                $customer_user->data->display_name = yit_get_prop($order, 'billing_first_name');
            }
            if ($customer_user && !isset($purchased_data[$customer_user->data->user_nicename])) {
                $purchased_data[$customer_user->data->user_nicename] = array(
                    'display_name' => $customer_user->data->display_name,
                    'avatar' => get_avatar($customer_user->data->ID),
                    'purchased_tickets' => 1
                );
            } elseif ($customer_user) {
                $purchased_data[$customer_user->data->user_nicename]['purchased_tickets']++;
            }
        }

        return $purchased_data;
    }
}
if ( ! function_exists( 'yith_wecvti_get_reduced_price' ) ) {
    function yith_wecvti_get_reduced_price($product_id)
    {
        $to_subtract = 0;

        $product = wc_get_product($product_id);
        $reduced_price_data = yit_get_prop($product, '_reduce_ticket', true);
        $price_product = $product->get_price();

        if ('fixed' == $reduced_price_data['_event_type']) {
            $to_subtract = $reduced_price_data['_price_fixed'];
        } elseif ('percentage' == $reduced_price_data['_event_type']) {
            $to_subtract = ($price_product * $reduced_price_data['_price_relative']) / 100;
        }


        return $to_subtract;
    }
}

function yith_wcevti_before_cart_service_validation($product_id, $services_customer){
    $can_add_to_cart = true;
    $count_services = yith_wcevti_count_services_product($product_id, $services_customer);
    foreach ($services_customer as $service_customer){
        foreach ($service_customer as $service){
            $check_stock = '';
            if(isset($count_services[$product_id][$service['_label']])) {
                $check_stock = yith_wcevti_check_current_stock_service($product_id, $service['_label'], $count_services[$product_id][$service['_label']], '');
            }
            if('out_range' != $check_stock| 'sold' != $check_stock){
                $check_stock = yith_wcevti_check_service_sold($product_id, $service, $check_stock);
            }

            switch ($check_stock){
                case 'sold':
                    $can_add_to_cart = false;
                    wc_add_notice($service['_label'] .' :' . __(' Service selected have been already bought!', 'yith-event-tickets-for-woocommerce'), 'error');
                    break;
                case  'out_range':
                    $can_add_to_cart = false;
                    wc_add_notice($service['_label'] .' :' . __(' Service its out ranged!', 'yith-event-tickets-for-woocommerce'), 'error');
                    break;
                default:
                    $can_add_to_cart = $can_add_to_cart;
                    break;
            }

        }
    }
    return $can_add_to_cart;
}


function yith_wcevti_checkout_service_validation(){
    $update = true;
    $cart = WC()->cart;
    $count_services = yith_wcevti_count_services_cart();
    $check_service = 'free';
    foreach ($cart->cart_contents as $cart_item) {
        if (isset($cart_item['_field_service']) && isset($cart_item['_field_service']['_services']) & is_array($cart_item['_field_service']['_services'])) {
            foreach ($cart_item['_field_service']['_services'] as $service) {
                $product_id = $cart_item['product_id'];
                $label = $service['_label'];
                if(isset($count_services[$product_id][$label])){
                    $count_service_item = $count_services[$product_id][$label];
                    $check_service = yith_wcevti_check_current_stock_service($product_id, $label, $count_service_item, $check_service );
                }
                if('out_range' != $check_service | 'sold' != $check_service){
                    $check_service = yith_wcevti_check_service_sold($cart_item['product_id'], $service, $check_service);
                } else {
                    break;
                }
            }
        }
    }

    switch ($check_service){
        case 'sold':
            $update = false;
            wc_add_notice(__('Some of the selected services have been already bought!', 'yith-event-tickets-for-woocommerce'), 'error');
            break;
        case  'out_range':
            $update = false;
            wc_add_notice(__('You are trying to add more services than available!', 'yith-event-tickets-for-woocommerce'), 'error');
            break;
        default:
            $update = true;
            break;
    }

    return $update;
}

if ( ! function_exists( 'yith_wcevti_get_services' ) ) {
    function yith_wcevti_get_services($post)
    {
        $post_meta = get_post_meta($post->ID, '', true);

        $services = array();
        foreach ($post_meta as $key => $meta){
            if (preg_match('/service_/i', $key)) {
                $label = str_replace( array( 'service_' ), '', $key );
                $value = $meta[0];

                $services[] = array(
                    $label => $value
                );
            }
        }
        return $services;
    }
}

add_filter('yith_wcevti_set_custom_mail_args', 'set_services_location_mail_args', 10, 2);
if ( ! function_exists( 'set_services_location_mail_args' ) ) {
    function set_services_location_mail_args($args, $post_meta)
    {
        $services = array();
        foreach ($post_meta as $key => $meta){
            if (preg_match('/service_/i', $key)) {
                $label = str_replace( array( 'service_' ), '', $key );
                $value = $meta[0];

                $services[] = array(
                    $label => $value
                );
            }
        }
        $args['services'] = $services;

        $args['location'] = get_post_meta($post_meta['wc_event_id'][0], '_direction_event', true);

        return $args;
    }
}
