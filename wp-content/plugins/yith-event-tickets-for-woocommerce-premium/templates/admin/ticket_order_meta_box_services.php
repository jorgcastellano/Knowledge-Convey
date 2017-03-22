<div class="ticket_data_column">
    <h3><?php _e( 'Services details', 'yith-event-tickets-for-woocommerce' ); ?></h3>
    <?php
    if(!empty($services)){
        if(is_array($services)) {
            foreach ($services as $service_item) {
                $label = key($service_item);
                $field = $service_item[$label];
                ?>
                <p class="form-field ticket_service">
                    <label for="_ticket_service_<?php echo esc_html($label) ?>"><?php echo esc_html($label) ?>: </label>
                    <span id="_ticket_service_<?php echo esc_html($label) ?>"
                          class="yith_wcevti_meta_span"><?php echo esc_html($field) ?></span>
                </p>

                <?php
            }
        }
    }
    ?>
</div>