<?php
/*
 * This file belongs to the YITH framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

return array(

    'settings' => apply_filters( 'yith_wcte_settings_options', array(

            'settings_options_start'    => array(
                'type' => 'sectionstart',
                'id'   => 'yith_wcte_settings_options_start'
            ),

            'settings_options_title'    => array(
                'title' => _x( 'General settings', 'Panel: page title', 'yith-event-tickets-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'yith_wcte_settings_options_title'
            ),

            'settings_enable_location' => array(
                'title'   => _x( 'Enable Location site:', 'Admin option: Enable location maps', 'yith-event-tickets-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to enable Google Maps event location', 'Admin option: Enable location maps',
                    'yith-event-tickets-for-woocommerce' ),
                'id'      => 'yith_wcte_enable_location',
                'default' => 'yes'
            ),

            'settings_api_key_gmaps' => array(
                'title'   => _x( 'Google Maps API Key:', 'Admin option: Google Maps Api Key', 'yith-event-tickets-for-woocommerce' ),
                'type'    => 'text',
                'desc'    => _x( 'Your Api Key here, you need a Google Developer account to generate your Api Key for Google Maps', 'Admin option:
                Google Maps Api Key', 'yith-event-tickets-for-woocommerce' ),
                'id'      => 'yith_wcte_api_key_gmaps'
            ),

            'settings_options_end'      => array(
                'type' => 'sectionend',
                'id'   => 'yith_wcte_settings_options_end'
            ),
        )
    )
);