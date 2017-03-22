<div id="map_product_data" class="panel woocommerce_options_panel hidden">
    <?php
    $enable_location = get_option('yith_wcte_enable_location');
    $api_key = get_option('yith_wcte_api_key_gmaps');
    if($enable_location == 'yes' && !empty($api_key)){

        woocommerce_wp_text_input(
            array(
                'id'          => '_direction_event_field',
                'class'       => 'yith_wcevti_direction_event',
                'label'       => __( 'Event direction', 'yith-event-tickets-for-woocommerce' ),
                'placeholder' => __( 'Event address here', 'yith-event-tickets-for-woocommerce' ),
                'desc_tip'    => 'true',
                'description' => __( 'For physical events, enter your event address here. The location will be displayed on Google Maps',
                    'yith-event-tickets-for-woocommerce' ),
                'value'       => get_post_meta( $thepostid, '_direction_event', true )
            )
        );
        ?>
        <p class="form-field _display_map_tab_field_field">
            <label for="_display_map_tab">
                <?php echo __( 'Display location tab', 'yith-event-tickets-for-woocommerce' );?>
            </label>
            <input id="_display_map_tab" type="checkbox" class="yith-wceti-map-tab" style="" name="_map_tab_display"
                <?php if('on' == get_post_meta($thepostid, '_map_tab_display', true)){ echo 'checked';}  else {echo 'disabled';}?>>
        </p>
        <div class="yith_wcevti_address_map_div">
            <div id="_map_event_ticket" class="yith_wcevti_address_map">

            </div>

        </div>
        <?php
        woocommerce_wp_hidden_input(
            array(
                'id'          => '_latitude_event_field',
                'class'       => 'yith_wcevti_latitude_event',
                'value'       => get_post_meta( $thepostid, '_latitude_event', true )
            )
        );

        woocommerce_wp_hidden_input(
            array(
                'id'          => '_longitude_event_field',
                'class'       => 'yith_wcevti_longitude_event',
                'value'       => get_post_meta( $thepostid, '_longitude_event', true )
            )
        );

        ?>
    <?php } else {
        ?>
        <a href="<?php echo admin_url() . 'admin.php?page=yith_wcevti_panel';?>" target="_blank"><?php echo __('To display map you must add Google Maps API KEY V3', 'yith-event-tickets-for-woocommerce');?></a>
        <?php
    }
    ?>
</div>
<?php
$services = get_post_meta($thepostid, '_services', true);
?>
    <div id="services_product_data" class="panel woocommerce_options_panel hidden">
        <div class="yith_evti_aditional_service_title">
            <h2><strong class="attribute_name"><?php echo __('Add services to this ticket', 'yith-event-tickets-for-woocommerce')?></strong></h2>
        <!-- <button id="_show_service_type_selector" class="button button-primary"><?php echo __('Add service', 'yith-event-tickets-for-woocommerce') ?></button> -->
        <button id="_add_service_row" class="add_service_button button button-primary"><?php echo __('Add service', 'yith-event-tickets-for-woocommerce') ?></button>

        <div id="_service_type_selector_container">
            <select name="_service_type_selector" id="_service_type_selector">
                <option value="checkbox"><?php _e( 'Checkbox', 'yith-event-tickets-for-woocommerce' ) ?></option>
                <option value="select"><?php _e( 'Select', 'yith-event-tickets-for-woocommerce' ) ?></option>
            </select>
            <button id="_add_service_row" class="add_service_button button button-primary"><?php _e( 'Continue', 'yith-event-tickets-for-woocommerce' ) ?></button>
            <button id="_cancel_add_service" class="button button-secondary"><?php _e( 'Cancel', 'yith-event-tickets-for-woocommerce' ) ?></button>
        </div>
    </div>

        <div class="yith_evti_add_product_data">

            <div class="services_panel" >
                <?php
                if(!empty($services)) {

                    foreach ($services as $index => $service_item) {
                        if(isset($service_item)) {
                            $args = array(
                                'index' => $index,
                                'service' => $service_item
                            );

                            wc_get_template('admin/service_row.php', $args, '', YITH_WCEVTI_TEMPLATE_PATH);
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php
$organization = get_post_meta($thepostid, '_organization', true);

$values_selected = isset($organization['values']) ? is_array($organization['values']) ? $organization['values'] : explode( ',', $organization['values'] ) : array();

$organizer_selected = array();
foreach ($values_selected as $value){
    $user = get_user_by('id', $value);
    $organizer_selected[$value] = $user ? $user->data->display_name . ' (#'. $value .' - ' . $user->data->user_email . ')' : '';
}

yith_wcevti_get_template('organization_fields', array('organization' => $organization, 'organizer_selected' => $organizer_selected), 'admin');
?>