<div class="yith_evti_mail_template_container">
    <div class="yith_evti_mail_template_panel">
        <div class="yith_evti_mail_template_content">
            <div id="content_main"><div class="container-ticket">
                <div id="content_title">
                    <p class="styles-grales-titulos" style="text-transform: initial">Course/Event</p>
                    <p class="" style="font-size: 14px; font-weight: bold; margin: 5px 0px 5px 20px; text-transform: capitalize;"><?php echo $post->post_title; ?></p>
                </div>
                <div id="content_fields">
                    <?php
                    if(isset($fields)){
                        if(is_array($fields)) {
                            foreach ($fields as $field) {
                                if (isset($field) & !empty($field)) {
                                    $label = key($field);
                                    $field = $field[$label];
                                    ?>
                                    <p class="form-field">
                                        <label
                                            for="_ticket_field_<?php echo esc_html($label) ?>"><?php echo esc_html($label) ?>
                                            : </label>
                                        <span
                                            id="_ticket_field_<?php echo esc_html($label) ?>"><?php echo esc_html($field) ?> </span>
                                    </p>
                                    <?php
                                }
                            }
                        }
                    }
                    ?>
                </div>
                <?php
                    do_action('yith_wcevti_default_html_preview_end_fields', $post);
                if(!empty($date['message_start']) & !empty($date['message_end'])){
                    ?>
                    <div id="content_date"> <br>
                        <p class="styles-grales-titulos">
                            <?php echo $date['message_start'];?>
                        </p> <br>
                        <p class="styles-grales-titulos">
                            <?php echo $date['message_end'];?>
                        </p>
                    </div>
                    <?php
                }
                $formated_price_service =  sprintf( get_woocommerce_price_format(),  get_woocommerce_currency_symbol() , $price );
                ?>
                <div id="content_price">
                    <p class="form-field">
                        <label for="_content_price"><b style="color: #d0b363; font-weight: bold;"><?php echo __('Price', 'yith-event-tickets-for-woocommerce'); ?>: </b></label>
                        <span id="_content_price"><b><?php echo $formated_price_service?></b></span>
                    </p>
                </div>
                <?php if(false != $barcode){
                ?>
                <div id="header_barcode">
                    <div class="barcode_container">
                        <?php echo $barcode_rendered;?>
                    </div>
                </div>
            <?php } ?>
            </div></div>
        </div>
    </div>
</div>