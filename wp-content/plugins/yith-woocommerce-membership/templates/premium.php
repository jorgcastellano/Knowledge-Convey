<style>
.section{
    margin-left: -20px;
    margin-right: -20px;
    font-family: 'Raleway',san-serif;
    overflow-x: hidden;
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
    background: url(<?php echo YITH_WCMBS_ASSETS_URL?>/images/upgrade.png) #ff643f no-repeat 13px 13px;
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
    background: url(<?php echo YITH_WCMBS_ASSETS_URL?>/images/upgrade.png) #971d00 no-repeat 13px 13px;
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
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/01-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.two{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/02-bg.png) no-repeat; background-position: 15% 100%;
}
.section.three{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/03-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.four{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/04-bg.png) no-repeat; background-position: 15% 100%;
}
.section.five{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/05-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.six{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/06-bg.png) no-repeat; background-position: 15% 100%;
}
.section.seven{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/07-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.eight{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/08-bg.png) no-repeat; background-position: 15% 100%;
}
.section.nine{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/09-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.ten{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/10-bg.png) no-repeat; background-position: 15% 100%;
}
.section.eleven{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/11-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.twelve{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/12-bg.png) no-repeat; background-position: 15% 100%;
}
.section.thirteen{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/13-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.fourteen{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/12-bg.png) no-repeat; background-position: 15% 100%;
}
.section.fifteen{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/15-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.sixteen{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/16-bg.png) no-repeat; background-position: 15% 100%;
}
.section.seventeen{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/17-bg.png) no-repeat #fff; background-position: 85% 75%;
}
.section.twenty{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/20-bg.png) no-repeat; background-position: 15% 100%;
}
.section.twentyone{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/21-bg.png) no-repeat; background-position: 85% 100%;
}
.section.twentytwo{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/22-bg.png) no-repeat; background-position: 15% 100%;
}
.section.twentythree{
    background: url(<?php echo YITH_WCMBS_ASSETS_URL ?>/images/23-bg.png) no-repeat; background-position: 85% 100%;
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
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Membership%2$s to benefit from all features!','yith-woocommerce-membership'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-woocommerce-membership');?></span>
                    <span><?php _e('to the premium version','yith-woocommerce-membership');?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="one section section-even clear">
        <h1><?php _e('Premium Features','yith-woocommerce-membership');?></h1>
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/01.png" alt="Feature 01" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/01-icon.png" alt="icon 01"/>
                    <h2><?php _e('Unlimited membership plans','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('When a shop has different contents of any kind, it\'s easy to think about managing them in various memberships. In opposition to the free version, there are %1$sno limits%2$s for the creation of tailored plans for users, giving them the freedom to register to the contents that most suit their needs. ', 'yith-woocommerce-membership'), '<b>', '</b>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="two section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/02-icon.png" alt="icon 02" />
                    <h2><?php _e('Expiration date','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('You could decide to give access to specific contents of a membership only for a certain number of days. In this way, you can loyalize your users, and %1$soffer them the renewal of the plan at a reduced price%2$s, sending them an email with a discount code.', 'yith-woocommerce-membership'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/02.png" alt="feature 02" />
            </div>
        </div>
    </div>
    <div class="three section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/03.png" alt="Feature 03" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/03-icon.png" alt="icon 03" />
                    <h2><?php _e( 'Content availability','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Any plan of your site may include %1$sposts, pages%2$s and %1$sproducts%2$s. If you have already set all contents by tags and categories, you will always be free to add %1$snew resources in the membership%2$s. A small step to crowd quickly the plan you have just created.%3$s But there\'s more: you can also set every single element of the plan visible to users after a certain number of days after the registration to the membership.', 'yith-woocommerce-membership'), '<b>', '</b>','<br>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="four section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/04-icon.png" alt="icon 04" />
                    <h2><?php _e('Restricted media files','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Today information run fast on the internet and often you could find freely the resources you have included in your shop only after a purchase. With %1$sYITH WooCommerce Membership%2$s, the information confidentiality is assured, and even all media files, like videos, images, PDFs you have uploaded in your WordPress gallery, will be available only to those users that are logged in and registered to your membership.', 'yith-woocommerce-membership'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/04.png" alt="Feature 04" />
            </div>
        </div>
    </div>
    <div class="five section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/05.png" alt="Feature 05" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/05-icon.png" alt="icon 05" />
                    <h2><?php _e('Linked memberships','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'Interaction among more memberships is one off the biggest news of the premium version of YITH WooCommerce Membership.%3$s Linking two or more plans, %1$seach member of one the two will have access to the contents of both plans, and to all those linked.%2$s A plus to satisfy completely your customers.','yith-woocommerce-membership' ),'<b>','</b>','<br>') ?>
                </p>
            </div>
        </div>
    </div>
    <div class="six section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/06-icon.png" alt="icon 06" />
                    <h2><?php _e('A message system','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('An important loyalizing technique is let your users contact the administrator easily to solve any kind of doubt. With the %1$sYITH WooCommerce Membership – Messages widget%2$s, users can send their messages and read the administrator\'s answers at any time. ','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/06.png" alt="Feature 06" />
            </div>
        </div>
    </div>
    <div class="seven section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/07.png" alt="Feature 07" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/07-icon.png" alt="icon 07" />
                    <h2><?php _e('Membership history','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'Thanks to the related section, you can have a complete overview of users\' plans, and %1$sedit quickly a specific membership%2$s, just like the status or the expiration date.','yith-woocommerce-membership' ),'<b>','</b>') ?>
                </p>
            </div>
        </div>
    </div>
    <div class="eight section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/08-icon.png" alt="icon 08" />
                    <h2><?php _e('Membership status','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('The status of the content plans can be modified in the course of time. It can be because of an expiration date of the membership, or maybe because of your will to delete the membership for one or more users. %1$sEach single modification will be recorded in the user profile.%2$s','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/08.png" alt="Feature 08" />
            </div>
        </div>
    </div>
    <div class="nine section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/09.png" alt="Feature 09" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/09-icon.png" alt="icon 09" />
                    <h2><?php _e('Access to restricted contents','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php _e( 'You can apply one of the following actions to those users that try to view contents that require a membership access: ','yith-woocommerce-membership' );?>
                </p>
                <ul>
                    <li><?php _e( 'redirection to 404 error page','yith-woocommerce-membership' ); ?></li>
                    <li><?php _e( 'redirection to a specific URL','yith-woocommerce-membership' ); ?></li>
                    <li><?php _e( 'visualization of an alternative text added in the content','yith-woocommerce-membership' ); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="ten section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/10-icon.png" alt="icon 10" />
                    <h2><?php _e('Emails','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Sending emails automatically is a vital feature for a cutting-edge shop. This is why the premium version solves this need offering you to %1$scustomize the generated emails%2$s for any new membership registration, for the deletion of a membership, or when one is getting to its end. ','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/10.png" alt="Feature 10" />
            </div>
        </div>
    </div>
    <div class="eleven section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/11.png" alt="Feature 11" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/11-icon.png" alt="icon 11" />
                    <h2><?php _e('Restricted area for users','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Inside the %1$s"My Account" page%2$s, users will be free to view all the unlocked contents for each membership. An important solution to ease the %1$suser experience%2$s on your e-commerce site.','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="twelve section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/12-icon.png" alt="icon 12" />
                    <h2><?php _e('Shortcodes','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Thanks to shortcodes, you will be free to show your users the %1$scontent of a specific membership%2$s, to let them know what they will have available once purchased. ','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/12.png" alt="Feature 12" />
            </div>
        </div>
    </div>
    <div class="thirteen section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/13.png" alt="Feature 13" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/13-icon.png" alt="icon 13" />
                    <h2><?php _e('How to manage products','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Two different options to manage membership content. %1$sAllow only members to see contents%2$s, or allow everybody to see them keeping the %1$sdownload available only to members%2$s.','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="fourteen section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/12-icon.png" alt="icon 14" />
                    <h2><?php _e(' Purchase only if registered','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('From the option panel of the plugin, you can decide to allow every user to purchase the %1$smembership%2$s, or allow only registered users. ','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/14.png" alt="Feature 14" />
            </div>
        </div>
    </div>
    <div class="fifteen section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/15.png" alt="Feature 15" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/15-icon.png" alt="icon 13" />
                    <h2><?php _e('Compatible with YITH WooCommerce Subscription','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('With %1$sYITH WooCommerce Subscription%2$s you will be free to sell memberships with a subscription plan. The most used solution by online sellers: it can be perfect for you too!','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="sixteen section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/16-icon.png" alt="icon 16" />
                    <h2><?php _e('LIMITED DOWNLOAD ','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Thanks to the new credit system, you\'ll be able to %1$sregulate access to contents of the configured membership plans%2$s in an advanced way and apply many new strategies and combinations.','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/16.png" alt="Feature 16" />
            </div>
        </div>
    </div>
    <div class="seventeen section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/17.png" alt="Feature 17" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/17-icon.png" alt="icon 17" />
                    <h2><?php _e('Report','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('A custom-tailored report to track all downloads of products included in a membership. You can also verify the number of memberships activated at that moment or currently active. %1$sReports are essential to achieve success, you cannot avoid!%2$s','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="eighteen section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/18-icon.png" alt="icon 18" />
                    <h2><?php _e('Membership advanced management ','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('This action on memberships is a very delicate one, but really useful if you have to edit a specific detail concerning a user membership.Thanks to the advanced management, you\'ll be able to edit important information associated to the membership, such as order number, the associated plan, activation and expiry date, current status to mention just a few.','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/18.png" alt="Feature 16" />
            </div>
        </div>
    </div>
    <div class="nineteen section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/19.png" alt="Feature 19" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/19-icon.png" alt="icon 19" />
                    <h2><?php _e('Price and "Add to cart" button','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('It might be necessary, besides logical, to hide price and "add to cart" button that appear on the page of products included in the membership plan joined.%1$sThanks to an option included in the plugin, you can do that and keep the plugin behaviour unchanged for other users.','yith-woocommerce-membership'),'<br>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="twenty section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/20-icon.png" alt="icon 20" />
                    <h2><?php _e('User registration','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php _e('If you want to persuade your users to subscribe to what you offer on your site, the best way to succeed is to let them try.','yith-woocommerce-membership'); ?>
                </p>
                <p>
                    <?php echo sprintf( __('%1$sCreate a custom membership plan and associate it automatically to all the users after their registration.%2$s%3$s The contents will be immediately available in their reserved area. ','yith-woocommerce-membership'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/20.png" alt="Feature 20" />
            </div>
        </div>
    </div>
    <div class="twentyone section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/21.png" alt="Feature 21" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/21-icon.png" alt="icon 21" />
                    <h2><?php _e('Free shipping','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Would you like to offer a %1$sprivilege to your registered users by offering them the free shipping%2$s for the products of your shop? Now you can! Thanks to YITH WooCommerce Membership you can create a new shipping method and associate it to the membership plan.','yith-woocommerce-membership'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="twentytwo section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/22-icon.png" alt="icon 22" />
                    <h2><?php _e('Link Access','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('You want to add links within your pages but don’t want them to be visible to everybody?%3$s Thanks to YITH WooCommerce Membership you will be able to satisfy this need, by being able to %1$sshow links only to users who own a specific type of membership.%2$s','yith-woocommerce-membership'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/22.png" alt="Feature 22" />
            </div>
        </div>
    </div>
    <div class="twentythree section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/23.png" alt="Feature 23" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL?>/images/23-icon.png" alt="icon 23" />
                    <h2><?php _e('Multiple content for each page','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Great news from YITH WooCommerce Membership! Starting today you will be able to %1$sset up different content for each one of the membership plans%2$s you have created, within the same page!%3$s The system will then verify the type of membership owned by the users and automatically display the content they are entitled to get.','yith-woocommerce-membership'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="twentyfour section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/24-icon.png" alt="icon 24" />
                    <h2><?php _e('Discounts for your members','yith-woocommerce-membership');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('How about applying specific discounts on your products to those who purchased a membership plan on your site?%3$s Nothing could be easier! Use the plugin in combination with %1$sYITH WooCommerce Dynamic Pricing and Discounts%2$s to reward your member  loyalty. ','yith-woocommerce-membership'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCMBS_ASSETS_URL ?>/images/24.png" alt="Feature 24" />
            </div>
        </div>
    </div>
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Membership%2$s to benefit from all features!','yith-woocommerce-membership'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-woocommerce-membership');?></span>
                    <span><?php _e('to the premium version','yith-woocommerce-membership');?></span>
                </a>
            </div>
        </div>
    </div>
</div>