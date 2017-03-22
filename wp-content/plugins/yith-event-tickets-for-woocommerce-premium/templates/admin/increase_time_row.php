<tr class="yith_evti_increase_by yith_evti_time_row">
    <td class="drag-icon">
        <i class="dashicons dashicons-move"></i>
    </td>

    <td colspan="4">
        <div class="option-threshold">
            <input type="number" class="yith-wceti-time_treshold"
                   name="_increase_by_time[<?php echo $index ?>][_threshold]"
                   id="_increase_by_time_<?php echo $index ?>_threshold"
                   value="<?php echo isset( $increase['_threshold'] ) ? $increase['_threshold'] : '' ?>">
        </div>

        <div class="option-increase-type">
            <select id="_increase_by_time_<?php echo $index ?>_increase_ticket_event_type"
                    name="_increase_by_time[<?php echo $index ?>][_increase_ticket_event_type]"
                    class="yith-wceti-increase-type">
                <option value="fixed" <?php selected( isset( $increase['_increase_ticket_event_type'] ) && $increase['_increase_ticket_event_type'] == 'fixed' ) ?> ><?php _e( 'Fixed', 'yith-event-tickets-for-woocommerce' ) ?> </option>
                <option value="percentage" <?php selected( isset( $increase['_increase_ticket_event_type'] ) && $increase['_increase_ticket_event_type'] == 'percentage' ) ?> ><?php _e( 'Percentage', 'yith-event-tickets-for-woocommerce' ) ?></option>
            </select>
        </div>

        <div class="option-increase-value">
            <div class="yith-wceti-increase-fixed">
                <input type="text" class="wc_input_price"
                       name="_increase_by_time[<?php echo $index ?>][_increase_fixed_amount]"
                       id="_increase_by_time_<?php echo $index ?>_increase_fixed_amount"
                       value="<?php echo isset( $increase['_increase_fixed_amount'] ) ? $increase['_increase_fixed_amount'] : '' ?>">
                <span class="currency-symbol">(<?php echo get_woocommerce_currency_symbol() ?>)</span>
            </div>

            <div class="yith-wceti-increase-percentage">
                <input type="number"
                       name="_increase_by_time[<?php echo $index ?>][_increase_percentage_amount]"
                       id="_increase_by_time_<?php echo $index ?>_increase_percentage_amount"
                       value="<?php echo isset( $increase['_increase_percentage_amount'] ) ? $increase['_increase_percentage_amount'] : '' ?>">
                <span class="percentage-symbol">(%)</span>
            </div>
        </div>

        <div class="option-actions">
            <button class="button remove_increase_item"><?php _e( 'Remove', 'yith-event-tickets-for-woocommerce' ) ?></button>
        </div>

        <div class="option-description">
            <label for="_increase_by_time_<?php echo $index ?>_increase_description"><?php echo __( 'Description', 'yith-event-tickets-for-woocommerce' ); ?></label>
            <textarea class="yith-wceti-increase-description" style=""
                      name="_increase_by_time[<?php echo $index ?>][_increase_description]"
                      id="_increase_by_time_<?php echo $index ?>_increase_description"><?php echo isset( $increase['_increase_description'] ) ? $increase['_increase_description'] : '' ?></textarea>
        </div>
    </td>
</tr>
