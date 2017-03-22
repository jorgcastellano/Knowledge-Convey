<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCEVTI_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Tickets_Premium_Premium
 * @package    Yithemes
 * @since      Version 2.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_Tickets_Premium' ) ) {
    /**
     * Class YITH_Tickets_Premium
     *
     * @author Francisco Mateo
     */
    class YITH_Tickets_Premium extends YITH_Tickets{

        /**
         * Construct
         *
         * @author Francisco Mateo
         * @since 1.0
         */
        public function __construct(){

            add_action('yith_wcevti_require', array($this, 'require_premium'), 10);
            add_filter('yith_wcevti_require_class', array($this, 'require_class_premium'), 10, 1);
            add_action( 'init', array( 'YITH_Tickets_Shortcodes', 'init' ));

            add_action( 'init', array( 'YITH_Tickets_Shortcodes', 'init' ));

            /* === Widget Init === */
            add_action( 'widgets_init', array( $this, 'widgets_init' ) );


            parent::__construct();
        }

        /**
         * Main plugin Instance
         *
         * @return YITH_Tickets Main instance
         * @author Francisco Mateo
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Class Initialization
         *
         * Instance the admin premium class
         *
         * @author Francisco Mateo
         * @since  1.0
         * @return void
         * @access protected
         */
        public function init() {
            global $wp_query;
            if ( is_admin() ) {
                $this->admin = new YITH_Tickets_Admin_Premium();
            }

            if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX )) {

                $this->frontend = new YITH_Tickets_Frontend_Premium();
            }
        }
        public function require_premium(){
            require_once( YITH_WCEVTI_WIDGET_PATH . 'class.yith-event-tickets-widget-calendar.php' );
        }
        public function require_class_premium($require){
            array_push($require['common'], 'includes/class.yith-event-tickets-shortcodes.php' );
            array_push($require['common'], 'includes/functions.wcevti-premium.php' );
            array_push($require['frontend'], 'includes/class.yith-event-tickets-frontend-premium.php' );
            array_push($require['admin'], 'includes/class.yith-event-tickets-admin-premium.php');

            return $require;
        }
        public function widgets_init(){
            register_widget('YITH_Widget_Calendar');
        }
    }
}