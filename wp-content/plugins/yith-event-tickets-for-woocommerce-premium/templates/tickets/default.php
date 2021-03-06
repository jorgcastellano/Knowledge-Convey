<!doctype html>
<html lang="en">
<?php
    if( ! ( is_user_logged_in() && ( is_user_owner( $post->ID ) || current_user_can('manage_woocommerce') ) ) ) {
        return;
    }
?>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Expletus+Sans" rel="stylesheet">
    <style type="text/css">
    <?php
    $args = array(
        'post' => $post,
        'fields' => $fields,
        'date' => $date,
        'price' => $price,
        'mail_template' => $mail_template,
        'header_image' => $header_image,
        'barcode' => $barcode,
        'barcode_rendered' => $barcode_rendered,
        'content_image' => $content_image,
        'footer_image' => $footer_image
    );

    yith_wcevti_get_template('default-css-preview', $args, 'tickets');
    ?>
    </style>
</head>
<body>
<?php
yith_wcevti_get_template('default-html-preview', $args, 'tickets');

?>
</body>
</html>