<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Display metal prices using shortcode
function gpw_display_prices_shortcode() {
	global $wpdb;

	$table_name = GPW_TABLE_NAME;

	$results = $wpdb->get_results( "SELECT * FROM $table_name" );

	ob_start();
	?>
	<table class="gpw-prices">
		<thead>
			<tr>
				<th>Metal Name</th>
				<th>Metal Price</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $results as $result ) : ?>
				<tr>
					<td><?php echo esc_html( $result->metal_name ); ?></td>
					<td><?php echo esc_html( $result->metal_price ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php
	return ob_get_clean();
}
add_shortcode( 'gpw_prices', 'gpw_display_prices_shortcode' );