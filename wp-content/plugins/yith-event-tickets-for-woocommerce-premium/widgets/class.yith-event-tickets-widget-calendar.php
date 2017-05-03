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
 * @class      YITH_Widget_Calendar
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_Widget_Calendar' ) ) {
    class YITH_Widget_Calendar extends WP_Widget {


        public function __construct() {

            $widget_args = array(
                'classname' => 'yith-widget-calendar',
                'name' => 'YITH Event Tickets Calendar',
                'description' => __('Display events from Event Tickets...')
            );

            parent::__construct( $widget_args['classname'], $widget_args['name'], $widget_args );
        }


        public function widget( $args, $instance ) {
            $title = ( isset($instance['title'])) ? $instance['title'] : __('Calendar Event Tickets');

            echo $args['before_widget'];
            echo '<h2 class="widget-title">'. $title . '</h2>';

            echo  do_shortcode('[event_calendar]');
        }

        public function update( $new_instance, $old_instance ) {

            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];

            return $instance;
        }

        public function form ($instance){

            $default = array(
                'title' => ''
            );

            $instance = wp_parse_args((array) $instance, $default);
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title', 'yith-event-tickets-for-woocommerce')?>:</label>
                </br>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>"
                       value="<?php echo $instance['title']; ?>">
            </p>
            <?php

        }

    }
}