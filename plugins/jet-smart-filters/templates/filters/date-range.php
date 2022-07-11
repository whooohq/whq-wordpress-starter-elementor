<?php

if ( empty( $args ) ) {
	return;
}

$query_var   = $args['query_var'];
$current     = $this->get_current_filter_value( $args );
$from        = '';
$to          = '';

$date_format      = isset( $args['date_format'] ) ? $args['date_format'] : '';
$from_placeholder = isset( $args['from_placeholder'] ) ? $args['from_placeholder'] : '';
$to_placeholder   = isset( $args['to_placeholder'] ) ? $args['to_placeholder'] : '';

$classes = array(
	'jet-date-range'
);

if ( '' !== $args['button_icon'] ) {
	$classes[] = 'button-icon-position-' . $args['button_icon_position'];
}

/* if ( $current ) {
	$formated = explode( '-', $current );

	$from_placeholder = $formated[0];
	$to_placeholder   = $formated[1];
} */

$hide_button = isset( $args['hide_button'] ) ? $args['hide_button'] : false;

?>
<div class="<?php echo implode( ' ', $classes ) ?>" <?php $this->filter_data_atts( $args ); ?>>
	<div class="jet-date-range__inputs">
		<input
			class="jet-date-range__from jet-date-range__control"
			type="text"
			autocomplete="off"
			placeholder="<?php echo $from_placeholder ?>"
			name="<?php echo $query_var; ?>_from"
			value="<?php echo $from; ?>"
		>
		<input
			class="jet-date-range__to jet-date-range__control"
			type="text"
			autocomplete="off"
			placeholder="<?php echo $to_placeholder ?>"
			name="<?php echo $query_var; ?>_to"
			value="<?php echo $to; ?>"
		>
	</div>
	<input
		class="jet-date-range__input"
		type="hidden"
		name="<?php echo $query_var; ?>"
		value="<?php echo $current; ?>"
		data-date-format="<?php echo $date_format; ?>"
	>
	<?php if ( ! $hide_button ) : ?>
	<button
		type="button"
		class="jet-date-range__submit apply-filters__button"
	>
	<?php echo 'left' === $args['button_icon_position'] ? $args['button_icon'] : ''; ?>
		<span class="jet-date-range__submit-text"><?php echo $args['button_text']; ?></span>
	<?php echo 'right' === $args['button_icon_position'] ? $args['button_icon'] : ''; ?>
	</button>
	<?php endif; ?>
</div>
