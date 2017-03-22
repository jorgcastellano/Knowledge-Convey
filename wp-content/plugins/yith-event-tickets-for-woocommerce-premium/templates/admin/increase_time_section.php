<div class="options_group yith_wcevti_increase_time_section yith_wcevti_increase_by_section show_if_ticket-event closed">
    <div class="yith_wcevti_increase_title">
        <h2>
            <strong class="attribute_name"><?php echo __( 'Increase price by time', 'yith-event-tickets-for-woocommerce' ) ?></strong>
        </h2>
        <div class="toggle"><i class="dashicons"></i></div>
    </div>
    <div class="yith_wcevti_increase_time_data">
        <div id="yith_wcevti_increase_time" class="increase_time_panel">

            <table class="increase_time wp-list-table widefat">
                <thead>
                <tr>
                    <th class="drag-icon"></th>
                    <th class="option-threshold"><?php _e( 'Days before event', 'yith-event-tickets-for-woocommerce' ) ?></th>
                    <th class="option-increase-type"><?php _e( 'Type', 'yith-event-tickets-for-woocommerce' ) ?></th>
                    <th class="option-increase-value"><?php _e( 'Increase', 'yith-event-tickets-for-woocommerce' ) ?></th>
                    <th class="option-actions"><?php _e( 'Actions', 'yith-event-tickets-for-woocommerce' ) ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ( ! empty( $increase_by_time ) ) {
	                foreach ( $increase_by_time as $index => $increase ) {

		                if ( isset( $increase ) ) {
			                $args = array(
				                'index'    => $index,
				                'increase' => $increase
			                );

			                wc_get_template( 'admin/increase_time_row.php', $args, '', YITH_WCEVTI_TEMPLATE_PATH );
		                }
	                }
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <button id="_add_increase_time_row" class="add_increase_button button">
                            <i class="dashicons dashicons-plus"></i>
                            <?php echo __( 'Add price-increase rule', 'yith-event-tickets-for-woocommerce' ) ?>
                        </button>
                    </td>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>