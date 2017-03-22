<div class="service_row">
    <?php
    if(isset($services) & is_array($services)) {
        foreach ($services as $index => $service) {
            $label = sanitize_title($service['_label']);
            switch ($service['_type']) {

                case 'checkbox':
                    $formated_price_service =  sprintf( get_woocommerce_price_format(),  get_woocommerce_currency_symbol() , $service['_item_overcharge'] );
                    ?>
                    <hr>
                    <div class="service_panel" data-price_service="0">
                        <p class="form-field service_item _services_customer_<?php echo $label; ?>_field">
                            <input type="hidden" style=""
                                   name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_type]"
                                   value="checkbox">
                            <input type="hidden" style=""
                                   name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                                   value="<?php echo $service['_key']; ?>">
                            <input type="hidden" style=""
                                   name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
                                   value="<?php echo $service['_label']; ?>">
                            <input type="checkbox" class="checkbox" style=""
                                   name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_value]"
                                   data-overcharge="<?php if (!empty($service['_item_overcharge'])) {
                                       echo $service['_item_overcharge'];
                                   } else {
                                       echo '0';
                                   } ?>" id="_services_customer_<?php echo $label; ?>">
                            <label
                                for="_services_customer_<?php echo $label; ?>"><?php echo esc_html($service['_label']) . ' ( ' . $formated_price_service . ' ' . __('overcharge', 'yith-event-tickets-for-woocommerce') . ' )'; ?></label>
                        </p>
                    </div>
                    <?php
                    break;
                case 'select':
                    ?>
                    <hr>
                    <div class="service_panel" data-price_service="0">
                        <p class="form-field service_item _services_customer_select_field">
                            <?php if(!empty($service['_label'])){?>
                                <label for="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"><?php echo $service['_label'];?></label>
                            <?php } ?>
                            <input type="hidden" style=""
                                   name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_type]"
                                   value="select">
                            <input type="hidden" style=""
                                   name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                                   value="<?php echo $service['_key'] ?>">
                            <select class="_select_item <?php if (isset($service['_required'])) {
                                if ('on' == $service['_required']) {
                                    echo 'required';
                                }
                            } ?>"
                                    name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
                                    id="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                                <?php if (isset($service['_required'])) {
                                if ('on' == $service['_required']) {
                                    echo 'required';
                                }
                            } ?>>
                                <option value="" data-overcharge="0"
                                        default><?php echo __('Choose an option', 'yith-event-tickets-for-woocommerce');
                                    if (isset($service['_required'])) {
                                        if ('on' == $service['_required']) {
                                            echo '*';
                                        }
                                    } ?></option>
                                <?php
                                foreach ($service['_select'] as $select) {
                                    $formated_price_service =  sprintf( get_woocommerce_price_format(),  get_woocommerce_currency_symbol() , $select['_overcharge'] );
                                    $overcharge_text = ' (' . __('Free', 'yith-event-tickets-for-woocommerce') . ')';
                                    $overcharge_text = (0 < $select['_overcharge']) ? ' (+ '.  $formated_price_service .')' : $overcharge_text;

                                    ?>
                                    <option name="<?php echo sanitize_title($select['_label']); ?>" value="<?php echo $select['_label']; ?>"
                                            data-overcharge="<?php echo esc_attr($select['_overcharge']) ?>"><?php echo esc_html($select['_label']) . $overcharge_text  ?></option>
                                    <?php
                                } ?>
                            </select>
                        </p>
                        <?php
                        foreach ($service['_select'] as $select) {

                            if('' != $select['_range_from'] & '' != $select['_range_to']) {
                                $formated_price_service =  sprintf( get_woocommerce_price_format(),  get_woocommerce_currency_symbol() , $select['_overcharge'] );
                                $overcharge_text = ' (' . __('Free', 'yith-event-tickets-for-woocommerce') . ')';
                                $overcharge_text = (0 < $select['_overcharge']) ? ' (+'. $formated_price_service .')' : $overcharge_text;
                                ?>
                                <p class="form-field _services_customer_<?php echo sanitize_title($select['_label']); ?>_field service_range">
                                    <label for="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"><?php echo __('Choose a number for', 'yith-event-tickets-for-woocommerce'). ' ' . esc_html($select['_label']) . $overcharge_text;?></label>
                                    <input type="hidden" style=""
                                           name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_type]"
                                           value="select">
                                    <select class="select_range <?php if (isset($service['_required'])) {
                                        if ('on' == $service['_required']) {
                                            echo 'required';
                                        }
                                    } ?>"
                                            name="_services_customer[<?php echo $row ?>][<?php echo $index ?>][_value][<?php echo sanitize_title($select['_label']); ?>]" >
                                        <option
                                            value=""><?php echo __('Choose a number', 'yith-event-tickets-for-woocommerce');
                                            if (isset($service['_required'])) {
                                                if ('on' == $service['_required']) {
                                                    echo '*';
                                                }
                                            } ?></option>
                                        <?php

                                        $service_aux['_type'] = $service['_type'];
                                        $service_aux['_label'] = $select['_label'];

                                        $i = $select['_range_from'];
                                        for ($i; $i <= $select['_range_to']; $i++) {
                                            $service_aux['_value'][sanitize_title($select['_label'])] = $i;

                                            $service_sold  = yith_wcevti_check_service_sold($product_id, $service_aux, false);
                                            ?>
                                            <option value="<?php echo $i ?>" <?php if('sold' == $service_sold){echo 'disabled';}?>><?php echo $i ?> <?php if('sold' == $service_sold){echo __('(Sold)', 'yith-event-tickets-for-woocommerce');}?></option>
                                            <?php
                                        } ?>
                                    </select>
                                </p>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php
                    break;
            }
        }
    }
    if(isset($reduce_ticket['_enable'])) {
        $value = 0;

        if('fixed' == $reduce_ticket['_event_type']){
            $value = $reduce_ticket['_price_fixed'];
        } elseif('percentage' == $reduce_ticket['_event_type']){
            $price = isset($price) ? $price : 0;
            $value = ($price * $reduce_ticket['_price_relative'])/100;
        }
        $formated_price_reduce =  sprintf( get_woocommerce_price_format(),  get_woocommerce_currency_symbol() , $value );
        ?>
        <div class="service_panel" data-price_service="0">
            <span><?php echo __('Reduced priced', 'yith-event-tickets-for-woocommerce').' ( '. $formated_price_reduce. ' '.__('discount', 'yith-event-tickets-for-woocommerce').' ): ' ?></span>
            <p class="form-field service_item _services_customer_price-reduced_field">
                <input type="hidden" style="" name="_reduced_price[<?php echo $row ?>][_type]"
                       value="<?php echo $reduce_ticket['_event_type']; ?>">
                <input type="hidden" style="" name="_reduced_price[<?php echo $row ?>][_key]"
                       value="reduced_price">
                <input type="hidden" style="" name="_reduced_price[<?php echo $row ?>][_label]"
                       value="<?php echo __('Reduced price', 'yith-event-tickets-for-woocommerce') ?>">
                <input
                    id="_services_customer_reduced-price"
                    type="checkbox"
                    class="checkbox"
                    name="_reduced_price[<?php echo $row ?>][_value]"
                    data-overcharge="
                                    <?php
                    echo '-'. $value;
                    ?>">
                <label
                    for="_services_customer_reduced-price"><?php echo esc_html($reduce_ticket['_description']); ?></label>
            </p>
        </div>
        <?php
    }
    ?>
</div>