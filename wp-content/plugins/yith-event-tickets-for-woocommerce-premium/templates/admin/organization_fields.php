<div id="organization_product_data" class="panel woocommerce_options_panel hide">
    <div class="yith_evti_organization_template_title">
        <h2><strong class="attribute_name"><?php echo __('Organizers and Assistants tab', 'yith-event-tickets-for-woocommerce')?></strong></h2>
    </div>
    <div class="organization_panel">
        <p class="form-field _display_tab_field ">
            <label for="_display_tab_assistants"><?php echo __('Display Assistants tab', 'yith-event-tickets-for-woocommerce') ?></label>
            <input  id="_display_tab_assistants"
                    type="checkbox"
                    class="yith-wceti-organizers"
                    style="" name="_organization[_tab_assistants]"
                <?php if(isset($organization['tab_assistants'])){if('on' == $organization['tab_assistants']){ echo 'checked';}}?>
            >
        </p>
        <p class="form-field _display_organizers_field " hidden>
            <label for="_display_organizers"><?php echo __('Show Organizers', 'yith-event-tickets-for-woocommerce') ?></label>
            <input  id="_display_organizers"
                    type="checkbox"
                    class="yith-wceti-organizers"
                    style="" name="_organization[_display]"
                <?php if(isset($organization['display'])){if('on' == $organization['display']){ echo 'checked';}}?>
            >
            <span class="description"><?php echo __('Display Organizers on the Assistants tab', 'yith-event-tickets-for-woocommerce');?></span>

        </p>
        <hr>
        <p class="form-field _organization_values_field ">
            <label for="_organization_values_field"><?php echo __('Add organizer' , 'yith-event-tickets-for-woocommerce');?></label>
            <?php
            $args = array(
                'class'             => 'wc-customer-search yith-wceti-service-type',
                'id'                => '_organization_values_field',
                'name'              => '_organization[_values]',
                'data-allow_clear'  => false,
                'data-selected'     => $organizer_selected,
                'data-multiple'     => true,
                'value'             => isset($organization['values']) ? $organization['values'] : '',
                'style'             => 'width: auto;'
            );
            yit_add_select2_fields($args);
            ?>
            <span class="description"><?php echo __('Find and add users that will contribute to the event organization',
                    'yith-event-tickets-for-woocommerce');?></span>
        </p>
    </div>
</div>