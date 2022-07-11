<?php

if ( empty( $args ) ) {
	return;
}

$query_var   = $args['query_var'];
$current     = $this->get_current_filter_value( $args );

$date_format            = isset( $args['date_format'] ) ? $args['date_format'] : '';
$period_type            = isset( $args['period_type'] ) ? $args['period_type'] : 'week';
$datepicker_button_text = isset( $args['datepicker_button_text'] ) ? $args['datepicker_button_text'] : __( 'Select Date', 'jet-smart-filters' );
$period_duration        = isset( $args['period_duration'] ) ? $args['period_duration'] : '1';

$classes = array(
	'jet-date-period'
);

if ( '' !== $args['button_icon'] ) {
	$classes[] = 'button-icon-position-' . $args['button_icon_position'];
}

?>
<div class="<?php echo implode( ' ', $classes ) ?>" <?php $this->filter_data_atts( $args ); ?>>
	<div class="jet-date-period__wrapper">
		<div class="jet-date-period__prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
		<div class="jet-date-period__datepicker date">
			<div class="jet-date-period__datepicker-button input-group-addon"><?php echo $datepicker_button_text ?></div>
			<input
				class="jet-date-period__datepicker-input"
				name="<?php echo $query_var; ?>"
				value="<?php echo $current; ?>"
				type="hidden"
				tabindex="-1"
				data-format="<?php echo $date_format; ?>"
			>
		</div>
		<div class="jet-date-period__next"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
	</div>
</div>
