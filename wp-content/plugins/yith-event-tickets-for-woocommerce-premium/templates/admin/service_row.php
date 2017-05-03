<?php
    $num_select_rows = 0;
    $num_checkbox_rows = 0;

    if(isset($service['_select'])){
	    $num_select_rows = count($service['_select']);
    }

    if(isset($service['_checkbox'])){
	    $num_checkbox_rows = count($service['_checkbox']);
    }
?>

<!-- Service Row -->
<div class="yith_wcevti_service_row closed" data-index="<?php echo $index?>">

    <!-- Service Handle -->
    <div class="yith-evti-service-handle">
        <div class="drag-icon"><i class="dashicons dashicons-move"></i></div>
        <div class="title">
            <h3><?php echo ! empty( $service['_label'] ) ? esc_html( $service['_label'] )  : '-' ?></h3>
            <span class="service-type">(
                <?php _e( 'Type', 'yith-event-tickets-for-woocommerce' )?> -
                <span class="service-type-checkbox" <?php echo ( $service['_type'] != 'checkbox' ) ? 'style="display:none"' : '' ?> ><?php _e( 'Checkbox', 'yith-event-tickets-for-woocommerce' ) ?></span>
                <span class="service-type-select" <?php echo ( $service['_type'] != 'select' ) ? 'style="display:none"' : '' ?> ><?php _e( 'Select', 'yith-event-tickets-for-woocommerce' ) ?></span>
            )</span>
        </div>
        <div class="remove">
            <button class="remove_service_row button button-secondary"><?php _e( 'Remove', 'yith-event-tickets-for-woocommerce' ) ?></button>
        </div>
        <div class="toggle"><i class="dashicons"></i></div>
    </div>

    <!-- Service Panel -->
    <div class="yith-evti-service-panel">

        <!-- Service Heading -->
        <div class="service_main_panel_row">
            <div class="form-field _services_<?php echo $index?>_label_field service_label">
                <input type="hidden"  style="" name="_services[<?php echo $index?>][_key]" value="service_<?php echo $index?>">

                <!-- Service Type -->
                <div class="service-type-container">
                    <label for="_services_<?php echo $index?>_type"><?php echo __('Service type', 'yith-event-tickets-for-woocommerce'); ?></label>
                    <select name="_services[<?php echo $index ?>][_type]" id="_services_<?php echo $index ?>_type">
                        <option value="checkbox" <?php selected( ! isset( $service['_type'] ) || 'checkbox' == $service['_type'] ) ?> ><?php _e( 'Checkbox', 'yith-event-tickets-for-woocommerce' )?></option>
                        <option value="select" <?php selected( 'select' == $service['_type'] ) ?> ><?php _e( 'Select', 'yith-event-tickets-for-woocommerce' )?></option>
                    </select>
                </div>

                <!-- Service Label -->
                <div class="service-label-container">
                    <label for="_services_<?php echo $index?>_label"><?php echo __('Service name', 'yith-event-tickets-for-woocommerce'); ?></label>
                    <input type="text" class="yith-wceti-service-label" name="_services[<?php echo $index?>][_label]" id="_services_<?php echo $index?>_label" value="<?php echo ! empty( $service['_label'] ) ? esc_html( $service['_label'] )  : ''?>" placeholder="<?php echo __('Service name here', 'yith-event-tickets-for-woocommerce');?>">
                </div>

                <div class="service-checkbox-heading">
                    <!-- Service Checkbox Stock -->
                    <div class="service-stock-container">
                        <label for="_services_<?php echo $index?>_stock"><?php echo __('Service stock', 'yith-event-tickets-for-woocommerce'); ?></label>
                        <input type="number" class="yith-wceti-service-stock" name="_services[<?php echo $index?>][_stock]" id="_services_<?php echo $index?>_stock" value="<?php echo ! empty( $service['_stock'] ) ? esc_html( $service['_stock'] )  : ''?>" placeholder="0">
                    </div>

                    <!-- Service Checkbox Overcharge -->
                    <div class="service-overcharge-container">
                        <label for="_services_<?php echo $index?>_overcharge"><?php echo __('Service Surcharge', 'yith-event-tickets-for-woocommerce'); ?></label>
                        <input type="number" class="yith-wceti-service-overcharge" name="_services[<?php echo $index?>][_item_overcharge]" id="_services_<?php echo $index?>_overcharge" value="<?php echo ! empty( $service['_item_overcharge'] ) ? esc_html( $service['_item_overcharge'] )  : ''?>" placeholder="0">
                    </div>
                </div>

                <div class="service-select-heading">
                    <!-- Service Checkbox Stock -->
                    <div class="service-required-container">
                        <label for="_services_<?php echo $index?>_stock">
                            <input type="checkbox" class="yith-wceti-service-required" name="_services[<?php echo $index?>][_required]" id="_services_<?php echo $index?>_required" value="on" <?php checked( isset( $service['_required'] ) && 'on' == $service['_required'] ) ?> >
	                        <?php echo __('Service required', 'yith-event-tickets-for-woocommerce'); ?>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <!-- Service Options -->
        <div id="yith_wcevti_service_panel_<?php echo $index;?>" class="yith-evti-option-panel">

            <!-- Service Select Options -->
            <div class="yith_wcevti_service_select_panel" style="display:<?php echo ( 'select' == $service['_type'] ) ? 'block' : 'none' ?>" data-rows="<?php echo $num_select_rows ?>">

                <table class="service-options service-options-select wp-list-table widefat">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="option-label"><?php _e( 'Name', 'yith-event-tickets-for-woocommerce' ) ?></th>
                            <th class="option-stock"><?php _e( 'Stock', 'yith-event-tickets-for-woocommerce' ) ?></th>
                            <th class="option-overcharge"><?php _e( 'Surcharge', 'yith-event-tickets-for-woocommerce' ) ?></th>
                            <th class="option-range-from"><?php _e( 'Range - From', 'yith-event-tickets-for-woocommerce' ) ?></th>
                            <th class="option-range-to"><?php _e( 'Range - To', 'yith-event-tickets-for-woocommerce' ) ?></th>
                            <th class="option-actions"><?php _e( 'Actions', 'yith-event-tickets-for-woocommerce' ) ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if( isset( $service['_select'] ) ) {
                            foreach ( $service['_select'] as $row_index => $select_item ) {
                                $args = array(
                                    'row_index'   => $index,
                                    'index'       => $row_index,
                                    'select_item' => $select_item
                                );

                                wc_get_template( 'admin/select_service_row.php', $args, '', YITH_WCEVTI_TEMPLATE_PATH );

                            }
                        }
                        ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <button class="button add_select_button">
                                    <i class="dashicons dashicons-plus"></i>
                                    <?php _e('Add option', 'yith-event-tickets-for-woocommerce')?>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</div>