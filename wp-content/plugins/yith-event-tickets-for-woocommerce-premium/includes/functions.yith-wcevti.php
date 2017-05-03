<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


/**
 * Get template for Event Tickets plugin
 *
 * @param $filename string Template name (with or without extension)
 * @param $args mixed Array of params to use in the template
 * @param $section string Subdirectory where to search
 */

function yith_wcevti_get_template( $filename, $args = array(), $section = '' ){
    $ext = strpos( $filename, '.php' ) === false ? '.php' : '';

    $template_name      = $section . '/' . $filename . $ext;
    $template_path      = WC()->template_path() . 'yith-wcevti/';
    $default_path       = YITH_WCEVTI_TEMPLATE_PATH;

    if( defined( 'YITH_WCEVTI_PREMIUM' ) ){
        $premium_template   = str_replace( '.php', '-premium.php', $template_name );
        $located_premium    = wc_locate_template( $premium_template, $template_path, $default_path );
        $template_name      = file_exists( $located_premium ) ?  $premium_template : $template_name;
    }

    wc_get_template( $template_name, $args, $template_path, $default_path );
}

function yith_wcevti_print_error($text){
    error_log(print_r($text, true));
}

function yith_wcevti_set_args_mail_template($post){
    $args = array();

    $post_meta = get_post_meta($post->ID, '', true);
    $mail_template = get_post_meta($post_meta['wc_event_id'][0], '_mail_template', true);

    $fields = array();
    $price = __('Free', 'yith-event-tickets-for-woocommerce' );

    $location = get_post_meta($post_meta['wc_event_id'][0], '_direction_event', true);
    $date = yith_wecvti_get_date_message($post_meta['wc_event_id'][0]);

    if( version_compare( WC()->version, '3.0.0', '<' ) ){
        $item_meta = $post_meta;
    } else {
        $item_meta = wc_get_order_item_meta( $post_meta['wc_order_item_id'][0], '', $single = true );
    }


    foreach ($item_meta as $key => $meta){
        if (preg_match('/field_/i', $key)) {
            $label = str_replace( array( 'field_' ), '', $key );
            $label = str_replace( array( '_' ), '', $label );
            $value = $meta[0];

            $fields[] = array(
                $label => $value
            );

        }
    }
    if(isset($post_meta['wc_total'][0])){
        $price = $post_meta['wc_total'][0];
    }

    $header_image = !empty($mail_template['data']['header_image']['id']) ? wp_get_attachment_image_src($mail_template['data']['header_image']['id'], 'default_header_mail') : array(YITH_WCEVTI_ASSETS_URL . 'images/header_image.png');
    $barcode = !empty($mail_template['data']['barcode']) & 'on' == $mail_template['data']['barcode']['display'] ? $mail_template['data']['barcode'] : false;
    $content_image = !empty($mail_template['data']['background_image']['id']) ? wp_get_attachment_image_src($mail_template['data']['background_image']['id'], 'default_content_mail') : array(YITH_WCEVTI_ASSETS_URL . 'images/background_image.png');
    $footer_image = !empty($mail_template['data']['footer_image']['id']) ? wp_get_attachment_image_src($mail_template['data']['footer_image']['id'], 'default_footer_mail') : array(YITH_WCEVTI_ASSETS_URL . 'images/footer_image.png');
    $barcode_rendered = '';

    if(defined('YITH_YWBC_PREMIUM') & false != $barcode){
        $barcode_id = 0;
        switch ($barcode['type']){
            case 'ticket':
                $barcode_id = $post->ID;
                break;
            case 'product':
                $barcode_id = get_post_meta($post->ID, 'wc_event_id', true);
                break;
            case 'order':
                $barcode_id = get_post_meta($post->ID, 'wc_order_id', true);
                break;
        }
        $barcode_object = new YITH_Barcode( $barcode_id );
        $barcode_object->save();
        $barcode_rendered = do_shortcode('[yith_render_barcode value="' . $barcode_id . '" layout="<div class=\'barcode-image\'>{barcode_image}</div><div class=\'barcode-code\'>{barcode_code}</div>"]');
    }

    if(isset($mail_template['type'])) {
        $args = array(
            'post' => $post,
            'fields' => $fields,
            'location' => $location,
            'date' => $date,
            'price' => $price,
            'mail_template' => $mail_template,
            'header_image' => $header_image,
            'barcode' => $barcode,
            'barcode_rendered' => $barcode_rendered,
            'content_image' => $content_image,
            'footer_image' => $footer_image
        );
        $args = apply_filters('yith_wcevti_set_custom_mail_args', $args, $post_meta, $item_meta);
    }

    return $args;
}

function yith_wecvti_print_mail_template_preview($post){
    $args = yith_wcevti_set_args_mail_template($post);
    return yith_wcevti_get_template($args['mail_template']['type'], $args, 'tickets');
}

