<?php
/**
 * Item price
 *
 * @package cartflows
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="wcf-price">
	<div class="wcf-display-price wcf-field-label"><?php echo wp_kses_post( $price_data['original_price'] ); ?></div>
</div>
