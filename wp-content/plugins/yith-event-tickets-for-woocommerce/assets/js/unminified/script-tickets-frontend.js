jQuery(document).ready(function($){

    init_common();
    init_services();

    function init_common() {

        $( '.fields_panel').on('click', '.header_service_row', function () {
            $(this).next().toggle('fast');

        });

        $('.fields_panel').on('click', '.header_service_row .remove', function (e) {
            e.preventDefault();
            if(confirm(yith_wcevti_tickets.messages.ask_for_delete_ticket + ' ' + yith_wcevti_tickets.messages.ticket) + '?') {
                var $row = $(this).closest('.field_service_row');
                $row.remove();

                erase_row_form($row); //Delete row cookies values...

                $('.quantity>input[type=number]').val($('.field_service_row').length);

                if (typeof Cookies.get('quantity') !== 'undefined') {
                    Cookies.set('quantity', $('.quantity>input[type=number]').val(), {path: ''});
                }

                refresh_total_price();

                $('.field_service_row').each(function (i) {
                    if (0 != i) {
                        set_field_service_row($(this), i, false);
                    }
                });
            }
        });


        $( '.fields_panel').on('change', 'input, select', function () {
            var $row = $(this).closest('.field_service_row');
            var $message = $(this).prev('.yith_wcevti_item_message_empty, .yith_wcevti_item_message_good');

            if(0 != $message.length) {
                if(!validate_row($row)){

                    display_row_message($row, 'incomplete');
                }
            }

        });

        $('.single_add_to_cart_button').on('click', function () {
            validate_form();
            save_form();
        });

        var quantity = ( typeof Cookies.get('quantity') !== 'undefined' ) ? Cookies.get('quantity') : 1;

        $( '.quantity>input[type=number]' ).val(quantity);

        $( '.quantity>input[type=number]' ).change(load_fields_event);

        $( '.quantity>input[type=number]' ).trigger('change');

    }

    function init_services() {
        $('.fields_panel').on('change','._select_item', display_service_range);
        $('._select_item').trigger('change');
        $('._select_item').prev('.yith_wcevti_item_message').remove();


        $('.fields_panel').on('change', '.checkbox', function (event) {

            var $service_panel = $(this).closest('.service_panel');

            if($(this).is(':checked')){
                $overchage = parseFloat($(this).attr('data-overcharge'));
            } else {
                $overchage = parseFloat(0);
            }

            refresh_price_service($service_panel, $overchage);
        });
        $('.checkbox').trigger('change');

    }

    function validate_form() {
        $(('.field_service_row')).each(function (i) {
            if(!validate_row($(this))){
                $(this).find('.content_service_row').toggle(true);
                display_row_message(this, 'incomplete');
                return false;
            }
        });

    }

    function validate_row($row) {
        var $row_is_validate = true;

            $row.find(':input[required], select[required]').each(function (i) {

                var $tag_name = $(this).prop('tagName').toLocaleLowerCase();
                switch ($tag_name) {
                    case 'input':
                        $row_is_validate = validate_input(this) ? $row_is_validate : false;
                        break;
                    case 'select':
                        $row_is_validate = validate_select(this) ? $row_is_validate : false;
                        break;
                }
            });
        return $row_is_validate;
    }

    function validate_input(input) {
        var $input_is_validate = true,
            $input_type = $(input).attr('type');

        $input_is_validate = validate_val(input) ?  $input_is_validate : false;

        switch ($input_type){
            case 'email':
                if($input_is_validate){
                    var pattern = RegExp(/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i);
                    $input_is_validate = pattern.test($(input).val()) ? $input_is_validate : false ;
                    if(!$input_is_validate){
                        display_item_message($(input), 'wrong_mail');
                    } else {
                        display_item_message($(input), 'good');
                    }
                } else {
                    display_item_message($(input), 'empty');
                }

                break;
            case 'number':
                if($input_is_validate){
                    $input_is_validate = $.isNumeric($(input).val()) ? $input_is_validate : false ;
                    if(!$input_is_validate){
                        display_item_message($(input), 'wrong_number');
                    } else {
                        display_item_message($(input), 'good');
                    }
                } else {
                    display_item_message($(input), 'empty');
                }

                break;
            case 'date':
                if($input_is_validate){
                    var date = Date.parse($(input).val());
                    $input_is_validate = date ? $input_is_validate : false;

                    if(!$input_is_validate){
                        var dateParts = $(input).val().split("/");
                        var dateString = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                        date = Date.parse(dateString);
                        $input_is_validate = date ? true : $input_is_validate;
                    }

                    if(!$input_is_validate){
                        display_item_message($(input), 'wrong_date');
                    } else {
                        display_item_message($(input), 'good');
                    }
                } else {
                    display_item_message($(input), 'empty');
                }
                break;
            default:
                if(!$input_is_validate){
                    display_item_message(input, 'empty');
                } else {
                    display_item_message(input, 'good');
                }
                break;
        }


        return $input_is_validate;
    }

    function validate_select(select) {
        var $select_is_validate = true;

        if(!$(select).is(':disabled')){
            $select_is_validate = validate_val(select) ? $select_is_validate : false;
        } else{
            $(select).val('');
            $select_is_validate = false;
        }

        if(!$select_is_validate){
            display_item_message($(select), 'empty');
        } else {
            display_item_message($(select), 'good');
        }

        return $select_is_validate;
    }

    function validate_val(value) {
        var $val_is_validate = true;

        if(null != $(value).val()){
            $val_is_validate = (0 === $(value).val().length) ? false : $val_is_validate;
        } else {
            $(value).val('');
            $val_is_validate = false;
        }
        return $val_is_validate ;
    }
    
    function load_fields_event() {
        //Calcule difference between num rows and current cuantity stock to determinate how many row we need adds.
        var diff = $(this).val() - $('.field_service_row').length;
        if(diff >= 0) { //Add rows

            $('.cart').block({message:null, overlayCSS:{background:"#fff",opacity:.6}});

            for (var i = 0; i < diff; i++){
                var row_index = $('.field_service_row').length;

                var $field_service_row = $('.field_service_row').first().clone();

                $field_service_row = set_field_service_row($field_service_row, row_index, true);

                $('.fields_panel').append($field_service_row);
                $field_service_row.find('.content_service_row').toggle(true);
            }
            $('.field_service_row .remove').show();
            $('.field_service_row .remove').first().hide();
            refresh_total_price();
            $('.cart').unblock();
            fill_form_with_cookies();

        } else { //remove rows
            if(diff != 0){
                if(confirm(yith_wcevti_tickets.messages.ask_for_delete_ticket + ' ' + diff*-1 + ' ' + yith_wcevti_tickets.messages.tickets) + '?') {
                    if (typeof Cookies.get('quantity') !== 'undefined') {
                        fill_form_with_cookies();
                    }
                    remove_form_fields(diff);
                }
            }
        }
    }

    function set_field_service_row($field_service_row, row_index, clear_values) {

        $field_service_row.find('input, select, textarea').each(function (i) {
            var $name = $(this).attr('name'),
                re_name =  /customer\[\d+]/;

            $name = $name.replace(re_name, 'customer[' + row_index + ']' );
            $(this).attr('name', $name);

            var $id = $(this).attr('id');

            if(typeof $id !== 'undefined'){
                var re_id = /customer_\d+/;

                $id = $id.replace(re_id, 'customer_'+ row_index);

                re_id = /customer\[\d+]/;

                $id = $id.replace(re_id, 'customer[' + row_index +']');

                $(this).attr('id', $id);
            }
            if($(this).attr('type') !== 'hidden' & clear_values){
                $(this).val('');
            }
        });

        $field_service_row.find('label').each(function (i) {
            var $for = $(this).attr('for'),
                re_for = /customer_\d+/;
            $for = $for.replace(re_for, 'customer_'+ row_index);

            re_for = /customer\[\d+]/;

            $for = $for.replace(re_for, 'customer[' + row_index +']');
            $(this).attr('for', $for);
        });

        var $row_title = $field_service_row.find('.header_service_row>h3').text();
        $row_title = $row_title.slice( 0 , $row_title.indexOf('#') + 1);

        $field_service_row.find('.header_service_row>h3').text($row_title + (row_index + 1));
        clear_row_style($field_service_row);


        return $field_service_row;
    }

    function display_service_range() {

        var $selected = $(this).find(':selected');

        var $service_panel = $(this).closest('.service_panel'),
            $service_field = $service_panel.find('._services_customer_'+ $selected.attr('name') +'_field'),
            $overchage = parseFloat($('option:selected', this).data('overcharge'));

        $service_panel.find('.service_range>.select_range').prop('required', false);
        $service_panel.find('.service_range').hide();

        $($service_field).find('.yith_wcevti_item_message').remove();
        $($service_field).find('.select_range').prop('required', true);
        $service_field.show();
        if($service_field.hasClass('service_range')){
            $service_field.find('.select_range').trigger('change');
        }

        refresh_price_service($service_panel, $overchage);
    }

    function remove_form_fields(diff) {
        diff = diff * -1;

        var current_index = $('.field_service_row').length - diff;

        $('.field_service_row').each(function (i, row) {
            if(i > 0 & i >= current_index){
                erase_row_form($(row));
                row.remove();
            }
        });
        refresh_total_price();
    }

    function refresh_price_service(service_panel, overchage) {

        $price_service = parseFloat($(service_panel).attr('data-price_service'));
        $overchage = parseFloat(overchage);

        $total_price = parseFloat($('.yith_evti_total_price').attr('data-current_price'));

        $total_price = $total_price - $price_service;
        $total_price = $total_price + $overchage;

        $('.yith_evti_total_price').attr('data-current_price', $total_price);
        $(service_panel).attr('data-price_service', $overchage);

        $last_price_content = $('.yith_evti_total_price>.price>.woocommerce-Price-amount').contents().last();

        if($last_price_content.hasClass('woocommerce-Price-currencySymbol')){
            $('.yith_evti_total_price>.price>.woocommerce-Price-amount').contents().first().replaceWith($total_price.toFixed(2));

        } else {
            $('.yith_evti_total_price>.price>.woocommerce-Price-amount').contents().last().replaceWith($total_price.toFixed(2));
        }
        $(document).trigger( 'yith_wcevti_price_refreshed', [ $total_price.toFixed(2) ] );
    }

    function refresh_total_price() {
        $base_price = yith_wcevti_tickets.product.price;
        $num_tickets = parseFloat($('.quantity>input[type=number]').val());
        $total_price = $base_price * $num_tickets;

        $('.yith_evti_total_price').attr('data-current_price', $total_price);
        $('.service_panel').attr('data-price_service', 0);

        $('._select_item').trigger('change');
        $('.checkbox').trigger('change');

        $last_price_content = $('.yith_evti_total_price>.price>.woocommerce-Price-amount').contents().last();

        if($last_price_content.hasClass('woocommerce-Price-currencySymbol')){
            $('.yith_evti_total_price>.price>.woocommerce-Price-amount').contents().first().replaceWith($total_price.toFixed(2));

        } else {
            $('.yith_evti_total_price>.price>.woocommerce-Price-amount').contents().last().replaceWith($total_price.toFixed(2));
        }
        $(document).trigger( 'yith_wcevti_price_refreshed', [ $total_price.toFixed(2) ] );
    }
    
    function save_form() {
        $(('.field_service_row')).each(function (i) {
            var $row = $(this);
            save_row_form($row);
        });
        Cookies.set('quantity', $( '.quantity>input[type=number]' ).val(), { path: '' });
    }

    function save_row_form($row) {
        $row.find('input, select, textarea').each(function (i) {
            set_cookie(this);
        });
    }

    function erase_row_form($row) {
        $row.find('input, select, textarea').each(function (i) {
            erase_cookie(this);
        });
    }

    function set_cookie(item) {
        if($(item).attr('type') !== 'hidden'){
            var $name = $(item).attr('name'),
                $val = $(item).val();
            switch ($(item).attr('type')) {
                case 'checkbox':
                    $val = $(item).prop('checked');
                    break;
            }
            Cookies.set($name, $val, { path: '' });
        }
    }

    function erase_cookie (item){
        Cookies.remove($(item).attr('name'), { path: '' });
    }

    function fill_form_with_cookies() {

        $(('.field_service_row')).each(function (i) {
            var $row = $(this);
            $row.find('input, select, textarea').each(function (i) {
                var $val = Cookies.get($(this).attr('name'));

                if ( typeof $val !== 'undefined'){
                    switch ($(this).attr('type')){
                        case 'checkbox':
                            var $val = ($val === 'true');
                            $(this).prop('checked', $val);
                            break;
                        default:
                            $(this).val($val);
                            break;

                    };
                }
                if (validate_val(this) & $(this).attr('type') !== 'hidden') {
                    $(this).closest('.form-field').show();
                }

            });
        });

        if(typeof Cookies.get('quantity') !== 'undefined') {
            Cookies.set('quantity', $('.quantity>input[type=number]').val(), {path: ''});
        }
    }
    
    function display_row_message(row, type) {
        var $service_message = $(row).closest('.field_service_row').find('.service_message');
        $service_message.empty();

        switch (type){
            case 'complete':
                var $notice = $('<span/>').addClass('fa fa-check yith_wcevti_item_message yith_wcevti_item_message_complete').text(' ' + yith_wcevti_tickets.messages.complete_field_service);
                $(row).closest('.field_service_row').removeClass('yith_wcevti_item_panel_unstyle yith_wcevti_item_panel_incomplete');
                $(row).closest('.field_service_row').addClass('yith_wcevti_item_panel_complete');
                $notice.appendTo($service_message);

                break;
            case 'incomplete':
                var $notice = $('<span/>').addClass('fa fa-close yith_wcevti_item_message yith_wcevti_item_message_incomplete').text(' ' + yith_wcevti_tickets.messages.incomplete_field_service);
                 $(row).closest('.field_service_row').removeClass('yith_wcevti_item_panel_unstyle yith_wcevti_item_panel_complete');
                 $(row).closest('.field_service_row').addClass('yith_wcevti_item_panel_incomplete');
                $notice.appendTo($service_message);
                break;
        }
    }

    function display_item_message(item, type) {
        $(item).prev('.yith_wcevti_item_message').remove();
        $row = $(item).closest('.field_service_row').find('.service_message');

        switch (type){
            case 'good':
                var $notice = $('<span/>').addClass('fa fa-check yith_wcevti_item_message yith_wcevti_item_message_good').text(' ' + yith_wcevti_tickets.messages.complete_required_item);
                $notice.insertBefore(item);
                display_row_message($row, 'complete');
                break;
            case 'empty':
                var $notice = $('<span/>').addClass('fa fa-close yith_wcevti_item_message yith_wcevti_item_message_empty').text(' ' + yith_wcevti_tickets.messages.incomplete_required_item);
                $notice.insertBefore(item);
                display_row_message($row, 'incomplete');
                break;
            case 'wrong_mail':
                var $notice = $('<span/>').addClass('fa fa-close yith_wcevti_item_message yith_wcevti_item_message_empty').text(' ' + yith_wcevti_tickets.messages.wrong_mail_field);
                $notice.insertBefore(item);
                display_row_message($row, 'incomplete');
                break;
            case 'wrong_number':
                var $notice = $('<span/>').addClass('fa fa-close yith_wcevti_item_message yith_wcevti_item_message_empty').text(' ' + yith_wcevti_tickets.messages.wrong_number_field);
                $notice.insertBefore(item);
                display_row_message($row, 'incomplete');
                break;
            case 'wrong_date':
                var $notice = $('<span/>').addClass('fa fa-close yith_wcevti_item_message yith_wcevti_item_message_empty').text(' ' + yith_wcevti_tickets.messages.wrong_date_field);
                $notice.insertBefore(item);
                display_row_message($row, 'incomplete');
                break;
            default:
                $(item).prev('.yith_wcevti_item_message').remove();
                break;
        }
    }

    function clear_row_style($row) {
        $row.find('.yith_wcevti_item_message').text('');
        $row.find('.yith_wcevti_item_message').removeClass('fa fa-check fa-close yith_wcevti_item_message_incomplete yith_wcevti_item_message_complete');
        $row.removeClass('yith_wcevti_item_panel_complete');
        $row.addClass('yith_wcevti_item_panel_unstyle');

    }

});