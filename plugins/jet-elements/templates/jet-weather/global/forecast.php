<?php
/**
 * Forecast weather template.
 */
$settings = $this->get_settings_for_display();
$data     = $this->weather_data;

$forecast_data = $data['forecast'];

$start_index = 0;

if ( isset( $settings['show_current_weather'] ) && 'true' === $settings['show_current_weather'] ) {
	$start_index = 1;
}

$forecast_days = ! empty( $settings['forecast_count']['size'] ) ? abs( $settings['forecast_count']['size'] ) : 5;
$forecast_days = $forecast_days + $start_index;

?>
<div class="jet-weather__forecast"><?php
	for ( $i = $start_index; $i < $forecast_days; $i ++ ) { ?>
		<div class="jet-weather__forecast-item">
			<div class="jet-weather__forecast-day"><?php echo $this->get_week_day_from_date( $forecast_data[ $i ]['date'] ); ?></div>
			<div class="jet-weather__forecast-icon" title="<?php echo esc_attr( $this->get_weather_desc( $forecast_data[ $i ]['code'] ) ); ?>"><?php echo $this->get_weather_svg_icon( $forecast_data[ $i ]['code'], true ); ?></div>
			<div class="jet-weather__forecast-max-temp"><?php echo $this->get_weather_temp( $forecast_data[ $i ]['temp_max'] ); ?></div>
			<div class="jet-weather__forecast-min-temp"><?php echo $this->get_weather_temp( $forecast_data[ $i ]['temp_min'] ); ?></div>
		</div>
	<?php }
?></div>
