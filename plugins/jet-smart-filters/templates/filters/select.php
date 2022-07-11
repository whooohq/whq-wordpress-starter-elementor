<?php

if ( empty( $args ) ) {
	return;
}

$current = $this->get_current_filter_value( $args );

?>
<div class="jet-select" <?php $this->filter_data_atts( $args ); ?>>
	<?php

	$options   = $args['options'];
	$query_var = $args['query_var'];

	$classes = array( 'jet-select__control' );

	// is hierarchical
	if ( $args['is_hierarchical'] ) {
		if ( ! empty( $args['current_value'] ) ) {
			$current = $args['current_value'];
		} else {
			$current = false;
		}

		if ( ! wp_doing_ajax() && ! empty( $args['is_loading'] ) ) {
			if ( ! $current ) {
				$current = 'loading';
			}

			$options = array( $current => __( 'Loading...', 'jet-smart-filters' ) );
		}

		$classes[] = 'depth-' . $args['depth'];

		$filter_label = $args['filter_label'];
		include jet_smart_filters()->get_template( 'common/filter-label.php' );
	}

	?>

	<?php if ( ! empty( $options ) || $args['is_hierarchical'] ) : ?>

		<select
			class="<?php echo implode( ' ', $classes ); ?>"
			name="<?php echo $query_var; ?>"
		>
		<?php if ( ! empty( $args['placeholder'] ) ) { ?>
			<option value=""><?php echo $args['placeholder']; ?></option>
		<?php } ?>

		<?php

		foreach ( $options as $value => $label ) {

			$selected = '';

			if ( $current ) {

				if ( is_array( $current ) && in_array( $value, $current ) ) {
					$selected = ' selected';
				}

				if ( ! is_array( $current ) && $value == $current ) {
					$selected = ' selected';
				}

			}

			?>
			<option
				value="<?php echo $value; ?>"
				data-label="<?php echo $label; ?>"
				data-counter-prefix="("
				data-counter-suffix=")"
				<?php echo $selected; ?>
			><?php echo $label; ?></option>
			<?php

		}

		?></select>

	<?php endif; ?>

</div>
