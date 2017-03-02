<?php

$my_orders_columns = apply_filters( 'yith_ywbc_search_products_columns', array(
	'post-title' => __( 'Product', 'yith-woocommerce-barcodes' ),
	'barcode'    => __( 'Code', 'yith-woocommerce-barcodes' ),
	/*'view'       => __( 'View', 'yith-woocommerce-barcodes' ),*/
) );

if ( $posts ) :
	?>
	<h3><?php _e( 'Product list', 'yith-woocommerce-barcodes' ); ?></h3>

	<table class="shop_table shop_table_responsive ywbc-search-by-products">
		<thead>
		<tr>
			<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
				<th class="<?php echo esc_attr( $column_id ); ?>">
					<span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
			<?php endforeach; ?>
		</tr>
		</thead>

		<tbody>
		<?php foreach ( $posts as $post ) :
			$product = wc_get_product( $post );
			?>
			<tr class="product">
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
						<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
							<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $product ); ?>

						<?php elseif ( 'post-title' === $column_id ) : ?>
							<?php echo $product->get_title(); ?>

						<?php elseif ( 'barcode' === $column_id ) : ?>
							<?php echo YITH_Barcode::get( $product->id )->get_display_value(); ?>

						<?php elseif ( 'view' === $column_id ) : ?>
							<?php
							//echo '<a href="' . esc_url( $order->get_view_order_url() ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( __( 'View', 'yith-woocommerce-barcodes' ) ) . '</a>';

							?>
						<?php endif; ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<span><?php _e( 'No product matches the selected criteria', 'yith-woocommerce-barcodes' ); ?></span>

<?php endif;