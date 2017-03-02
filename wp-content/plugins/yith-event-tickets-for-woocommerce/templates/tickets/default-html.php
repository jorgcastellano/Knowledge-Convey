<div class="yith_evti_mail_template_container">
    <div class="yith_evti_mail_template_panel">
        <div class="yith_evti_mail_template_content">
            <?php
            if($content_image){
                ?>
                <!--<img class="content_image" src="<?php echo $content_image[0]; ?>" width="<?php if(isset($content_image[1])){echo $content_image[1];} ?>" height="<?php if(isset($content_image[2])){ echo $content_image[2];} ?>">-->
            <?php }
            ?>
            <div id="content_main">
                <div id="content_title">
                    <h2><?php echo $post->post_title; ?></h2>
                </div>
                <div id="content_fields">
                    <?php
                    if(isset($fields)){
                        if(is_array($fields)){
                            foreach ($fields as $field){
                                if(isset($field) & !empty($field)){
                                    $label = key($field);
                                    $field = $field[$label];
                                    ?>
                                    <p class="form-field">
                                        <label for="_ticket_field_<?php echo esc_html($label)?>"><?php echo esc_html($label)?>: </label>
                                        <span id="_ticket_field_<?php echo esc_html($label)?>"><?php echo esc_html($field)?> </span>
                                    </p>
                                    <?php
                                }
                            }
                        }
                    }
                    ?>
                </div>
                <?php
                    do_action('yith_wcevti_default_html_end_fields', $post);
                ?>
                <div id="content_price">
                    <p class="form-field">
                        <label for="_content_price"><?php echo __('Price', 'yith-event-tickets-for-woocommerce'); ?>: </label>
                        <span id="_content_price"><?php echo esc_html($price)?></span>
                    </p>
                </div>
                <div id="content_aditional">
                    <p>
                        <?php echo esc_html($mail_template['data']['aditional_text'])?>
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
            </div>
        </div>
        <div class="yith_evti_mail_template_footer">
            <table class="footer_table">
                <tr>
                    <td>
                        <div class="footer_logo">
                            <?php
                            if($footer_image){
                                ?>
                                <img class="footer_image" src="<?php echo $footer_image[0]; ?>" width="<?php if(isset($footer_image[1])){ echo $footer_image[1];} ?>" height="<?php if (isset($footer_image[2])){ echo $footer_image[2];} ?>">
                            <?php }
                            ?>
                        </div>
                    </td>
                    <td>
                        <div class="footer_text">
                            <p>
                                <?php echo get_home_url();?>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
