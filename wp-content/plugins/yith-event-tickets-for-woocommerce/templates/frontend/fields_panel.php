<div class="fields_panel" >
    <input type="hidden" class="yith_evti_event_title" name="_event_title" value="<?php echo $event_title?>">
    <?php

    YITH_Tickets_Frontend()->load_fields_event_action();

    ?>
</div>
<div class="yith_evti_total_price" data-current_price="<?php echo esc_attr($price); ?>">
    <span class="price">
        <span class="woocommerce-Price-amount yith_evti_amount_price amount"><?php echo $price; ?>

        </span>
        <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
    </span>
</div>