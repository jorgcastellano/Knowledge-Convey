<style>
    .section{
        margin-left: -20px;
        margin-right: -20px;
        font-family: "Raleway",san-serif;
    }
    .section h1{
        text-align: center;
        text-transform: uppercase;
        color: #808a97;
        font-size: 35px;
        font-weight: 700;
        line-height: normal;
        display: inline-block;
        width: 100%;
        margin: 50px 0 0;
    }
    .section ul{
        list-style-type: disc;
        padding-left: 15px;
    }
    .section:nth-child(even){
        background-color: #fff;
    }
    .section:nth-child(odd){
        background-color: #f1f1f1;
    }
    .section .section-title img{
        display: table-cell;
        vertical-align: middle;
        width: auto;
        margin-right: 15px;
    }
    .section h2,
    .section h3 {
        display: inline-block;
        vertical-align: middle;
        padding: 0;
        font-size: 24px;
        font-weight: 700;
        color: #808a97;
        text-transform: uppercase;
    }

    .section .section-title h2{
        display: table-cell;
        vertical-align: middle;
        line-height: 25px;
    }

    .section-title{
        display: table;
    }

    .section h3 {
        font-size: 14px;
        line-height: 28px;
        margin-bottom: 0;
        display: block;
    }

    .section p{
        font-size: 13px;
        margin: 25px 0;
    }
    .section ul li{
        margin-bottom: 4px;
    }
    .landing-container{
        max-width: 750px;
        margin-left: auto;
        margin-right: auto;
        padding: 50px 0 30px;
    }
    .landing-container:after{
        display: block;
        clear: both;
        content: '';
    }
    .landing-container .col-1,
    .landing-container .col-2{
        float: left;
        box-sizing: border-box;
        padding: 0 15px;
    }
    .landing-container .col-1 img{
        width: 100%;
    }
    .landing-container .col-1{
        width: 55%;
    }
    .landing-container .col-2{
        width: 45%;
    }
    .premium-cta{
        background-color: #808a97;
        color: #fff;
        border-radius: 6px;
        padding: 20px 15px;
    }
    .premium-cta:after{
        content: '';
        display: block;
        clear: both;
    }
    .premium-cta p{
        margin: 7px 0;
        font-size: 14px;
        font-weight: 500;
        display: inline-block;
        width: 60%;
    }
    .premium-cta a.button{
        border-radius: 6px;
        height: 60px;
        float: right;
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL?>/images/premium/upgrade.png) #ff643f no-repeat 13px 13px;
        border-color: #ff643f;
        box-shadow: none;
        outline: none;
        color: #fff;
        position: relative;
        padding: 9px 50px 9px 70px;
    }
    .premium-cta a.button:hover,
    .premium-cta a.button:active,
    .premium-cta a.button:focus{
        color: #fff;
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL?>/images/premium/upgrade.png) #971d00 no-repeat 13px 13px;
        border-color: #971d00;
        box-shadow: none;
        outline: none;
    }
    .premium-cta a.button:focus{
        top: 1px;
    }
    .premium-cta a.button span{
        line-height: 13px;
    }
    .premium-cta a.button .highlight{
        display: block;
        font-size: 20px;
        font-weight: 700;
        line-height: 20px;
    }
    .premium-cta .highlight{
        text-transform: uppercase;
        background: none;
        font-weight: 800;
        color: #fff;
    }

    .section.one{
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/01-bg.png) no-repeat #fff; background-position: 85% 75%
    }
    .section.two{
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/02-bg.png) no-repeat #fff; background-position: 15% 100%
    }
    .section.three{
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/03-bg.png) no-repeat #fff; background-position: 85% 100%
    }
    .section.four{
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/04-bg.png) no-repeat #fff; background-position: 15% 100%
    }
    .section.five{
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/05-bg.png) no-repeat #fff; background-position: 85% 100%
    }
    .section.six{
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/06-bg.png) no-repeat #fff; background-position: 15% 100%
    }
    .section.seven{
        background: url(<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/07-bg.png) no-repeat #fff; background-position: 85% 100%
    }


    @media (max-width: 768px) {
        .section{margin: 0}
        .premium-cta p{
            width: 100%;
        }
        .premium-cta{
            text-align: center;
        }
        .premium-cta a.button{
            float: none;
        }
    }

    @media (max-width: 480px){
        .wrap{
            margin-right: 0;
        }
        .section{
            margin: 0;
        }
        .landing-container .col-1,
        .landing-container .col-2{
            width: 100%;
            padding: 0 15px;
        }
        .section-odd .col-1 {
            float: left;
            margin-right: -100%;
        }
        .section-odd .col-2 {
            float: right;
            margin-top: 65%;
        }
    }

    @media (max-width: 320px){
        .premium-cta a.button{
            padding: 9px 20px 9px 70px;
        }

        .section .section-title img{
            display: none;
        }
    }
