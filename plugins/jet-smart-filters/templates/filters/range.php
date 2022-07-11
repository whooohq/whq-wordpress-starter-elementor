<?php

if ( empty( $args ) ) {
	return;
}

$container_class = 'jet-range';
if ( wp_is_mobile() ) {
	$container_class .= ' jet-range--mobile';
}

$query_var      = $args['query_var'];
$inputs_enabled = $args['inputs_enabled'];
$prefix         = $args['prefix'];
$suffix         = $args['suffix'];
$current        = $this->get_current_filter_value( $args );

if ( $current ) {
	$slider_val = explode( '_', $current );
} else {
	$slider_val = array( $args['min'], $args['max'] );
}

?>

<div class="<?php echo $container_class; ?>" <?php $this->filter_data_atts( $args ); ?>>
	<div class="jet-range__slider">
		<div class="jet-range__slider__track">
			<div class="jet-range__slider__track__range"></div>
		</div>
		<input type="range" class="jet-range__slider__input jet-range__slider__input--min" step="<?php echo $args['step']; ?>" min="<?php echo $args['min']; ?>" max="<?php echo $args['max']; ?>" value="<?php echo $slider_val[0] ?>" tabindex="-1">
		<input type="range" class="jet-range__slider__input jet-range__slider__input--max" step="<?php echo $args['step']; ?>" min="<?php echo $args['min']; ?>" max="<?php echo $args['max']; ?>" value="<?php echo $slider_val[1] ?>" tabindex="-1">
	</div>
	<?php if ( $inputs_enabled ) : ?>
	<div class="jet-range__inputs">
		<div class="jet-range__inputs__container">
			<div class="jet-range__inputs__group">
				<?php if ( $prefix ) : ?>
				<span class="jet-range__inputs__group__text"><?php echo $prefix; ?></span>
				<?php endif; ?>
				<input type="number" class="jet-range__inputs__min" step="<?php echo $args['step']; ?>" min="<?php echo $args['min']; ?>" max="<?php echo $args['max']; ?>" value="<?php echo $slider_val[0]; ?>"/>
				<?php if ( $suffix ) : ?>
				<span class="jet-range__inputs__group__text"><?php echo $suffix ?></span>
				<?php endif; ?>
			</div>
			<div class="jet-range__inputs__group">
				<?php if ( $prefix ) : ?>
				<span class="jet-range__inputs__group__text"><?php echo $prefix; ?></span>
				<?php endif; ?>
				<input type="number" class="jet-range__inputs__max" step="<?php echo $args['step']; ?>" min="<?php echo $args['min']; ?>" max="<?php echo $args['max']; ?>" value="<?php echo $slider_val[1]; ?>"/>
				<?php if ( $suffix ) : ?>
				<span class="jet-range__inputs__group__text"><?php echo $suffix ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php else : ?>
	<div class="jet-range__values">
		<span class="jet-range__values-prefix"><?php
			echo $prefix;
		?></span><span class="jet-range__values-min"><?php
			echo number_format(
				$slider_val[0],
				$args['format']['decimal_num'],
				$args['format']['decimal_sep'],
				$args['format']['thousands_sep']
			);
		?></span><span class="jet-range__values-suffix"><?php
			echo $suffix;
		?></span> — <span class="jet-range__values-prefix"><?php
			echo $prefix;
		?></span><span class="jet-range__values-max"><?php
			echo number_format(
				$slider_val[1],
				$args['format']['decimal_num'],
				$args['format']['decimal_sep'],
				$args['format']['thousands_sep']
			);;
		?></span><span class="jet-range__values-suffix"><?php
			echo $suffix;
		?></span>
	</div>
	<?php endif; ?>
</div>