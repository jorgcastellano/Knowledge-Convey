<div class="field_row">
    <?php
    if(isset($fields) & is_array($fields)) {
        foreach ($fields as $index => $field) {
            $label = sanitize_title($field['_label']);

            switch ($field['_type']) {

                case 'text':
                    ?>
                    <p class="form-field _fields_customer_<?php echo $label; ?>_field "><label
                            for="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"><?php echo $field['_label']; ?><?php if (isset($field['_required'])) {
                                if ('on' == $field['_required']) {
                                    echo '*';
                                }
                            } ?></label>
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                               value="<?php echo $label; ?>">
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
                               value="<?php echo $field['_label']; ?>">
                        <input type="text" class="_field_item" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_value]"
                               id="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"
                               value="" placeholder="" <?php if (isset($field['_required'])) {
                            if ('on' == $field['_required']) {
                                echo 'required';
                            }
                        } ?>>
                    </p>
                    <?php
                    break;
                case 'textarea':
                    ?>
                    <p class="form-field field_item _fields_customer_<?php echo $label; ?>_field "><label
                            for="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"><?php echo $field['_label']; ?><?php if (isset($field['_required'])) {
                                if ('on' == $field['_required']) {
                                    echo '*';
                                }
                            } ?></label>
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                               value="<?php echo $label; ?>">
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
                               value="<?php echo $field['_label']; ?>">
                        <textarea class="_field_item" style=""
                                  name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_value]"
                                  id="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"
                                  placeholder="" <?php if (isset($field['_required'])) {
                            if ('on' == $field['_required']) {
                                echo 'required';
                            }
                        } ?>></textarea>
                    </p>
                    <?php
                    break;
                case 'email':
                    ?>
                    <p class="form-field field_item _fields_customer_<?php echo $label; ?>_field "><label
                            for="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"><?php echo $field['_label']; ?><?php if (isset($field['_required'])) {
                                if ('on' == $field['_required']) {
                                    echo '*';
                                }
                            } ?></label>
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                               value="<?php echo $label; ?>">
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
                               value="<?php echo $field['_label']; ?>">
                        <input type="email" class="regular-text ltr _field_item" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_value]"
                               id="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"
                               value="" placeholder="" <?php if (isset($field['_required'])) {
                            if ('on' == $field['_required']) {
                                echo 'required';
                            }
                        } ?>>
                    </p>
                    <?php
                    break;
                case 'number':
                    ?>
                    <p class="form-field field_item _fields_customer_<?php echo $label; ?>_field "><label
                            for="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"><?php echo $field['_label']; ?><?php if (isset($field['_required'])) {
                                if ('on' == $field['_required']) {
                                    echo '*';
                                }
                            } ?></label>
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                               value="<?php echo $label; ?>">
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
                               value="<?php echo $field['_label']; ?>">
                        <input type="number" class="_field_item" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_value]"
                               id="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"
                               value="" placeholder="" <?php if (isset($field['_required'])) {
                            if ('on' == $field['_required']) {
                                echo 'required';
                            }
                        } ?>>
                    </p>
                    <?php
                    break;
                case 'date':
                    ?>
                    <p class="form-field _fields_customer_<?php echo $label; ?>_field "><label
                            for="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"><?php echo $field['_label']; ?><?php if (isset($field['_required'])) {
                                if ('on' == $field['_required']) {
                                    echo '*';
                                }
                            } ?></label>
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                               value="<?php echo $label; ?>">
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
                               value="<?php echo $field['_label']; ?>">
                        <input type="text" class="_field_item _field_datepicker" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_value]"
                               id="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"
                               value="" placeholder="mm/dd/aaaa" <?php if (isset($field['_required'])) {
                            if ('on' == $field['_required']) {
                                echo 'required';
                            }
                        } ?>>
                    </p>
                    <?php
                    break;
                case 'check':
                    ?>
                    <p class="form-field field_item check_item _fields_customer_<?php echo $label; ?>_field">
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
                               value="<?php echo $label; ?>">
                        <input type="hidden" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
                               value="<?php echo $field['_label']; ?>">
                        <input type="checkbox" class="checkbox" style=""
                               name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_value]"
                               id="_fields_customer_<?php echo $label; ?>">
                        <label for="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"><?php echo $field['_label']; ?></label>
                    </p>
                    <?php

                default:
                    do_action( 'yith_wcevti_custom_field', $field, $index, $row, $label );
                    break;
            }
        }
    }
    ?>
</div>