</style>
<div class="landing">
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH Event Tickets for WooCommerce%2$s to benefit from all features!','yith-event-tickets-for-woocommerce'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-event-tickets-for-woocommerce');?></span>
                    <span><?php _e('to the premium version','yith-event-tickets-for-woocommerce');?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="one section section-even clear">
        <h1><?php _e('Premium Features','yith-event-tickets-for-woocommerce');?></h1>
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/01.png" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/01-icon.png" />
                    <h2><?php _e('Sell reduced-price tickets','yith-event-tickets-for-woocommerce');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Offer your users the possibility to %1$spurchase a ticket at a lower price:%2$s set the discount to apply to the price and insert the notes to inform about the requirements to benefit from a reduced-price ticket. ', 'yith-event-tickets-for-woocommerce'), '<b>', '</b>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="two section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/02-icon.png" />
                    <h2><?php _e('Increase the price basing on the availability','yith-event-tickets-for-woocommerce');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('The strategy to increase the ticket price when the availability is decreasing is very used! In a few steps, %1$syou can configure an automatic increase%2$s, fixed or percentage, on the ticket price basing on the remaining quantity. %3$sChoose the quantity range to apply this action. ', 'yith-event-tickets-for-woocommerce'), '<b>', '</b>','<br>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/02.png" />
            </div>
        </div>
    </div>
    <div class="three section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/03.png" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/03-icon.png" />
                    <h2><?php _e( 'Increase the price when the event is approaching','yith-event-tickets-for-woocommerce');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Is the event date getting closer? Add more value to the %1$sremaining tickets%2$s applying a surcharge on the price. ', 'yith-event-tickets-for-woocommerce'), '<b>', '</b>' );?>
                </p>
                <p>
                    <?php echo sprintf(__('After choosing how to edit the price, the system daily checks the days that are left to the event and sets the new price.', 'yith-event-tickets-for-woocommerce'), '<b>', '</b>' );?>
                </p>
            </div>
        </div>
    </div>
    <div class="four section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/04-icon.png" />
                    <h2><?php _e('Add services to the ticket','yith-event-tickets-for-woocommerce');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Offer optional services linked to the ticket sale, such as parking, lunch or dinner included, etc. Depending on the settings, %1$sthe service can have a limited availability and entail an increase in the final ticket price.%2$s ', 'yith-event-tickets-for-woocommerce'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/04.png" />
            </div>
        </div>
    </div>
    <div class="five section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/05.png" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/05-icon.png" />
                    <h2><?php _e( 'Show the location of the event','yith-event-tickets-for-woocommerce');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Insert the address and...that\'s it! Thanks to the %1$sintegration with Google Maps%2$s, you can show the event map in a certain tab of the product page. A significant information for those users who are interested in the event. ', 'yith-event-tickets-for-woocommerce'), '<b>', '</b>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="six section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/06-icon.png" />
                    <h2><?php _e('Organizers and attendees ','yith-event-tickets-for-woocommerce');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('The tab %1$s"Assistants"%2$s of the product page allows having the complete list of organizers and users who purchased at least a ticket for the selected event in a unique section.', 'yith-event-tickets-for-woocommerce'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/06.png" />
            </div>
        </div>
    </div>
    <div class="seven section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/07.png" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCEVTI_ASSETS_URL ?>/images/premium/07-icon.png" />
                    <h2><?php _e( 'Events calendar ','yith-event-tickets-for-woocommerce');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Thanks to a useful widget, you can %1$sinsert a calendar to your site sidebars%2$s that will help your users to spot the scheduled events in a very easy way. ', 'yith-event-tickets-for-woocommerce'), '<b>', '</b>','<br>');?>
                </p>
            </div>
        </div>
    </div>
    
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH Event Tickets for WooCommerce%2$s to benefit from all features!','yith-event-tickets-for-woocommerce'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-event-tickets-for-woocommerce');?></span>
                    <span><?php _e('to the premium version','yith-event-tickets-for-woocommerce');?></span>
                </a>
            </div>
        </div>
    </div>
</div>