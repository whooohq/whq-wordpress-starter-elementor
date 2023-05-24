<?php

$settings = $this->get_settings_for_display();

$countdown_type = isset( $settings['type'] ) ? $settings['type'] : 'due_date';

$this->add_render_attribute( 'countdown', 'class', 'jet-countdown-timer' );
$this->add_render_attribute( 'countdown', 'data-type', esc_attr( $countdown_type ) );

switch( $countdown_type ) {
	case 'due_date':
		$this->add_render_attribute( 'countdown', 'data-due-date', $this->get_date_from_setting( 'due_date' ) );
		break;

	case 'evergreen':
		$this->add_render_attribute( 'countdown', 'data-evergreen-interval', $this->get_evergreen_interval( $settings ) );
		break;

	case 'endless':
		$this->add_render_attribute( 'countdown', 'data-start-date', $this->get_date_from_setting( 'start_date' ) );
		$this->add_render_attribute( 'countdown', 'data-restart-interval', $this->get_restart_interval( $settings ) );
		$this->add_render_attribute( 'countdown', 'data-expire-actions', json_encode( array( 'restart' ) ) );
		break;
}

$is_edit_mode = jet_elements()->elementor()->editor->is_edit_mode();

if ( ! $is_edit_mode && ! empty( $settings['expire_actions'] ) && is_array( $settings['expire_actions'] ) ) {
	$this->add_render_attribute( 'countdown', 'data-expire-actions', json_encode( $settings['expire_actions'] ) );
}

if ( ! $is_edit_mode && ! empty( $settings['expire_redirect_url']['url'] ) ) {
	$this->add_render_attribute( 'countdown', 'data-expire-redirect-url', esc_url( $settings['expire_redirect_url']['url'] ) );
}
?>

<div <?php $this->print_render_attribute_string( 'countdown' ); ?>>
	<?php $this->_glob_inc_if( '00-days', array( 'show_days' ) ); ?>
	<?php $this->_glob_inc_if( '01-hours', array( 'show_hours' ) ); ?>
	<?php $this->_glob_inc_if( '02-minutes', array( 'show_min' ) ); ?>
	<?php $this->_glob_inc_if( '03-seconds', array( 'show_sec' ) ); ?>
</div>

<?php $this->_html( 'message_after_expire', '<div class="jet-countdown-timer-message">%s</div>' );
