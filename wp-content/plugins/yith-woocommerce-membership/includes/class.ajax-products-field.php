<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Outputs a custom select template in plugin options panel
 *
 * @class   YITH_FL_Ajax_Products
 * @package Yithemes
 * @since   1.0.0
 * @author  Yithemes
 *
 */
class YITH_FL_Ajax_Products {

    /**
     * Single instance of the class
     *
     * @var \YITH_FL_Ajax_Products
     * @since 1.0.0
     */
    protected static $instance;

    /**
     * Returns single instance of the class
     *
     * @return \YITH_FL_Ajax_Products
     * @since 1.0.0
     */
    public static function get_instance() {
        $self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

        if ( is_null( $self::$instance ) ) {
            $self::$instance = new $self;
        }

        return $self::$instance;
    }

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     */
    public function __construct() {
    }

    public function init_actions( $type ) {
        add_action( 'woocommerce_admin_field_' . $type, array( $this, 'output' ) );
    }

    /**
     * Outputs a custom select template in plugin options panel
     *
     * @since   1.0.0
     *
     * @param   $option
     *
     * @return  void
     * @author  Leanza Francesco <leanzafrancesco@gmail.com>
     */
    public function output( $option ) {

        $custom_attributes = array();

        if ( !empty( $option[ 'custom_attributes' ] ) && is_array( $option[ 'custom_attributes' ] ) ) {
            foreach ( $option[ 'custom_attributes' ] as $attribute => $attribute_value ) {
                $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
            }
        }

        $option_value = WC_Admin_Settings::get_option( $option[ 'id' ], $option[ 'default' ] );

        $data_selected = '';
        $value         = '';

        if ( $option[ 'multiple' ] == 'true' ) {

            $product_ids = array_filter( array_map( 'absint', explode( ',', $option_value ) ) );
            $json_ids    = array();

            foreach ( $product_ids as $product_id ) {
                $product = wc_get_product( $product_id );
                if ( is_object( $product ) ) {
                    $json_ids[ $product_id ] = wp_kses_post( $product->get_formatted_name() );
                }
            }

            $data_selected = esc_attr( json_encode( $json_ids ) );
            $value         = implode( ',', array_keys( $json_ids ) );

        } else {

            if ( $option_value != '' ) {
                if ( $product = wc_get_product( $option_value ) ) {
                    $data_selected = wp_kses_post( $product->get_formatted_name() );
                    $value         = $option_value;
                }
            }

        }

        ?>
        <tr valign="top" class="titledesc">
            <th scope="row">
                <label for="<?php echo esc_attr( $option[ 'id' ] ); ?>"><?php echo esc_html( $option[ 'name' ] ); ?></label>
            </th>
            <td class="forminp forminp-<?php echo sanitize_title( $option[ 'type' ] ) ?>">
                <input
                    type="hidden"
                    style="<?php echo esc_attr( $option[ 'css' ] ); ?>"
                    class="<?php echo esc_attr( $option[ 'class' ] ); ?>"
                    id="<?php echo esc_attr( $option[ 'id' ] ); ?>"
                    name="<?php echo esc_attr( $option[ 'id' ] ); ?>"
                    data-placeholder="<?php _e( 'Search for a product...', 'yith-woocommerce-membership' ) ?>"
                    data-action="woocommerce_json_search_products_and_variations"
                    data-multiple="<?php echo $option[ 'multiple' ] ?>"
                    data-selected="<?php echo $data_selected; ?>"
                    value="<?php echo $value; ?>"
                    <?php echo implode( ' ', $custom_attributes ); ?>/>
                <span class="description"><?php echo $option[ 'desc' ] ?></span>
            </td>
        </tr>
        <?php
    }
}

/**
 * Unique access to instance of YITH_FL_Ajax_Products class
 *
 * @return \YITH_FL_Ajax_Products
 * @since 1.0.0
 */
function YITH_FL_Ajax_Products() {
    return YITH_FL_Ajax_Products::get_instance();
}