<div id="content_services">
    <?php
    if(!empty($services)){
        if(is_array($services)){
            foreach ($services as $service_item){
                if(isset($service_item) & !empty($service_item)){
                    $label = key($service_item);
                    $field = $service_item[$label];
                    ?>
                    <p class="form-field">
                        <label for="_content_service_<?php echo esc_html($label)?>"><?php echo esc_html($label)?>: </label>
                        <span id="_content_service_<?php echo esc_html($label)?>"><?php echo esc_html($field)?></span>
                    </p>
                    <?php
                }
            }
        }
    }
    ?>
</div>