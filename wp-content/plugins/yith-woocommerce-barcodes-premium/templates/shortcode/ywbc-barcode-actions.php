<?php

//  if a capability is entered, check if the current user can
if ( $fields['capability'] && ($fields['capability'] != 'all') && ! current_user_can( $fields['capability'] ) ) {
	return;
}
$actions = array_map( 'trim', explode( ",", $fields['actions'] ) );

if ( ! $actions ) {
	return;
}
?>
<div class="yith-barcode-actions">
	<form name="yith-barcodes-form" method="post">
		<input type="text" name="yith-barcode-value" value="" placeholder="<?php _e( 'Enter the barcode here', 'yith-woocommerce-barcodes' ); ?>">
		<input type="hidden" name="yith-type" value="<?php echo $fields['search_type']; ?>" placeholder="<?php _e( 'Enter the barcode here', 'yith-woocommerce-barcodes' ); ?>">
		<div class="yith-barcode-buttons">
			<?php foreach ( $actions as $action ): ?>
				<?php $action = sanitize_title_for_query(strtolower($action)); ?>
				<button name="ywbc-action" class="<?php echo $action; ?>" data-action="<?php echo $action; ?>"><?php echo $action; ?></button>
			<?php endforeach; ?>
		</div>
	</form>
</div>