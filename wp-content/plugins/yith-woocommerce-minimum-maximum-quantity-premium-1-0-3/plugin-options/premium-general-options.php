<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

return array(

    'premium-general' => array(

        'ywmmq_main_section_title'        => array(
            'name' => __( 'Minimum Maximum Quantity settings', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type' => 'title',
        ),
        'ywmmq_enable_plugin'             => array(
            'name'    => __( 'Enable YITH WooCommerce Minimum Maximum Quantity', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_enable_plugin',
            'default' => 'yes',
        ),
        'ywmmq_main_section_end'          => array(
            'type' => 'sectionend',
        ),

        'ywmmq_cart_section_title'        => array(
            'name' => __( 'Cart restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type' => 'title',
        ),
        'ywmmq_cart_quantity_limit'       => array(
            'name'    => __( 'Enable cart quantity restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_cart_quantity_limit',
            'default' => 'yes',
        ),
        'ywmmq_cart_minimum_quantity'     => array(
            'name'                => __( 'Minimum quantity restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'                => 'number',
            'desc'                => __( 'Minimum number of items in cart. Set zero for no restrictions.' ),
            'id'                  => 'ywmmq_cart_minimum_quantity',
            'default'             => '0'
            , 'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_cart_maximum_quantity'     => array(
            'name'              => __( 'Maximum quantity restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'              => 'number',
            'desc'              => __( 'Minimum number of items in cart. Set zero for no restrictions.' ),
            'id'                => 'ywmmq_cart_maximum_quantity',
            'default'           => '0',
            'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_cart_value_limit'          => array(
            'name'    => __( 'Enable cart spend restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_cart_value_limit',
            'default' => 'yes',
        ),
        'ywmmq_cart_minimum_value'        => array(
            'name'                => __( 'Minimum spend restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'                => 'number',
            'desc'                => __( 'Minimum spend for items in cart. Set zero for no restrictions.' ),
            'id'                  => 'ywmmq_cart_minimum_value',
            'default'             => '0'
            , 'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_cart_maximum_value'        => array(
            'name'              => __( 'Maximum spend restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'              => 'number',
            'desc'              => __( 'Minimum spend for items in cart. Set zero for no restrictions.' ),
            'id'                => 'ywmmq_cart_maximum_value',
            'default'           => '0',
            'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_cart_value_shipping'       => array(
            'name'    => __( 'Include shipping rates and relative fees.', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_cart_value_shipping',
            'default' => 'no',
        ),
        'ywmmq_cart_section_end'          => array(
            'type' => 'sectionend',
        ),

        'ywmmq_product_section_title'     => array(
            'name' => __( 'Product restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type' => 'title',
        ),
        'ywmmq_product_quantity_limit'    => array(
            'name'    => __( 'Enable product quantity restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_product_quantity_limit',
            'default' => 'no',
        ),
        'ywmmq_product_minimum_quantity'  => array(
            'name'              => __( 'Minimum quantity restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'              => 'number',
            'desc'              => __( 'Minimum number of items required for each product. Set zero for no restrictions.' ),
            'id'                => 'ywmmq_product_minimum_quantity',
            'default'           => '0',
            'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_product_maximum_quantity'  => array(
            'name'              => __( 'Maximum quantity restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'              => 'number',
            'desc'              => __( 'Maximum quantity allowed for each single product. Set zero for no restrictions.' ),
            'id'                => 'ywmmq_product_maximum_quantity',
            'default'           => '0',
            'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_product_section_end'       => array(
            'type' => 'sectionend',
        ),

        'ywmmq_category_section_title'    => array(
            'name' => __( 'Category restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type' => 'title',
        ),
        'ywmmq_category_quantity_limit'   => array(
            'name'    => __( 'Enable category quantity restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_category_quantity_limit',
            'default' => 'no',
        ),
        'ywmmq_category_minimum_quantity' => array(
            'name'                => __( 'Minimum quantity restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'                => 'number',
            'desc'                => __( 'Minimum number of items in cart. Set zero for no restrictions.' ),
            'id'                  => 'ywmmq_category_minimum_quantity',
            'default'             => '0'
            , 'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_category_maximum_quantity' => array(
            'name'              => __( 'Maximum quantity restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'              => 'number',
            'desc'              => __( 'Minimum number of items in cart. Set zero for no restrictions.' ),
            'id'                => 'ywmmq_category_maximum_quantity',
            'default'           => '0',
            'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_category_value_limit'      => array(
            'name'    => __( 'Enable category spend restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_category_value_limit',
            'default' => 'no',
        ),
        'ywmmq_category_minimum_value'    => array(
            'name'                => __( 'Minimum spend restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'                => 'number',
            'desc'                => __( 'Minimum spend for items in cart. Set zero for no restrictions.' ),
            'id'                  => 'ywmmq_category_minimum_value',
            'default'             => '0'
            , 'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_category_maximum_value'    => array(
            'name'              => __( 'Maximum spend restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'              => 'number',
            'desc'              => __( 'Minimum spend for items in cart. Set zero for no restrictions.' ),
            'id'                => 'ywmmq_category_maximum_value',
            'default'           => '0',
            'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_category_section_end'      => array(
            'type' => 'sectionend',
        ),

        'ywmmq_tag_section_title'    => array(
            'name' => __( 'Tag restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type' => 'title',
        ),
        'ywmmq_tag_quantity_limit'   => array(
            'name'    => __( 'Enable tag quantity restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_tag_quantity_limit',
            'default' => 'no',
        ),
        'ywmmq_tag_minimum_quantity' => array(
            'name'                => __( 'Minimum quantity restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'                => 'number',
            'desc'                => __( 'Minimum number of items in cart. Set zero for no restrictions.' ),
            'id'                  => 'ywmmq_tag_minimum_quantity',
            'default'             => '0'
            , 'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_tag_maximum_quantity' => array(
            'name'              => __( 'Maximum quantity restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'              => 'number',
            'desc'              => __( 'Minimum number of items in cart. Set zero for no restrictions.' ),
            'id'                => 'ywmmq_tag_maximum_quantity',
            'default'           => '0',
            'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_tag_value_limit'      => array(
            'name'    => __( 'Enable tag spend restrictions', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywmmq_tag_value_limit',
            'default' => 'no',
        ),
        'ywmmq_tag_minimum_value'    => array(
            'name'                => __( 'Minimum spend restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'                => 'number',
            'desc'                => __( 'Minimum spend for items in cart. Set zero for no restrictions.' ),
            'id'                  => 'ywmmq_tag_minimum_value',
            'default'             => '0'
            , 'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_tag_maximum_value'    => array(
            'name'              => __( 'Maximum spend restriction', 'yith-woocommerce-minimum-maximum-quantity' ),
            'type'              => 'number',
            'desc'              => __( 'Maximum spend for items in cart. Set zero for no restrictions.' ),
            'id'                => 'ywmmq_tag_maximum_value',
            'default'           => '0',
            'custom_attributes' => array(
                'min'      => 0,
                'required' => 'required'
            )
        ),
        'ywmmq_tag_section_end'      => array(
            'type' => 'sectionend',
        ),

    )

);