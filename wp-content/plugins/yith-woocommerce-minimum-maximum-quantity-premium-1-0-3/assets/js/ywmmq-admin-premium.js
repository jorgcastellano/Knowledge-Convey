jQuery(function ($) {

    $(document).ready(function ($) {

        /**
         * Plugin admin panel
         */
        $('#ywmmq_cart_quantity_limit').change(function () {

            var ywmmq_cart_minimum_quantity = $('#ywmmq_cart_minimum_quantity').parent().parent(),
                ywmmq_cart_maximum_quantity = $('#ywmmq_cart_maximum_quantity').parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_cart_minimum_quantity.show();
                ywmmq_cart_maximum_quantity.show();

            } else {

                ywmmq_cart_minimum_quantity.hide();
                ywmmq_cart_maximum_quantity.hide();

            }

        }).change();

        $('#ywmmq_cart_value_limit').change(function () {

            var ywmmq_cart_minimum_value = $('#ywmmq_cart_minimum_value').parent().parent(),
                ywmmq_cart_maximum_value = $('#ywmmq_cart_maximum_value').parent().parent(),
                ywmmq_cart_value_shipping = $('#ywmmq_cart_value_shipping').parent().parent().parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_cart_minimum_value.show();
                ywmmq_cart_maximum_value.show();
                ywmmq_cart_value_shipping.show();

            } else {

                ywmmq_cart_minimum_value.hide();
                ywmmq_cart_maximum_value.hide();
                ywmmq_cart_value_shipping.hide();

            }

        }).change();

        $('#ywmmq_product_quantity_limit').change(function () {

            var ywmmq_product_minimum_quantity = $('#ywmmq_product_minimum_quantity').parent().parent(),
                ywmmq_product_maximum_quantity = $('#ywmmq_product_maximum_quantity').parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_product_minimum_quantity.show();
                ywmmq_product_maximum_quantity.show();

            } else {

                ywmmq_product_minimum_quantity.hide();
                ywmmq_product_maximum_quantity.hide();

            }

        }).change();

        $('#ywmmq_category_quantity_limit').change(function () {

            var ywmmq_category_minimum_quantity = $('#ywmmq_category_minimum_quantity').parent().parent(),
                ywmmq_category_maximum_quantity = $('#ywmmq_category_maximum_quantity').parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_category_minimum_quantity.show();
                ywmmq_category_maximum_quantity.show();

            } else {

                ywmmq_category_minimum_quantity.hide();
                ywmmq_category_maximum_quantity.hide();

            }

        }).change();

        $('#ywmmq_category_value_limit').change(function () {

            var ywmmq_category_minimum_value = $('#ywmmq_category_minimum_value').parent().parent(),
                ywmmq_category_maximum_value = $('#ywmmq_category_maximum_value').parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_category_minimum_value.show();
                ywmmq_category_maximum_value.show();

            } else {

                ywmmq_category_minimum_value.hide();
                ywmmq_category_maximum_value.hide();

            }

        }).change();

        $('#ywmmq_tag_quantity_limit').change(function () {

            var ywmmq_tag_minimum_quantity = $('#ywmmq_tag_minimum_quantity').parent().parent(),
                ywmmq_tag_maximum_quantity = $('#ywmmq_tag_maximum_quantity').parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_tag_minimum_quantity.show();
                ywmmq_tag_maximum_quantity.show();

            } else {

                ywmmq_tag_minimum_quantity.hide();
                ywmmq_tag_maximum_quantity.hide();

            }

        }).change();

        $('#ywmmq_tag_value_limit').change(function () {

            var ywmmq_tag_minimum_value = $('#ywmmq_tag_minimum_value').parent().parent(),
                ywmmq_tag_maximum_value = $('#ywmmq_tag_maximum_value').parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_tag_minimum_value.show();
                ywmmq_tag_maximum_value.show();

            } else {

                ywmmq_tag_minimum_value.hide();
                ywmmq_tag_maximum_value.hide();

            }

        }).change();

        $('#ywmmq_message_enable_atc').change(function () {

            var ywmmq_message_min_cart_quantity_atc = $('#ywmmq_message_min_cart_quantity_atc').parent().parent(),
                ywmmq_message_max_cart_quantity_atc = $('#ywmmq_message_max_cart_quantity_atc').parent().parent(),
                ywmmq_message_min_cart_value_atc = $('#ywmmq_message_min_cart_value_atc').parent().parent(),
                ywmmq_message_max_cart_value_atc = $('#ywmmq_message_max_cart_value_atc').parent().parent(),
                ywmmq_message_min_product_quantity_atc = $('#ywmmq_message_min_product_quantity_atc').parent().parent(),
                ywmmq_message_max_product_quantity_atc = $('#ywmmq_message_max_product_quantity_atc').parent().parent(),
                ywmmq_message_min_category_quantity_atc = $('#ywmmq_message_min_category_quantity_atc').parent().parent(),
                ywmmq_message_max_category_quantity_atc = $('#ywmmq_message_max_category_quantity_atc').parent().parent(),
                ywmmq_message_min_category_value_atc = $('#ywmmq_message_min_category_value_atc').parent().parent(),
                ywmmq_message_max_category_value_atc = $('#ywmmq_message_max_category_value_atc').parent().parent(),
                ywmmq_message_min_tag_quantity_atc = $('#ywmmq_message_min_tag_quantity_atc').parent().parent(),
                ywmmq_message_max_tag_quantity_atc = $('#ywmmq_message_max_tag_quantity_atc').parent().parent(),
                ywmmq_message_min_tag_value_atc = $('#ywmmq_message_min_tag_value_atc').parent().parent(),
                ywmmq_message_max_tag_value_atc = $('#ywmmq_message_max_tag_value_atc').parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_message_min_cart_quantity_atc.show();
                ywmmq_message_max_cart_quantity_atc.show();
                ywmmq_message_min_cart_value_atc.show();
                ywmmq_message_max_cart_value_atc.show();
                ywmmq_message_min_product_quantity_atc.show();
                ywmmq_message_max_product_quantity_atc.show();
                ywmmq_message_min_category_quantity_atc.show();
                ywmmq_message_max_category_quantity_atc.show();
                ywmmq_message_min_category_value_atc.show();
                ywmmq_message_max_category_value_atc.show();
                ywmmq_message_min_tag_quantity_atc.show();
                ywmmq_message_max_tag_quantity_atc.show();
                ywmmq_message_min_tag_value_atc.show();
                ywmmq_message_max_tag_value_atc.show();

            } else {

                ywmmq_message_min_cart_quantity_atc.hide();
                ywmmq_message_max_cart_quantity_atc.hide();
                ywmmq_message_min_cart_value_atc.hide();
                ywmmq_message_max_cart_value_atc.hide();
                ywmmq_message_min_product_quantity_atc.hide();
                ywmmq_message_max_product_quantity_atc.hide();
                ywmmq_message_min_category_quantity_atc.hide();
                ywmmq_message_max_category_quantity_atc.hide();
                ywmmq_message_min_category_value_atc.hide();
                ywmmq_message_max_category_value_atc.hide();
                ywmmq_message_min_tag_quantity_atc.hide();
                ywmmq_message_max_tag_quantity_atc.hide();
                ywmmq_message_min_tag_value_atc.hide();
                ywmmq_message_max_tag_value_atc.hide();

            }

        }).change();

        $('#ywmmq_rules_enable').change(function () {

            var ywmmq_rules_position = $('#ywmmq_rules_position').parent().parent(),
                ywmmq_rules_before_text = $('#ywmmq_rules_before_text').parent().parent();

            if ($(this).is(':checked')) {

                ywmmq_rules_position.show();
                ywmmq_rules_before_text.show();

            } else {

                ywmmq_rules_position.hide();
                ywmmq_rules_before_text.hide();

            }

        }).change();

        /**
         * Product edit page
         */
        function lock_unlock_product(product_override) {
            var variations_override = $('#_ywmmq_product_quantity_limit_variations_override'),
                product_override_enabled = product_override.is(':checked'),
                variations_override_enabled = (variations_override.length > 0 ? variations_override.is(':checked') : false);

            if (product_override_enabled) {

                variations_override.removeAttr('disabled');

                if (!variations_override_enabled) {

                    $('#_ywmmq_product_minimum_quantity').removeAttr('disabled');
                    $('#_ywmmq_product_maximum_quantity').removeAttr('disabled');

                }

            } else {

                variations_override.attr('disabled', 'disabled');

                $('#_ywmmq_product_minimum_quantity').attr('disabled', 'disabled');
                $('#_ywmmq_product_maximum_quantity').attr('disabled', 'disabled');

            }
        }

        $('#_ywmmq_product_exclusion').change(function () {

            var product_override = $('#_ywmmq_product_quantity_limit_override');

            if ($(this).is(':checked')) {

                product_override.attr('disabled', 'disabled');
                $('#_ywmmq_product_minimum_quantity').attr('disabled', 'disabled');
                $('#_ywmmq_product_maximum_quantity').attr('disabled', 'disabled');
                $('#_ywmmq_product_quantity_limit_variations_override').attr('disabled', 'disabled');

            } else {

                product_override.removeAttr('disabled');
                lock_unlock_product(product_override);

            }

        }).change();

        $('#_ywmmq_product_quantity_limit_override').change(function () {

            lock_unlock_product($(this));


        }).change();

        $('#_ywmmq_product_quantity_limit_variations_override').change(function () {

            if ($(this).is(':checked')) {

                $('#_ywmmq_product_minimum_quantity').attr('disabled', 'disabled');
                $('#_ywmmq_product_maximum_quantity').attr('disabled', 'disabled');
                $('.ywmmq-variation-field').each(function () {

                    $(this).removeAttr('disabled');

                });
            } else {

                $('#_ywmmq_product_minimum_quantity').removeAttr('disabled');
                $('#_ywmmq_product_maximum_quantity').removeAttr('disabled');
                $('.ywmmq-variation-field').each(function () {

                    $(this).attr('disabled', 'disabled');

                });
            }

        }).change();

        /**
         * Category edit page
         */
        function lock_unlock_taxonomy(override_check, taxonomy, type) {

            var minimum_value = $('#_ywmmq_' + taxonomy + '_minimum_' + type),
                maximum_value = $('#_ywmmq_' + taxonomy + '_maximum_' + type);

            if (override_check.is(':checked')) {

                minimum_value.removeAttr('disabled');
                maximum_value.removeAttr('disabled');

            } else {

                minimum_value.attr('disabled', 'disabled');
                maximum_value.attr('disabled', 'disabled');

            }

        }

        $('#_ywmmq_category_exclusion, #_ywmmq_tag_exclusion').change(function () {

            var taxonomy = $(this).attr('id').replace('_ywmmq_', '').replace('_exclusion', ''),
                quantity_override = $('#_ywmmq_' + taxonomy + '_quantity_limit_override'),
                value_override = $('#_ywmmq_' + taxonomy + '_value_limit_override');


            if ($(this).is(':checked')) {
                quantity_override.attr('disabled', 'disabled');
                $('#_ywmmq_' + taxonomy + '_minimum_quantity').attr('disabled', 'disabled');
                $('#_ywmmq_' + taxonomy + '_maximum_quantity').attr('disabled', 'disabled');

                value_override.attr('disabled', 'disabled');
                $('#_ywmmq_' + taxonomy + '_minimum_value').attr('disabled', 'disabled');
                $('#_ywmmq_' + taxonomy + '_maximum_value').attr('disabled', 'disabled');

            } else {

                quantity_override.removeAttr('disabled');
                lock_unlock_taxonomy(quantity_override, taxonomy, 'quantity');

                value_override.removeAttr('disabled');
                lock_unlock_taxonomy(value_override, taxonomy, 'value');

            }

        }).change();

        $('#_ywmmq_category_quantity_limit_override, #_ywmmq_tag_quantity_limit_override').change(function () {

            var taxonomy = $(this).attr('id').replace('_ywmmq_', '').replace('_quantity_limit_override', '');

            lock_unlock_taxonomy($(this), taxonomy, 'quantity')

        }).change();

        $('#_ywmmq_category_value_limit_override, #_ywmmq_tag_value_limit_override').change(function () {

            var taxonomy = $(this).attr('id').replace('_ywmmq_', '').replace('_value_limit_override', '');

            lock_unlock_taxonomy($(this), taxonomy, 'value')

        }).change();

    });

    $('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {

        $('.ywmmq-variation-field').each(function () {

            if ($('#_ywmmq_product_quantity_limit_override').is(':checked') && $('#_ywmmq_product_quantity_limit_variations_override').is(':checked')) {

                $(this).removeAttr('disabled');

            } else {

                $(this).attr('disabled', 'disabled');

            }

        });

    })

});

