<?php

if ( empty( $args ) ) {
	return;
}

$options     = $args['options'];
$filter_type = ! empty( $args['behavior'] ) ? $args['behavior'] : 'checkbox';
$query_var   = 'alphabet-filter' . $args['filter_id'];

if ( ! $options ) {
	return;
}

$current = $this->get_current_filter_value( $args );

?>
<div class="jet-alphabet-list" <?php $this->filter_data_atts( $args ); ?>><?php
	echo '<div class="jet-alphabet-list__wrapper">';

	foreach ( $options as $value ) {
		$checked = '';

		if ( $current ) {
			if ( is_array( $current ) && in_array( $value, $current ) ) {
				$checked = 'checked';
			}

			if ( ! is_array( $current ) && $value == $current ) {
				$checked = 'checked';
			}
		}

		if( '' !== $value ){
			include jet_smart_filters()->get_template( 'filters/alphabet-item.php' );
		}
	}

	echo '</div>';
?></div>
