<?php

// Exit if accessed directly
!defined( 'YITH_WCMBS' ) && exit();

return array(
    'premium' => array(
        'landing' => array(
            'type'         => 'custom_tab',
            'action'       => 'yith_wcmbs_premium_tab',
            'hide_sidebar' => true
        )
    )
);