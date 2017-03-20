<?php
$header_title_width = '100';
$header_image_margin_left = '5';
$header_title_left = '6';
$header_title_left_border = YITH_WCEVTI_ASSETS_URL . '/images/triangle-pattern.png' ;

if('on' == $barcode['display'] & defined('YITH_YWBC_PREMIUM') & !empty($barcode_rendered)){
    $header_title_width = '80';
    $header_image_margin_left = '1.75';
    $header_title_left = '3';
    $header_title_left_border = YITH_WCEVTI_ASSETS_URL . '/images/circle-pattern.png' ;
}
?>

    body{
        font-family: "Open Sans";
        background: #ddd;
    }
    .barcode_container, .yith_evti_mail_template_content{
        background-color: white;
    }

    .yith_evti_mail_template_container{
    }

    .yith_evti_mail_template_panel{
        margin: auto;
        width: 604px;
    }

    #header_title:before,
    #header_barcode:after{
        content: '';
        display: block;
        position: absolute;
        top:0;
        width: 10px;
        height: 100%;
        background-image: url(<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/triangle-pattern.png);
        background-size: 10px;
    }

    #header_title:after,
    #header_barcode:before{
        content: '';
        display: block;
        position: absolute;
        top:0;
        width: 10px;
        height: 100%;
        background-image: url(<?php echo $header_title_left_border ?>);
        background-size: 10px;
    }

    #header_title:before,
    #header_barcode:before{
        left: 0;
    }
    #header_barcode:after,
    #header_title:after{
        right: 0;
    }
    .yith_evti_mail_template_header {
        width: 100%;
        float: left;
    }

    #header_title{
        width: <?php  echo $header_title_width ?>%;
        float: left;
        padding: 0 5px;
        position: relative;
        box-sizing: border-box;
    }

    .title_container{
        position: relative;
        width: 100%;
        min-height: 13em;
        padding: 1.5em 0;
        background: white;;
    }

    .header_image{
        background-image: url(<?php echo $header_image[0]; ?>);
        background-repeat: no-repeat;
        border-radius: 7px;
        margin: auto;
        width: 420px;
        height: 203px;
    }

    #header_title .title-hover{
        position: absolute;
        display: inline-block;
        width: 70%;
        top: 1em;
        left: <?php echo $header_title_left;?>em;
        text-transform: uppercase;
        text-shadow:  rgb(255, 255, 255) 0px 0px 20px,
        rgb(255, 255, 255) 0px 0px 30px,
        rgb(255, 255, 255) 0px 0px 15px,
        rgb(255, 255, 255) 0px 0px 40px,
        rgb(255, 255, 255) 0px 0px 50px,
        rgb(255, 255, 255) 0px 0px 60px,
        rgb(255, 255, 255) 0px 0px 70px,
        rgb(255, 255, 255) 0px 0px 85px;

    }

    #header_barcode{
        float: left;
        width: 20%;
        padding: 0 5px;
        position: relative;
        box-sizing: border-box;
    }

    .barcode_container{
        background: white;
        padding: 1.35em 1.635em;
        min-height: 13.3em;
    }

    #header_barcode>img{
        display: block;
        margin: auto;
        padding: 1.5em 0;
    }
    #ywbc_barcode_value{
        transform: rotate(90deg);
        text-align: center;
        position: absolute;
        left: -43px;
        top: 90px;
    }

    .yith_evti_mail_template_content{
        color: #3b3b3b;
        margin-top: 2em;
        position: relative;
        z-index: -1;
        width: 100%;
        min-height: 340px;
        float: left;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
    }

    #content_title{
        text-transform: uppercase;
    }

    .content_image{
        position: absolute;
        z-index: -1;
    }

    #content_main{
        padding: 1.5em 3em;
    }
    #content_aditional{
        overflow-wrap: break-word;
    }
    .yith_evti_mail_template_footer{
        background-color: #545454;
        width: 100%;
        float: left;
        border-bottom-left-radius: 7px;
        border-bottom-right-radius: 7px;
        margin-bottom: 1em;
    }

    .footer_logo{
        float: left;
        margin: 0.5em;
    }

    .footer_text{
        color: white;
        font-weight: bold;
        font-size: 0.7em;
        float: left;
        margin: 1.19em 1em;
    }

