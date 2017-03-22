<?php
/**
 * @var $row_index int Service unique id
 */

$label = '';

if ( isset( $select_item['_label'] ) ) {
	$label = $select_item['_label'];
}
if ( isset( $service_label ) ) {
	$label = $service_label;
}
?>

<!-- Option Row -->
<tr>
    <td class="drag-icon">
        <i class="dashicons dashicons-move"></i>
    </td>
    <td class="option-label">
        <input type="text" class="yith-wceti-select-service-label"
               name="_services[<?php echo $row_index ?>][_select][<?php echo $index ?>][_label]"
               id="_services_<?php echo $row_index ?>_select_<?php echo $index ?>_label"
               value="<?php echo $label ?>"
               placeholder="<?php _e( 'Option Name', 'yith-event-tickets-for-woocommerce' ); ?>">
    </td>
    <td class="option-stock">
        <input type="number" class="yith-wceti-select-service-stock" style=""
               name="_services[<?php echo $row_index ?>][_select][<?php echo $index ?>][_stock]"
               id="_services_<?php echo $row_index ?>_select_<?php echo $index ?>_stock"
               value="<?php echo isset( $select_item['_stock'] ) ? $select_item['_stock'] : '' ?>"
               placeholder="0">
    </td>
    <td class="option-overcharge">
        <input type="number" class="select_item_overcharge"
               name="_services[<?php echo $row_index ?>][_select][<?php echo $index ?>][_overcharge]"
               id="_services_<?php echo $row_index ?>_select_<?php echo $index ?>_overcharge"
               value="<?php echo isset( $select_item['_overcharge'] ) ? $select_item['_overcharge'] : '' ?>"
               placeholder="0">
        <span class="currency-symbol"><?php echo '(' . get_woocommerce_currency_symbol() . ')' ?></span>
    </td>
    <td class="option-range-from">
        <input type="number" class="select_item_range_from" style=""
               name="_services[<?php echo $row_index ?>][_select][<?php echo $index ?>][_range_from]"
               id="_services_<?php echo $row_index ?>_select_<?php echo $index ?>_range_from"
               value="<?php echo isset( $select_item['_range_from'] ) ? $select_item['_range_from'] : '' ?>"
               placeholder="0">
    </td>
    <td class="option-range-to">
        <input type="number" class="select_item_range_to" style=""
               name="_services[<?php echo $row_index ?>][_select][<?php echo $index ?>][_range_to]"
               id="_services_<?php echo $row_index ?>_select_<?php echo $index ?>_range_to"
               value="<?php echo isset( $select_item['_range_to'] ) ? $select_item['_range_to'] : '' ?>"
               placeholder="0">
    </td>
    <td class="option-actions">
        <a href="#" class="button remove_service_item delete"><?php _e( 'Remove', 'yith-event-tickets-for-woocommerce' ); ?></a>
    </td>

</tr>