function yith_wcevti_create_pdf($id){
    $pdf_content = yith_wcevti_ticket_to_pdf($id);
    return file_put_contents(YITH_WCEVTI_DOCUMENT_SAVE_PDF_DIR . '/' . $id .'.pdf', $pdf_content);
}

function yith_wcevti_ticket_to_pdf($id_ticket){
    $post = get_post($id_ticket);

    ob_start();
    $args = yith_wcevti_set_args_mail_template($post);
    yith_wcevti_get_template('default-css', $args, 'tickets');

    $css = ob_get_clean();

    ob_start();
    $args = yith_wcevti_set_args_mail_template($post);
    yith_wcevti_get_template('default-html', $args, 'tickets');

    $html = ob_get_clean();
    $mpdf = new mPDF();
    $mpdf->WriteHTML('<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,800" rel="stylesheet">', 2);
    $mpdf->WriteHTML( $css , 1);
    $mpdf->WriteHTML( $html , 2);

    $pdf = $mpdf->Output('ticket', 'S');

    return $pdf;
}

function yith_wcevti_get_pdf_path($id_ticket){
    $upload_dir = wp_upload_dir();
    return $upload_dir['basedir'] . '/ywcevti-pdf-tickets/'. $id_ticket .  '.pdf';
}

function yith_wcevti_get_pdf_url($id_ticket){
    $upload_dir = wp_upload_dir();
    return $upload_dir['baseurl'] . '/ywcevti-pdf-tickets/'. $id_ticket .  '.pdf';
}

function yith_wcevti_get_google_calendar_link ($id_product){
    $title = str_replace(' ', '+', get_the_title($id_product));
    $start_date = str_replace('-', '', get_post_meta($id_product, '_start_date_picker', true));
    $start_time = str_replace(':', '', get_post_meta($id_product, '_start_time_picker', true));
    $end_date = str_replace('-', '', get_post_meta($id_product, '_end_date_picker', true));
    $end_time = str_replace(':', '', get_post_meta($id_product, '_end_time_picker', true));
    $description =str_replace(' ', '+', get_post_field('post_content', $id_product));
    $direction =str_replace(' ', '+',  get_post_meta($id_product, '_direction_event', true));

    $text = '';
    if(!empty($title)){
        $text = '&text='. $title;
    }

    $dates = '';
    if(!empty($start_date) & !empty($end_date)){

        if(!empty($start_time) & !empty($end_time)) {
            $dates = '&dates='.$start_date . 'T' . $start_time . '00Z/' . $end_date . 'T' . $end_time . '00Z';
        } else {
            $dates = '&dates='. $start_date . '/' . $end_date;
        }
    }

    $details = '';
    if(!empty($description)){
        $details = '&details=' . $description;
    }

    $location = '';
    if(!empty($direction)){
        $location = '&location=' . $direction;
    }

    $link = 'https://calendar.google.com/calendar/render?action=TEMPLATE' . $text . $dates  . $details . $location . '&sf=true&output=xml';

    return $link;
}

function yith_wecvti_get_date_message($id){
    $product = wc_get_product($id);
    $message_start = '';
    $message_end = '';

    $start_date = yit_get_prop( $product , '_start_date_picker', true);
    $start_time = yit_get_prop( $product , '_start_time_picker', true);
    $end_date = yit_get_prop( $product , '_end_date_picker', true);
    $end_time = yit_get_prop( $product , '_end_time_picker', true);

    if(!empty($start_date) & !empty($end_date)) {
        $start_text = __('Start', 'yith-event-tickets-for-woocommerce');
        $at_text = __('at', 'yith-event-tickets-for-woocommerce');
        $end_text = __('Finish', 'yith-event-tickets-for-woocommerce');

        $date_format = get_option('date_format');
        $start_date = date_i18n($date_format, strtotime($start_date));
        $end_date = date_i18n($date_format, strtotime($end_date));

        if (!empty($start_time) & !empty($end_time)) {
            $message_start = $start_text . ': <b>' . $start_date . '</b> ' . $at_text . ' <b>' . $start_time . '</b> ';
            $message_end = $end_text . ': <b>' . $end_date . '</b> ' . $at_text . ' <b>' . $end_time . '</b>';

        } else {
            $message_start = $start_text . ': <b>' . $start_date . '</b> ';
            $message_end = $end_text . ': <b>' . $end_date . '</b>';
        }

    }
    return array('message_start' => $message_start, 'message_end' => $message_end);
}

function is_user_owner($id){
    $owner = false;
    $order_id = get_post_meta($id, 'wc_order_id', true);
    $order = wc_get_order($order_id);
    if(get_current_user_id() == yit_get_prop($order, 'user_id')){
        $owner = true;
    }
    return $owner;
}

