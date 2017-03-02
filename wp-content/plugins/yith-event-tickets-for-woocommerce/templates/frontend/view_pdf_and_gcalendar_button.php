<?php
$upload_dir = wp_upload_dir();

?>
<div class="_view_and_pdf_row">
    <?php if(is_user_logged_in()) {?>
        <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'print_mail_template_action', 'id' => $event_id ), admin_url( 'admin-ajax.php' ) ) ); ?>" class="button wc-forward" target="_blank"><?php echo __('View', 'yith-event-tickets-for-woocommerce')?></a>
    <?php
    }

    $file = $upload_dir['baseurl'] . '/ywcevti-pdf-tickets/'. $event_id .  '.pdf';
    if( @fopen($file, 'r')){
        ?>
        <a href="<?php echo esc_url($file); ?>" class="button wc-forward" download><?php echo __('PDF', 'yith-event-tickets-for-woocommerce')?></a>
        <?php
    }?>
    <a href="<?php echo esc_url($url_google_calendar);?>" class="button wc-forward tooltip" target="_blank"><?php echo __('Export to Google Calendar', 'yith-event-tickets-for-woocommerce')?></a>
</div>
